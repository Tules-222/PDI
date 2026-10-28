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

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
  ]);
}

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

$app->get('/assets/login.css', function ($request, $response) {
  $css = file_get_contents(__DIR__ . '/views/usuario/login.css');
  $response->getBody()->write($css === false ? '' : $css);
  return $response->withHeader('Content-Type', 'text/css; charset=UTF-8');
});

$app->get('/assets/register.css', function ($request, $response) {
  $css = file_get_contents(__DIR__ . '/views/usuario/register.css');
  $response->getBody()->write($css === false ? '' : $css);
  return $response->withHeader('Content-Type', 'text/css; charset=UTF-8');
});

// Ruta/Vista principal

$app->get('/', function ($request, $response) use ($renderer) {
  $queryParams = $request->getQueryParams();
  $search = trim((string) ($queryParams['q'] ?? ''));
  $productos = [];
  $error = null;
  $totalPublicados = 0;

  try {
    $pdo = (new Database())->getConnection();
    $totalPublicados = (int) $pdo->query("SELECT COUNT(*) FROM productos WHERE estado = 'aprobado'")->fetchColumn();

    if ($search !== '') {
      $stmt = $pdo->prepare(
        'SELECT id, nombre, precio, descripcion, usuario_id FROM productos WHERE estado = \'aprobado\' AND (nombre LIKE :name_search OR descripcion LIKE :description_search) ORDER BY id DESC'
      );
      $searchPattern = '%' . $search . '%';
      $stmt->execute([
        'name_search' => $searchPattern,
        'description_search' => $searchPattern,
      ]);
      $productos = $stmt->fetchAll();
      if ($productos !== []) {
        $increment = $pdo->prepare('UPDATE productos SET busquedas = busquedas + 1 WHERE id = :id');
        foreach ($productos as $producto) {
          $increment->execute(['id' => $producto['id']]);
        }
      }
    } else {
      if ($totalPublicados <= 12) {
        $productos = $pdo->query(
          'SELECT id, nombre, precio, descripcion, usuario_id FROM productos WHERE estado = \'aprobado\' ORDER BY id DESC'
        )->fetchAll();
      } else {
        $productos = $pdo->query(
          "SELECT p.id, p.nombre, p.precio, p.descripcion, p.usuario_id
           FROM productos p
           LEFT JOIN (
             SELECT i.producto_id, COUNT(*) AS compras
             FROM pedido_items i JOIN pedidos pe ON pe.id = i.pedido_id
             WHERE pe.estado = 'aceptado' AND i.producto_id IS NOT NULL
             GROUP BY i.producto_id
           ) ventas ON ventas.producto_id = p.id
           WHERE p.estado = 'aprobado'
           ORDER BY (p.busquedas + COALESCE(ventas.compras, 0) * 3) DESC, p.id DESC
           LIMIT 12"
        )->fetchAll();
      }
    }
  } catch (PDOException $exception) {
    $error = 'No se pudieron cargar los productos.';
  }

  return view($renderer, $response, 'index.php', [
    'productos' => $productos,
    'search' => $search,
    'error' => $error,
    'usuarioActual' => $_SESSION['usuario'] ?? null,
    'stylesheet' => '/index.css',
    'totalPublicados' => $totalPublicados,
    'cantidadResultados' => count($productos),
  ]);
});

// Rutas de 'Productos'
$app->get('/productos', function ($request, $response) use ($renderer) {
  try {
    $pdo = (new Database())->getConnection();
    $productos = $pdo->query(
      'SELECT id, nombre, precio, descripcion, creado_en FROM productos WHERE estado = \'aprobado\' ORDER BY id DESC'
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
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login?next=%2Fproductos%2Fcreate')->withStatus(303);
  }

  return view($renderer, $response, '/productos/create/create.php', [
    'formAction' => '/productos',
    'producto' => null,
  ]);
});

$app->get('/mis-productos', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login?next=%2Fmis-productos')->withStatus(303);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT id, nombre, precio, descripcion, creado_en, estado FROM productos WHERE usuario_id = :usuario_id ORDER BY id DESC');
    $stmt->execute(['usuario_id' => $_SESSION['usuario']['id']]);
    return view($renderer, $response, '/productos/index.php', [
      'productos' => $stmt->fetchAll(),
      'misProductos' => true,
    ]);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/productos/index.php', [
      'error' => 'No se pudieron cargar tus productos.',
      'productos' => [],
      'misProductos' => true,
    ]);
  }
});

