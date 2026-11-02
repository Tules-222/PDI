<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once __DIR__ . '/../controllers/catalogo.controller.php';

$app->get('/productos/{id:[0-9]+}', 'show_producto_catalogo');
$app->post('/productos/{id:[0-9]+}/comentarios', 'handle_comentario_producto')->add(function (
    Request $request,
    RequestHandler $handler
): Response {
    return authMiddleware($request, $handler);
});
