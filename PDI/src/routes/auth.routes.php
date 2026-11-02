<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/** @var App $app */

require_once __DIR__ . '/../controllers/auth.controller.php';

$app->get('/auth/register', 'show_register_form');
$app->post('/auth/register', 'handle_register');

$app->get('/auth/login', 'show_login_form');
$app->post('/auth/login', 'handle_login');
$app->post('/auth/logout', 'handle_logout')->add(static function (
    Request $request,
    RequestHandler $handler
): Response {
    return authMiddleware($request, $handler);
});

$app->get('/formulario/register', 'redirect_to_auth_register');
$app->post('/formulario/register', 'redirect_to_auth_register');