$app->get('/mis-compras', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login?next=%2Fmis-compras')->withStatus(303);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT p.id, p.estado, p.total, p.creado_en, i.nombre_producto, i.precio_unitario, i.cantidad, i.nombre_vendedor, i.email_vendedor_snapshot FROM pedidos p JOIN pedido_items i ON i.pedido_id = p.id WHERE p.comprador_id_snapshot = :usuario_id ORDER BY p.creado_en DESC');
    $stmt->execute(['usuario_id' => $_SESSION['usuario']['id']]);
    return view($renderer, $response, '/usuario/pedidos.php', [
      'pedidos' => $stmt->fetchAll(),
      'esVenta' => false,
    ]);
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }
});

$app->get('/mis-ventas', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login?next=%2Fmis-ventas')->withStatus(303);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT p.id, p.estado, p.total, p.creado_en, i.nombre_producto, i.precio_unitario, i.cantidad, p.comprador_nombre_snapshot AS comprador_nombre, p.comprador_email_snapshot, i.nombre_vendedor, i.email_vendedor_snapshot FROM pedidos p JOIN pedido_items i ON i.pedido_id = p.id WHERE i.propietario_usuario_id_snapshot = :usuario_id ORDER BY p.creado_en DESC');
    $stmt->execute(['usuario_id' => $_SESSION['usuario']['id']]);
    return view($renderer, $response, '/usuario/pedidos.php', [
      'pedidos' => $stmt->fetchAll(),
      'esVenta' => true,
    ]);
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }
});

$app->get('/admin/pedidos', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    return $response->withStatus(403);
  }

  try {
    $pdo = (new Database())->getConnection();
    $pedidos = $pdo->query('SELECT p.id, p.estado, p.total, p.creado_en, p.comprador_id_snapshot, p.comprador_nombre_snapshot, p.comprador_email_snapshot, i.producto_id_snapshot, i.nombre_producto, i.precio_unitario, i.cantidad, i.propietario_usuario_id_snapshot, i.nombre_vendedor, i.email_vendedor_snapshot FROM pedidos p JOIN pedido_items i ON i.pedido_id = p.id ORDER BY p.creado_en DESC')->fetchAll();
    return view($renderer, $response, '/admin/pedidos.php', ['pedidos' => $pedidos]);
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }
});

$app->post('/ventas/{id}/estado', function ($request, $response, array $args) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withStatus(401);
  }

  $data = $request->getParsedBody() ?? [];
  $estado = (string) ($data['estado'] ?? '');
  if (!in_array($estado, ['aceptado', 'rechazado'], true)) {
    return $response->withStatus(422);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('UPDATE pedidos SET estado = :estado WHERE id = :id AND estado = \'pendiente\' AND EXISTS (SELECT 1 FROM pedido_items WHERE pedido_items.pedido_id = pedidos.id AND pedido_items.vendedor_id = :vendedor_id)');
    $stmt->execute([
      'estado' => $estado,
      'id' => $args['id'],
      'vendedor_id' => $_SESSION['usuario']['id'],
    ]);
    if ($stmt->rowCount() === 0) {
      return $response->withStatus(404);
    }
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }

  return $response->withHeader('Location', '/mis-ventas')->withStatus(303);
});

$app->get('/admin/productos', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    return $response->withStatus(403);
  }

  try {
    $pdo = (new Database())->getConnection();
    $productos = $pdo->query('SELECT id, nombre, precio, descripcion, creado_en, estado FROM productos ORDER BY creado_en DESC')->fetchAll();
    return view($renderer, $response, '/admin/productos.php', ['productos' => $productos]);
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }
});

