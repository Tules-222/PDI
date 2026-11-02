<?php

declare(strict_types=1);

require_once __DIR__ . '/../services/auth.service.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

function show_register_form(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();
    if (!empty($_SESSION['user_id'])) {
        return redirectTo('/');
    }

    return view($renderer, $response, '/auth/register.php', [
        'errors' => [],
        'data' => [],
    ]);
}

function handle_register(Request $request, Response $response): Response
{
    global $renderer;

    $body = $request->getParsedBody() ?? [];
    $data = [
        'nombre' => trim((string) ($body['nombre'] ?? '')),
        'email' => trim((string) ($body['email'] ?? '')),
        'password' => (string) ($body['password'] ?? ''),
        'tel' => (string) ($body['tel'] ?? ''),
    ];

    try {
        $result = register_user($data);

        if ($result['errors'] !== []) {
            return view($renderer, $response, '/auth/register.php', [
                'errors' => $result['errors'],
                'data' => [
                    'nombre' => $data['nombre'],
                    'email' => $data['email'],
                    'tel' => $data['tel'],
                ],
            ]);
        }

        return redirectTo('/auth/login');
    } catch (\Throwable $e) {
        return view($renderer, $response, '/auth/register.php', [
            'errors' => ['No se pudo completar el registro. Intenta nuevamente.'],
            'data' => [
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'tel' => $data['tel'],
            ],
        ]);
    }
}

function show_login_form(Request $request, Response $response): Response
{
    global $renderer;

    ensureSession();
    if (!empty($_SESSION['user_id'])) {
        return redirectTo('/');
    }

    return view($renderer, $response, '/auth/login.php', [
        'errors' => [],
        'data' => [],
    ]);
}

function handle_login(Request $request, Response $response): Response
{
    global $renderer;

    $body = $request->getParsedBody() ?? [];
    $data = [
        'email' => trim((string) ($body['email'] ?? '')),
        'password' => (string) ($body['password'] ?? ''),
    ];

    $result = login_user($data);

    if ($result['errors'] !== []) {
        return view($renderer, $response, '/auth/login.php', [
            'errors' => $result['errors'],
            'data' => [
                'email' => $data['email'],
            ],
        ]);
    }

    ensureSession();
    session_regenerate_id(true);

    $_SESSION['user_id'] = $result['user']['id'];
    $_SESSION['user_nombre'] = $result['user']['nombre'];

    $redirectPath = $_SESSION['redirect_after_login'] ?? '/';
    unset($_SESSION['redirect_after_login']);

    return redirectTo($redirectPath === '/carrito' ? '/carrito' : '/');
}

function handle_logout(Request $request, Response $response): Response
{
    ensureSession();

    $body = $request->getParsedBody();
    $csrfToken = is_array($body) && is_string($body['csrf_token'] ?? null)
        ? $body['csrf_token']
        : '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $csrfToken)) {
        $response->getBody()->write('El formulario venció. Actualizá la página e intentá nuevamente.');

        return $response->withStatus(403);
    }

    $_SESSION = [];
    if (!session_destroy()) {
        throw new RuntimeException('No se pudo cerrar la sesión.');
    }

    $cookieParams = session_get_cookie_params();
    if (!setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => $cookieParams['secure'],
        'httponly' => $cookieParams['httponly'],
        'samesite' => $cookieParams['samesite'] ?? 'Lax',
    ])) {
        throw new RuntimeException('No se pudo eliminar la cookie de sesión.');
    }

    return redirectTo('/');
}

function redirect_to_auth_register(Request $request, Response $response): Response
{
    return redirectTo('/auth/register');
}
