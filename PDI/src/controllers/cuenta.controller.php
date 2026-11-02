<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/auth.service.php';
require_once __DIR__ . '/../services/publicaciones.service.php';
require_once __DIR__ . '/../services/catalogo.service.php';
require_once __DIR__ . '/../services/carrito.service.php';
require_once __DIR__ . '/catalogo.controller.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

function show_cuenta(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();
    $usuarioId = (int) $_SESSION['user_id'];
    $usuario = obtener_usuario_por_id($usuarioId);

    if ($usuario === null) {
        unset($_SESSION['user_id'], $_SESSION['user_nombre']);

        return redirectTo('/auth/login');
    }

    if (!is_string($_SESSION['csrf_token'] ?? null) || $_SESSION['csrf_token'] === '') {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $query = $request->getQueryParams();

    return view($renderer, $response, '/cuenta/index.php', [
        'title' => 'Mi cuenta | Emprendi',
        'stylesheet' => '/styles.css',
        'cantidadCarrito' => cantidad_total_carrito(),
        'usuario' => $usuario,
        'publicaciones' => listar_publicaciones_usuario($usuarioId),
        'publicacionCreada' => ($query['publicacion'] ?? '') === 'creada',
        'publicacionEliminada' => ($query['eliminacion'] ?? '') === 'completada',
        'errorEliminacion' => ($query['eliminacion'] ?? '') === 'error',
        'csrfToken' => $_SESSION['csrf_token'],
    ]);
}

function handle_eliminar_publicacion(Request $request, Response $response, array $args): Response
{
    ensureSession();

    $id = filter_var($args['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 1) {
        $response->getBody()->write('No se encontró la publicación solicitada.');

        return $response->withStatus(404);
    }

    $body = $request->getParsedBody();
    $body = is_array($body) ? $body : [];
    $csrfToken = is_string($body['csrf_token'] ?? null) ? $body['csrf_token'] : '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    if (!is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $csrfToken)) {
        return redirectTo('/cuenta?eliminacion=error', 303);
    }

    try {
        $eliminada = eliminar_publicacion_usuario($id, (int) $_SESSION['user_id']);
    } catch (PDOException | RuntimeException $exception) {
        error_log('No se pudo eliminar la publicación: ' . $exception->getMessage());

        return redirectTo('/cuenta?eliminacion=error', 303);
    }

    if ($eliminada === null) {
        return redirectTo('/cuenta?eliminacion=error', 303);
    }

    unset($_SESSION['carrito']['publicacion-' . $id]);

    $imagePath = $eliminada['imagen_path'];
    if ($imagePath !== null) {
        try {
            eliminar_imagen_publicacion($imagePath);
        } catch (InvalidArgumentException | RuntimeException $exception) {
            error_log('No se pudo limpiar la imagen de la publicación eliminada: ' . $exception->getMessage());
        }
    }

    return redirectTo('/cuenta?eliminacion=completada', 303);
}

function show_publicacion_form(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();
    if (!is_string($_SESSION['csrf_token'] ?? null) || $_SESSION['csrf_token'] === '') {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return view($renderer, $response, '/publicaciones/create.php', [
        'title' => 'Crear publicación | Emprendi',
        'stylesheet' => '/styles.css',
        'cantidadCarrito' => cantidad_total_carrito(),
        'usuarioAutenticado' => true,
        'csrfToken' => $_SESSION['csrf_token'],
        'errors' => [],
        'data' => [],
    ]);
}

function handle_crear_publicacion(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();
    $body = $request->getParsedBody();
    $body = is_array($body) ? $body : [];
    if (!is_string($_SESSION['csrf_token'] ?? null) || $_SESSION['csrf_token'] === '') {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrfToken = is_string($body['csrf_token'] ?? null) ? $body['csrf_token'] : '';
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        return view($renderer, $response, '/publicaciones/create.php', [
            'title' => 'Crear publicación | Emprendi',
            'stylesheet' => '/styles.css',
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'errors' => ['El formulario venció. Actualizá la página e intentá nuevamente.'],
            'data' => [],
        ])->withStatus(403);
    }

    $validation = validar_publicacion($body);
    if ($validation['errors'] !== []) {
        return view($renderer, $response, '/publicaciones/create.php', [
            'title' => 'Crear publicación | Emprendi',
            'stylesheet' => '/styles.css',
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'errors' => $validation['errors'],
            'data' => $validation['data'],
        ])->withStatus(400);
    }

    $uploadedFiles = $request->getUploadedFiles();
    $imagen = $uploadedFiles['imagen'] ?? null;
    $imagen = $imagen instanceof UploadedFileInterface ? $imagen : null;
    $imageUpload = guardar_imagen_publicacion($imagen);
    if ($imageUpload['errors'] !== []) {
        return view($renderer, $response, '/publicaciones/create.php', [
            'title' => 'Crear publicación | Emprendi',
            'stylesheet' => '/styles.css',
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'errors' => $imageUpload['errors'],
            'data' => $validation['data'],
        ])->withStatus(400);
    }

    $imagePath = $imageUpload['path'];
    try {
        crear_publicacion((int) $_SESSION['user_id'], $validation['data'], $imagePath);
    } catch (PDOException $exception) {
        error_log('No se pudo crear la publicación: ' . $exception->getMessage());
        if ($imagePath !== null) {
            try {
                eliminar_imagen_publicacion($imagePath);
            } catch (RuntimeException $cleanupException) {
                error_log('No se pudo limpiar la imagen de una publicación fallida: ' . $cleanupException->getMessage());
            }
        }

        return view($renderer, $response, '/publicaciones/create.php', [
            'title' => 'Crear publicación | Emprendi',
            'stylesheet' => '/styles.css',
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'errors' => ['No se pudo guardar la publicación. Intentá nuevamente.'],
            'data' => $validation['data'],
        ])->withStatus(500);
    } catch (RuntimeException $exception) {
        error_log('No se pudo guardar la imagen de la publicación: ' . $exception->getMessage());

        return view($renderer, $response, '/publicaciones/create.php', [
            'title' => 'Crear publicación | Emprendi',
            'stylesheet' => '/styles.css',
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'errors' => ['No se pudo guardar la imagen. Intentá nuevamente.'],
            'data' => $validation['data'],
        ])->withStatus(500);
    }

    return redirectTo('/cuenta?publicacion=creada', 303);
}

function show_publicacion_catalogo(Request $request, Response $response, array $args): Response
{
    $id = filter_var($args['id'] ?? null, FILTER_VALIDATE_INT);
    $producto = $id === false || $id === null || $id < 1
        ? null
        : obtener_publicacion_catalogo_por_id($id);

    if ($producto === null) {
        $response->getBody()->write('No se encontró la publicación solicitada.');

        return $response->withStatus(404);
    }

    $query = $request->getQueryParams();

    return render_producto_catalogo(
        $response,
        $producto,
        [],
        [],
        ($query['comentario'] ?? '') === 'publicado',
        true,
        '/publicaciones/' . $id . '/comentarios',
        true,
    );
}

function handle_comentario_publicacion(Request $request, Response $response, array $args): Response
{
    $id = filter_var($args['id'] ?? null, FILTER_VALIDATE_INT);
    $producto = $id === false || $id === null || $id < 1
        ? null
        : obtener_publicacion_catalogo_por_id($id);

    if ($producto === null || $id === false || $id === null) {
        $response->getBody()->write('No se encontró la publicación solicitada.');

        return $response->withStatus(404);
    }

    return handle_comentario_para_producto(
        $request,
        $response,
        $producto,
        $id,
        '/publicaciones/' . $id,
        true,
    );
}