$app->post('/admin/productos/{id}/estado', function ($request, $response, array $args) {
  if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    return $response->withStatus(403);
  }

  $data = $request->getParsedBody() ?? [];
  $estado = (string) ($data['estado'] ?? '');
  if (!in_array($estado, ['aprobado', 'rechazado'], true)) {
    return $response->withStatus(422);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('UPDATE productos SET estado = :estado WHERE id = :id');
    $stmt->execute(['estado' => $estado, 'id' => $args['id']]);
    if ($stmt->rowCount() === 0) {
      $check = $pdo->prepare('SELECT 1 FROM productos WHERE id = :id');
      $check->execute(['id' => $args['id']]);
      if (!$check->fetchColumn()) {
        return $response->withStatus(404);
      }
    }
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }

  return $response->withHeader('Location', '/admin/productos')->withStatus(303);
});

$app->get('/productos/{id}/editar', function ($request, $response, array $args) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    $next = '/productos/' . $args['id'] . '/editar';
    return $response->withHeader('Location', '/usuarios/login?next=' . rawurlencode($next))->withStatus(303);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT id, nombre, precio, descripcion, usuario_id FROM productos WHERE id = :id');
    $stmt->execute(['id' => $args['id']]);
    $producto = $stmt->fetch();
    if ($producto === false) {
      return $response->withStatus(404);
    }
    if ((int) $producto['usuario_id'] !== (int) $_SESSION['usuario']['id']) {
      return $response->withStatus(403);
    }

    return view($renderer, $response, '/productos/create/create.php', [
      'formAction' => '/productos/' . rawurlencode((string) $producto['id']) . '/editar',
      'producto' => $producto,
    ]);
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }
});

$app->get('/productos/{id}', function ($request, $response, array $args) use ($renderer) {
  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT productos.id, productos.nombre, productos.precio, productos.descripcion, productos.usuario_id, productos.estado, usuarios.nombre AS vendedor_nombre FROM productos JOIN usuarios ON usuarios.id = productos.usuario_id WHERE productos.id = :id');
    $stmt->execute(['id' => $args['id']]);
    $producto = $stmt->fetch();

    if ($producto === false) {
      return view($renderer, $response->withStatus(404), '/productos/show.php', [
        'producto' => null,
      ]);
    }

    $usuarioActual = $_SESSION['usuario'] ?? null;
    $esPropietario = $usuarioActual !== null && (int) $producto['usuario_id'] === (int) $usuarioActual['id'];
    $esAdmin = $usuarioActual !== null && $usuarioActual['rol'] === 'admin';
    if ($producto['estado'] !== 'aprobado' && !$esPropietario && !$esAdmin) {
      return view($renderer, $response->withStatus(404), '/productos/show.php', ['producto' => null]);
    }

    return view($renderer, $response, '/productos/show.php', [
      'producto' => $producto,
      'usuarioActual' => $usuarioActual,
    ]);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/productos/show.php', [
      'producto' => null,
      'error' => 'No se pudo cargar el producto.',
    ]);
  }
}); 

$showUserRegistration = function ($request, $response) use ($renderer) {
  $query = $request->getQueryParams();
  $next = (string) ($query['next'] ?? '/');
  if (!str_starts_with($next, '/') || str_starts_with($next, '//')) {
    $next = '/';
  }
  return view($renderer, $response, '/usuario/register.php', [
    'next' => $next,
    'stylesheet' => '/assets/register.css',
  ]);
};

$app->get('/usuarios/register', $showUserRegistration);
$app->get('/usuarios/create', $showUserRegistration);

$app->get('/usuarios/login', function ($request, $response) use ($renderer) {
  $query = $request->getQueryParams();
  $next = (string) ($query['next'] ?? '/');
  if (!str_starts_with($next, '/') || str_starts_with($next, '//')) {
    $next = '/';
  }

  return view($renderer, $response, '/usuario/login.php', [
    'next' => $next,
    'stylesheet' => '/assets/login.css',
  ]);
});

