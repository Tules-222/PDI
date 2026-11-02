<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/carrito.service.php';
require_once __DIR__ . '/../services/catalogo.service.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

function handle_carrito_agregar(Request $request, Response $response): Response
{
    ensureSession();

    $wantsJson = str_contains($request->getHeaderLine('Accept'), 'application/json');
    $data = $request->getParsedBody();
    $productoId = is_array($data) && is_scalar($data['producto_id'] ?? null)
        ? (string) $data['producto_id']
        : '';
    $productos = obtener_productos_catalogo();

    foreach ($productos as $producto) {
        $cartId = (string) ($producto['cartId'] ?? $producto['id']);
        if ($cartId === $productoId) {
            agregar_producto_al_carrito($productoId);

            if ($wantsJson) {
                $response->getBody()->write(json_encode([
                    'cantidad' => cantidad_total_carrito(),
                ], JSON_THROW_ON_ERROR));

                return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
            }

            return $response
                ->withHeader('Location', '/carrito')
                ->withStatus(303);
        }
    }

    if ($wantsJson) {
        $response->getBody()->write(json_encode([
            'error' => 'El producto solicitado no existe.',
        ], JSON_THROW_ON_ERROR));

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(404);
    }

    $response->getBody()->write('El producto solicitado no existe.');

    return $response->withStatus(404);
}

function show_carrito(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();

    $productos = obtener_productos_catalogo();
    $items = obtener_items_del_carrito($productos);

    return view($renderer, $response, '/carrito/cesta.php', [
        'items' => $items,
        'total' => total_carrito($items),
        'cantidadCarrito' => cantidad_total_carrito(),
        'usuarioAutenticado' => !empty($_SESSION['user_id']),
        'title' => 'Carrito | Emprendi',
        'stylesheet' => '/carrito.css',
    ]);
}

function handle_carrito_cantidad(Request $request, Response $response, array $args): Response
{
    ensureSession();

    $productoId = is_string($args['id'] ?? null) ? $args['id'] : '';
    $data = $request->getParsedBody();
    $accion = is_array($data) ? ($data['accion'] ?? null) : null;

    if (!preg_match('/^(?:[1-9][0-9]*|publicacion-[1-9][0-9]*)$/D', $productoId)
        || !in_array($accion, ['sumar', 'restar'], true)) {
        $response->getBody()->write('No se pudo actualizar la cantidad del producto.');

        return $response->withStatus(400);
    }

    $cambio = $accion === 'sumar' ? 1 : -1;
    if (!ajustar_cantidad_producto_carrito($productoId, $cambio)) {
        $response->getBody()->write('El producto no está en el carrito o ya alcanzó la cantidad mínima.');

        return $response->withStatus(400);
    }

    return $response
        ->withHeader('Location', '/carrito')
        ->withStatus(303);
}

function show_carrito_pago(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();

    $items = obtener_items_del_carrito(obtener_productos_catalogo());
    if ($items === []) {
        return redirectTo('/carrito');
    }

    return view($renderer, $response, '/carrito/pago.php', [
        'errors' => [],
        'data' => [],
        'items' => $items,
        'total' => total_carrito($items),
        'cantidadCarrito' => cantidad_total_carrito(),
        'usuarioAutenticado' => !empty($_SESSION['user_id']),
        'title' => 'Finalizar compra | Emprendi',
    ]);
}

function handle_carrito_pago(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();

    $productos = obtener_productos_catalogo();
    $items = obtener_items_del_carrito($productos);
    if ($items === []) {
        return redirectTo('/carrito');
    }

    $body = $request->getParsedBody();
    $body = is_array($body) ? $body : [];
    $data = [
        'nombre' => trim((string) ($body['nombre'] ?? '')),
        'telefono' => trim((string) ($body['telefono'] ?? '')),
        'calle' => trim((string) ($body['calle'] ?? '')),
        'localidad' => trim((string) ($body['localidad'] ?? '')),
        'provincia' => trim((string) ($body['provincia'] ?? '')),
        'codigo_postal' => trim((string) ($body['codigo_postal'] ?? '')),
        'metodo_pago' => trim((string) ($body['metodo_pago'] ?? '')),
    ];
    $errors = [];

    foreach ([
        'nombre' => 'Ingresá tu nombre y apellido.',
        'telefono' => 'Ingresá un teléfono de contacto.',
        'calle' => 'Ingresá la dirección de entrega.',
        'localidad' => 'Ingresá la localidad.',
        'provincia' => 'Ingresá la provincia.',
        'codigo_postal' => 'Ingresá el código postal.',
    ] as $field => $message) {
        if ($data[$field] === '') {
            $errors[] = $message;
        }
    }

    if (!in_array($data['metodo_pago'], ['tarjeta', 'mercadopago'], true)) {
        $errors[] = 'Elegí un método de pago.';
    }

    if ($errors !== []) {
        return view($renderer, $response, '/carrito/pago.php', [
            'errors' => $errors,
            'data' => $data,
            'items' => $items,
            'total' => total_carrito($items),
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => !empty($_SESSION['user_id']),
            'title' => 'Finalizar compra | Emprendi',
        ])->withStatus(400);
    }

    try {
        $pedido = crear_pedido_desde_carrito(
            $items,
            $data,
            isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null,
        );
    } catch (PDOException $exception) {
        error_log('No se pudo guardar el pedido: ' . $exception->getMessage());

        return view($renderer, $response, '/carrito/pago.php', [
            'errors' => ['No se pudo registrar el pedido. Intentá nuevamente.'],
            'data' => $data,
            'items' => $items,
            'total' => total_carrito($items),
            'cantidadCarrito' => cantidad_total_carrito(),
            'usuarioAutenticado' => !empty($_SESSION['user_id']),
            'title' => 'Finalizar compra | Emprendi',
        ])->withStatus(500);
    }

    vaciar_carrito();

    return redirectTo('/carrito/confirmacion', 303);
}

function show_carrito_confirmacion(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();
    $pedido = obtener_ultimo_pedido();
    if ($pedido === null) {
        return redirectTo('/carrito');
    }

    return view($renderer, $response, '/carrito/confirmacion.php', [
        'pedido' => $pedido,
        'cantidadCarrito' => cantidad_total_carrito(),
        'usuarioAutenticado' => !empty($_SESSION['user_id']),
        'title' => 'Pedido recibido | Emprendi',
    ]);
}
