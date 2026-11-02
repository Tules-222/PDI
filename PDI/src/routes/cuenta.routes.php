<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once __DIR__ . '/../controllers/cuenta.controller.php';

$requireAuth = static function (Request $request, RequestHandler $handler): Response {
    return authMiddleware($request, $handler);
};

$app->get('/cuenta', 'show_cuenta')->add($requireAuth);
$app->get('/publicaciones/nueva', 'show_publicacion_form')->add($requireAuth);
$app->post('/publicaciones', 'handle_crear_publicacion')->add($requireAuth);
$app->post('/publicaciones/{id:[0-9]+}/eliminar', 'handle_eliminar_publicacion')->add($requireAuth);
$app->post('/publicaciones/{id:[0-9]+}/comentarios', 'handle_comentario_publicacion')->add($requireAuth);
$app->get('/publicaciones/{id:[0-9]+}', 'show_publicacion_catalogo');