$app->post('/usuarios/login', function ($request, $response) use ($renderer) {
  $data = $request->getParsedBody() ?? [];
  $email = trim((string) ($data['gmail'] ?? ''));
  $password = (string) ($data['password'] ?? '');
  $next = (string) ($data['next'] ?? '/');
  if (!str_starts_with($next, '/') || str_starts_with($next, '//')) {
    $next = '/';
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT id, nombre, rol, password_hash FROM usuarios WHERE gmail = :gmail');
    $stmt->execute(['gmail' => $email]);
    $usuario = $stmt->fetch();
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/usuario/login.php', [
      'error' => 'No se pudo iniciar sesión. Intentá nuevamente.',
      'next' => $next,
      'stylesheet' => '/assets/login.css',
    ]);
  }

  if ($usuario === false || empty($usuario['password_hash']) || !password_verify($password, $usuario['password_hash'])) {
    return view($renderer, $response->withStatus(401), '/usuario/login.php', [
      'error' => 'Correo o contraseña incorrectos.',
      'next' => $next,
      'stylesheet' => '/assets/login.css',
    ]);
  }

  session_regenerate_id(true);
  $_SESSION['usuario'] = [
    'id' => (int) $usuario['id'],
    'nombre' => (string) $usuario['nombre'],
    'rol' => (string) $usuario['rol'],
  ];

  return $response->withHeader('Location', $next)->withStatus(303);
});

$app->post('/usuarios/logout', function ($request, $response) {
  $_SESSION = [];
  session_regenerate_id(true);
  return $response->withHeader('Location', '/')->withStatus(303);
});

$app->get('/mi-cuenta', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login')->withStatus(303);
  }
  return view($renderer, $response, '/usuario/eliminar-cuenta.php');
});

$app->post('/mi-cuenta/eliminar', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login')->withStatus(303);
  }

  $data = $request->getParsedBody() ?? [];
  $password = (string) ($data['password'] ?? '');

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('SELECT password_hash FROM usuarios WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['usuario']['id']]);
    $passwordHash = $stmt->fetchColumn();
    if (!is_string($passwordHash) || !password_verify($password, $passwordHash)) {
      return view($renderer, $response->withStatus(401), '/usuario/eliminar-cuenta.php', [
        'error' => 'La contraseña no es correcta. La cuenta no se eliminó.',
      ]);
    }

    $delete = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
    $delete->execute(['id' => $_SESSION['usuario']['id']]);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/usuario/eliminar-cuenta.php', [
      'error' => 'No se pudo eliminar la cuenta.',
    ]);
  }

  $_SESSION = [];
  session_regenerate_id(true);
  return $response->withHeader('Location', '/')->withStatus(303);
});

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
  $password = (string) ($data['password'] ?? '');
  $next = (string) ($data['next'] ?? '/');
  if (!str_starts_with($next, '/') || str_starts_with($next, '//')) {
    $next = '/';
  }
  $usuario = [
    'nombre' => trim((string) ($data['nombre'] ?? '')),
    'gmail' => trim((string) ($data['gmail'] ?? '')),
    'telefono' => trim((string) ($data['telefono'] ?? '')),
    'direccion' => trim((string) ($data['direccion'] ?? '')),
  ];

  if (in_array('', $usuario, true) || !filter_var($usuario['gmail'], FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
    return view($renderer, $response->withStatus(422), '/usuario/register.php', [
      'error' => 'Completá todos los campos con datos válidos. La contraseña debe tener al menos 8 caracteres.',
      'next' => $next,
      'stylesheet' => '/assets/register.css',
    ]);
  }

  try {
    $pdo = (new Database())->getConnection();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $insertSql = 'INSERT INTO usuarios (nombre, rol, gmail, telefono, direccion, password_hash) VALUES (:nombre, :rol, :gmail, :telefono, :direccion, :password_hash)';
    if ($driver === 'pgsql') {
      $insertSql .= ' RETURNING id';
    }
    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([
      ...$usuario,
      'rol' => 'usuario',
      'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);
    $usuarioId = (int) ($driver === 'pgsql' ? $stmt->fetchColumn() : $pdo->lastInsertId());
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/usuario/register.php', [
      'error' => 'No se pudo crear el usuario. Verificá que el correo no esté registrado e intentá nuevamente.',
      'next' => $next,
      'stylesheet' => '/assets/register.css',
    ]);
  }

  session_regenerate_id(true);
  $_SESSION['usuario'] = ['id' => $usuarioId, 'nombre' => $usuario['nombre'], 'rol' => 'usuario'];
  return $response->withHeader('Location', $next)->withStatus(303);
});


