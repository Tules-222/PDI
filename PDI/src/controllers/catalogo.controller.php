<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/catalogo.service.php';
require_once __DIR__ . '/../services/comentarios.service.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

function show_producto_catalogo(Request $request, Response $response, array $args): Response
{
    global $renderer;

    ensureSession();

    $id = filter_var($args['id'] ?? null, FILTER_VALIDATE_INT);
    $producto = $id === false || $id === null || $id < 1
        ? null
        : obtener_producto_catalogo_por_id($id);

    if ($producto === null) {
        $response->getBody()->write('No se encontró el producto solicitado.');

        return $response->withStatus(404);
    }

    $query = $request->getQueryParams();

    return render_producto_catalogo(
        $response,
        $producto,
        [],
        [],
        ($query['comentario'] ?? '') === 'publicado',
    );
}

function handle_comentario_producto(Request $request, Response $response, array $args): Response
{
    $id = filter_var($args['id'] ?? null, FILTER_VALIDATE_INT);
    $producto = $id === false || $id === null || $id < 1
        ? null
        : obtener_producto_catalogo_por_id($id);

    if ($producto === null) {
        $response->getBody()->write('No se encontró el producto solicitado.');

        return $response->withStatus(404);
    }

    return handle_comentario_para_producto(
        $request,
        $response,
        $producto,
        $id,
        '/productos/' . $id,
        false,
    );
}

function handle_comentario_para_producto(
    Request $request,
    Response $response,
    array $producto,
    int $id,
    string $productPath,
    bool $esPublicacion,
): Response {
    ensureSession();
    $body = $request->getParsedBody();
    $body = is_array($body) ? $body : [];
    $csrfToken = is_string($body['csrf_token'] ?? null) ? $body['csrf_token'] : '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    if (!is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $csrfToken)) {
        return render_producto_catalogo($response, $producto, [
            'La sesión del formulario venció. Actualizá la página e intentá nuevamente.',
        ], [], false, true, $productPath . '/comentarios', $esPublicacion)->withStatus(403);
    }

    $comentarioInput = $body['comentario'] ?? '';
    $comentario = is_string($comentarioInput) ? trim($comentarioInput) : '';
    $calificacion = filter_var($body['calificacion'] ?? null, FILTER_VALIDATE_INT);
    $errors = [];
    $longitud = preg_match_all('/./us', $comentario);

    if (!is_string($comentarioInput)) {
        $errors[] = 'El comentario no tiene un formato válido.';
    }

    if ($longitud === false) {
        $errors[] = 'El comentario contiene caracteres no válidos.';
    } elseif ($longitud < 1 || $longitud > 1000) {
        $errors[] = 'El comentario debe tener entre 1 y 1000 caracteres.';
    }

    if ($calificacion === false || $calificacion < 1 || $calificacion > 5) {
        $errors[] = 'Elegí una calificación entre 1 y 5 estrellas.';
    }

    if ($errors !== []) {
        return render_producto_catalogo($response, $producto, $errors, [
            'comentario' => $comentario,
            'calificacion' => is_scalar($body['calificacion'] ?? null) ? (string) $body['calificacion'] : '',
        ], false, true, $productPath . '/comentarios', $esPublicacion)->withStatus(400);
    }

    $usuarioId = filter_var($_SESSION['user_id'] ?? null, FILTER_VALIDATE_INT);
    if ($usuarioId === false || $usuarioId === null || $usuarioId < 1) {
        return redirectTo('/auth/login');
    }

    try {
        crear_comentario_producto($id, $usuarioId, $calificacion, $comentario, $esPublicacion);
    } catch (PDOException $exception) {
        error_log('No se pudo guardar el comentario del producto: ' . $exception->getMessage());

        return render_producto_catalogo($response, $producto, [
            'No se pudo publicar el comentario. Intentá nuevamente.',
        ], [
            'comentario' => $comentario,
            'calificacion' => $calificacion,
        ], false, true, $productPath . '/comentarios', $esPublicacion)->withStatus(500);
    }

    return redirectTo($productPath . '?comentario=publicado#comentarios', 303);
}

function render_producto_catalogo(
    Response $response,
    array $producto,
    array $commentErrors = [],
    array $commentData = [],
    bool $commentPublished = false,
    bool $commentsEnabled = true,
    ?string $commentActionPath = null,
    bool $esPublicacion = false,
): Response {
    global $renderer;
    ensureSession();
    $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));

    return view($renderer, $response, '/productos/show.php', [
        'producto' => $producto,
        'cantidadCarrito' => cantidad_total_carrito(),
        'comentarios' => $commentsEnabled
            ? listar_comentarios_producto((int) $producto['id'], $esPublicacion)
            : [],
        'comentariosHabilitados' => $commentsEnabled,
        'commentActionPath' => $commentActionPath ?? '/productos/' . rawurlencode((string) $producto['id']) . '/comentarios',
        'esPublicacion' => $esPublicacion,
        'productosRecomendados' => obtener_productos_recomendados_para_producto($producto),
        'erroresComentario' => $commentErrors,
        'datosComentario' => $commentData,
        'comentarioPublicado' => $commentPublished,
        'usuarioAutenticado' => !empty($_SESSION['user_id']),
        'csrfToken' => $_SESSION['csrf_token'],
        'title' => $producto['nombre'] . ' | Emprendi',
        'stylesheet' => '/styles.css',
    ]);
}
