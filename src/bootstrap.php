<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/database/database.php';
require_once __DIR__ . '/utils/html.php';
require_once __DIR__ . '/utils/view.php';

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
$app->addBodyParsingMiddleware();

// Crear el motor de plantillas
$renderer = new PhpRenderer(
  templatePath: __DIR__ . "/views",
  attributes: ["title" => "PDI | Slim Template 2026"],
);

// Ruta/Vista principal
$app->get('/', function ($request, $response) use ($renderer) {
  $queryParams = $request->getQueryParams();
  $search = trim((string) ($queryParams['q'] ?? ''));
  $productos = [];
  $error = null;

  try {
    $pdo = (new Database())->getConnection();

    if ($search !== '') {
      $stmt = $pdo->prepare(
        'SELECT id, nombre, precio, descripcion FROM productos WHERE nombre LIKE :search OR descripcion LIKE :search ORDER BY id DESC'
      );
      $stmt->execute(['search' => '%' . $search . '%']);
      $productos = $stmt->fetchAll();
    } else {
      $productos = $pdo->query(
        'SELECT id, nombre, precio, descripcion FROM productos ORDER BY id DESC'
      )->fetchAll();
    }
  } catch (PDOException $exception) {
    $error = 'No se pudieron cargar los productos.';
  }

  return view($renderer, $response, 'index.php', [
    'productos' => $productos,
    'search' => $search,
    'error' => $error,
    'stylesheet' => '/index.css',
  ]);
});

// Rutas de 'Productos'
$app->get('/productos', function ($request, $response) use ($renderer) {
  try {
    $pdo = (new Database())->getConnection();
    $productos = $pdo->query(
      'SELECT id, nombre, precio, descripcion, creado_en FROM productos ORDER BY id DESC'
    )->fetchAll();

    return view($renderer, $response, '/productos/index.php', [
      'productos' => $productos,
    ]);
  } catch (PDOException $exception) {
    return view($renderer->render($response->withStatus(500), '/productos/index.php'), '/productos/index.php', [
      'error' => 'No se pudieron cargar los productos.',
      'productos' => [],
    ]);
  }
});

$app->get('/productos/create', function ($request, $response) use ($renderer) {
  return view($renderer, $response, '/productos/create/create.php');
});

$app->get('/productos/{id}', function ($request, $response, array $args) use ($renderer) {
  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare(
      'SELECT id, nombre, precio, descripcion FROM productos WHERE id = :id'
    );
    $stmt->execute(['id' => $args['id']]);
    $producto = $stmt->fetch();

    if ($producto === false) {
      return view($renderer, $response->withStatus(404), '/productos/show.php', [
        'producto' => null,
      ]);
    }

    return view($renderer, $response, '/productos/show.php', [
      'producto' => $producto,
    ]);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/productos/show.php', [
      'producto' => null,
      'error' => 'No se pudo cargar el producto.',
    ]);
  }
}); 

$showUserRegistration = function ($request, $response) use ($renderer) {
  return view($renderer, $response, '/usuario/register.php');
};

$app->get('/usuarios/register', $showUserRegistration);
$app->get('/usuarios/create', $showUserRegistration);

$app->get('/admin/usuarios', function ($request, $response) use ($renderer) {
  $adminUser = $_ENV['ADMIN_USER'] ?? '';
  $adminPassword = $_ENV['ADMIN_PASS'] ?? '';
  $serverParams = $request->getServerParams();
  $providedUser = (string) ($serverParams['PHP_AUTH_USER'] ?? '');
  $providedPassword = (string) ($serverParams['PHP_AUTH_PW'] ?? '');

  if (
    $adminUser === '' ||
    $adminPassword === '' ||
    !hash_equals($adminUser, $providedUser) ||
    !hash_equals($adminPassword, $providedPassword)
  ) {
    return $response
      ->withHeader('WWW-Authenticate', 'Basic realm="Administración", charset="UTF-8"')
      ->withStatus(401);
  }

  try {
    $pdo = (new Database())->getConnection();
    $usuarios = $pdo->query(
      'SELECT id, nombre, rol, gmail, telefono, direccion FROM usuarios ORDER BY id DESC'
    )->fetchAll();

    return view($renderer, $response, '/admin/usuarios.php', [
      'usuarios' => $usuarios,
    ]);
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }
});

$app->post('/usuarios', function ($request, $response) use ($renderer) {
  $data = $request->getParsedBody() ?? [];
  $usuario = [
    'nombre' => trim((string) ($data['nombre'] ?? '')),
    'rol' => trim((string) ($data['rol'] ?? '')),
    'gmail' => trim((string) ($data['gmail'] ?? '')),
    'telefono' => trim((string) ($data['telefono'] ?? '')),
    'direccion' => trim((string) ($data['direccion'] ?? '')),
  ];

  if (in_array('', $usuario, true) || !filter_var($usuario['gmail'], FILTER_VALIDATE_EMAIL)) {
    return view($renderer, $response->withStatus(422), '/usuario/register.php', [
      'error' => 'Completá todos los campos con datos válidos.',
    ]);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare(
      'INSERT INTO usuarios (nombre, rol, gmail, telefono, direccion) VALUES (:nombre, :rol, :gmail, :telefono, :direccion)'
    );
    $stmt->execute($usuario);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/usuario/register.php', [
      'error' => 'No se pudo crear el usuario. Verificá que el correo no esté registrado e intentá nuevamente.',
    ]);
  }

  return view($renderer, $response, '/usuario/register.php', [
    'success' => 'Usuario creado correctamente.',
  ]);
});


$app->post('/productos', function ($request, $response) use ($renderer) {
  $data = $request->getParsedBody() ?? [];
  $nombre = trim((string) ($data['nombre'] ?? ''));
  $precio = trim((string) ($data['precio'] ?? ''));
  $descripcion = trim((string) ($data['descripcion'] ?? ''));

  if (
    $nombre === '' ||
    strlen($nombre) > 255 ||
    !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $precio) ||
    $descripcion === '' ||
    strlen($descripcion) > 1000
  ) {
    return view($renderer, $response->withStatus(422), '/productos/create/create.php', [
      'error' => 'Completá todos los campos con datos válidos.',
    ]);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare(
      'INSERT INTO productos (nombre, precio, descripcion) VALUES (:nombre, :precio, :descripcion)'
    );
    $stmt->execute([
      'nombre' => $nombre,
      'precio' => $precio,
      'descripcion' => $descripcion,
    ]);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/productos/create/create.php', [
      'error' => 'No se pudo guardar el producto.',
    ]);
  }

  return $response->withHeader('Location', '/productos')->withStatus(303);
});

$app->put('/productos/{id}', function ($request, $response, array $args) use ($renderer) {
  $db = new Database();
  $pdo = $db->getConnection();

  $data = $request ->getParsedBody();
  $id = $args['id'];
  
  $stmt = $pdo -> prepare("UPDATE productos SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
  $stmt ->execute([$data['nombre'], $data['precio'], $data['description'], $id]);

  return view($renderer, $response, '/productos/detalles.php' , [ 
    'nombre' => $data['nombre'],
    'precio' => $data['precio'],
    'descripcion' => $data['descripcion']
  ]);
});

$app->addErrorMiddleware($debug, true, true);

return $app;