$app->post('/productos', function ($request, $response) use ($renderer) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login?next=%2Fproductos%2Fcreate')->withStatus(303);
  }

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
      'INSERT INTO productos (nombre, precio, descripcion, usuario_id, estado) VALUES (:nombre, :precio, :descripcion, :usuario_id, \'aprobado\')'
    );
    $stmt->execute([
      'nombre' => $nombre,
      'precio' => $precio,
      'descripcion' => $descripcion,
      'usuario_id' => $_SESSION['usuario']['id'],
    ]);
  } catch (PDOException $exception) {
    return view($renderer, $response->withStatus(500), '/productos/create/create.php', [
      'error' => 'No se pudo guardar el producto.',
    ]);
  }

  return $response->withHeader('Location', '/mis-productos')->withStatus(303);
});

$app->post('/productos/{id}/comprar', function ($request, $response, array $args) {
  if (!isset($_SESSION['usuario'])) {
    $next = '/productos/' . $args['id'];
    return $response->withHeader('Location', '/usuarios/login?next=' . rawurlencode($next))->withStatus(303);
  }

  $data = $request->getParsedBody() ?? [];
  $cantidad = filter_var($data['cantidad'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 99],
  ]);
  if ($cantidad === false) {
    return $response->withStatus(422);
  }

  $pdo = (new Database())->getConnection();
  try {
    $pdo->beginTransaction();
    $driver = (string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $lock = $driver === 'pgsql' ? ' FOR UPDATE' : ' FOR UPDATE';
    $stmt = $pdo->prepare('SELECT p.id, p.nombre, p.precio, p.usuario_id, u.nombre AS vendedor_nombre FROM productos p JOIN usuarios u ON u.id = p.usuario_id WHERE p.id = :id AND p.estado = \'aprobado\'' . $lock);
    $stmt->execute(['id' => $args['id']]);
    $producto = $stmt->fetch();

    if ($producto === false || (int) $producto['usuario_id'] === (int) $_SESSION['usuario']['id']) {
      $pdo->rollBack();
      return $response->withStatus(404);
    }

    [$parteEntera, $parteDecimal] = array_pad(explode('.', (string) $producto['precio'], 2), 2, '');
    $totalCentavos = ((int) $parteEntera * 100 + (int) str_pad($parteDecimal, 2, '0')) * $cantidad;
    $total = intdiv($totalCentavos, 100) . '.' . str_pad((string) ($totalCentavos % 100), 2, '0', STR_PAD_LEFT);

    $buyerStmt = $pdo->prepare('SELECT nombre, gmail FROM usuarios WHERE id = :id');
    $buyerStmt->execute(['id' => $_SESSION['usuario']['id']]);
    $comprador = $buyerStmt->fetch();
    if ($comprador === false) {
      $pdo->rollBack();
      return $response->withStatus(401);
    }

    $pedidoSql = 'INSERT INTO pedidos (comprador_id, comprador_id_snapshot, comprador_nombre_snapshot, comprador_email_snapshot, estado, total) VALUES (:comprador_id, :comprador_id_snapshot, :comprador_nombre_snapshot, :comprador_email_snapshot, \'pendiente\', :total)';
    if ($driver === 'pgsql') {
      $pedidoSql .= ' RETURNING id';
    }
    $pedidoStmt = $pdo->prepare($pedidoSql);
    $pedidoStmt->execute([
      'comprador_id' => $_SESSION['usuario']['id'],
      'comprador_id_snapshot' => $_SESSION['usuario']['id'],
      'comprador_nombre_snapshot' => $comprador['nombre'],
      'comprador_email_snapshot' => $comprador['gmail'],
      'total' => $total,
    ]);
    $pedidoId = (int) ($driver === 'pgsql' ? $pedidoStmt->fetchColumn() : $pdo->lastInsertId());

    $sellerStmt = $pdo->prepare('SELECT gmail FROM usuarios WHERE id = :id');
    $sellerStmt->execute(['id' => $producto['usuario_id']]);
    $vendedorEmail = $sellerStmt->fetchColumn();
    $itemStmt = $pdo->prepare('INSERT INTO pedido_items (pedido_id, producto_id, vendedor_id, producto_id_snapshot, propietario_usuario_id_snapshot, email_vendedor_snapshot, nombre_vendedor, nombre_producto, precio_unitario, cantidad) VALUES (:pedido_id, :producto_id, :vendedor_id, :producto_id_snapshot, :propietario_usuario_id_snapshot, :email_vendedor_snapshot, :nombre_vendedor, :nombre_producto, :precio_unitario, :cantidad)');
    $itemStmt->execute([
      'pedido_id' => $pedidoId,
      'producto_id' => $producto['id'],
      'vendedor_id' => $producto['usuario_id'],
      'producto_id_snapshot' => $producto['id'],
      'propietario_usuario_id_snapshot' => $producto['usuario_id'],
      'email_vendedor_snapshot' => $vendedorEmail,
      'nombre_vendedor' => $producto['vendedor_nombre'],
      'nombre_producto' => $producto['nombre'],
      'precio_unitario' => $producto['precio'],
      'cantidad' => $cantidad,
    ]);
    $pdo->commit();
  } catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    return $response->withStatus(500);
  }

  return $response->withHeader('Location', '/mis-compras')->withStatus(303);
});

