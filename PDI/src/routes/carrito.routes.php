<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/** @var App $app */

require_once __DIR__ . '/../controllers/carrito.controller.php';

$requireAuth = static function (Request $request, RequestHandler $handler): Response {
    return authMiddleware($request, $handler);
};

$app->post('/carrito/agregar', 'handle_carrito_agregar');
$app->get('/carrito', 'show_carrito')->add($requireAuth);
$app->post('/carrito/{id}/cantidad', 'handle_carrito_cantidad')->add($requireAuth);
$app->get('/carrito/pago', 'show_carrito_pago')->add($requireAuth);
$app->post('/carrito/pago', 'handle_carrito_pago')->add($requireAuth);
$app->get('/carrito/confirmacion', 'show_carrito_confirmacion')->add($requireAuth);
