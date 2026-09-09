<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde el .env
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$env = $_ENV["APP_ENV"] ?? "prod";
$allowedEnvs = ["dev", "prod"];

if (!in_array($env, $allowedEnvs, true)) {
  throw new RuntimeException("APP_ENV inválido: $env");
}

$debug = $env === "dev";

// Crear la aplicacion de Slim
$app = AppFactory::create();

// Crear el motor de plantillas
$renderer = new PhpRenderer(
  templatePath: __DIR__ . "/views",
  attributes: ["title" => "PDI | Slim Template 2026"],
);

// Ruta/Vista principal
$app->get('/', function ($request, $response) use ($renderer) {
  return view($renderer, $response, 'index.php');
});

// Rutas de 'Productos'
$app->get('/productos', function ($request, $response) use ($renderer) {
  return view($renderer, $response, '/productos/index.php');
});

$app->get('/productos/{id}', function ($request, $response, array $args) use ($renderer) {
  return view($renderer, $response, '/productos/show.php', [
    'id' => $args['id']
  ]);
}); 


$app->get('/create/productos', function ($request, $response) use ($renderer) {
  return view($renderer, $response, '/productos/store.php' , [
  ]);
});
$app->post('/productos', function ($request, $response) use ($renderer) {
  $data = $request->getParsedBody();

  return view($renderer, $response, '/productos/detalles.php', [
    'nombre' => $data['nombre'],
    'precio' => $data['precio'],
    'descripcion' => $data['descripcion']
  ]);
});

$app->addErrorMiddleware($debug, true, true);

return $app;