$app->post('/productos/{id}/editar', function ($request, $response, array $args) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login')->withStatus(303);
  }

  $data = $request->getParsedBody() ?? [];
  $nombre = trim((string) ($data['nombre'] ?? ''));
  $precio = trim((string) ($data['precio'] ?? ''));
  $descripcion = trim((string) ($data['descripcion'] ?? ''));
  if ($nombre === '' || strlen($nombre) > 255 || !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $precio) || $descripcion === '' || strlen($descripcion) > 1000) {
    return $response->withStatus(422);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion, estado = \'aprobado\' WHERE id = :id AND usuario_id = :usuario_id');
    $stmt->execute([
      'nombre' => $nombre,
      'precio' => $precio,
      'descripcion' => $descripcion,
      'id' => $args['id'],
      'usuario_id' => $_SESSION['usuario']['id'],
    ]);
    if ($stmt->rowCount() === 0) {
      $check = $pdo->prepare('SELECT 1 FROM productos WHERE id = :id AND usuario_id = :usuario_id');
      $check->execute(['id' => $args['id'], 'usuario_id' => $_SESSION['usuario']['id']]);
      if (!$check->fetchColumn()) {
        return $response->withStatus(404);
      }
    }
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }

  return $response->withHeader('Location', '/productos/' . rawurlencode((string) $args['id']))->withStatus(303);
});

$app->post('/productos/{id}/eliminar', function ($request, $response, array $args) {
  if (!isset($_SESSION['usuario'])) {
    return $response->withHeader('Location', '/usuarios/login')->withStatus(303);
  }

  try {
    $pdo = (new Database())->getConnection();
    $stmt = $pdo->prepare('DELETE FROM productos WHERE id = :id AND usuario_id = :usuario_id');
    $stmt->execute(['id' => $args['id'], 'usuario_id' => $_SESSION['usuario']['id']]);
    if ($stmt->rowCount() === 0) {
      return $response->withStatus(404);
    }
  } catch (PDOException $exception) {
    return $response->withStatus(500);
  }

  return $response->withHeader('Location', '/mis-productos')->withStatus(303);
});

$app->addErrorMiddleware($debug, true, true);

return $app;
