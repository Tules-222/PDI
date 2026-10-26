<?php

declare(strict_types=1);

$productos = $productos ?? [];
$error = $error ?? null;
$misProductos = $misProductos ?? false;
?>
<main>
  <h1><?= $misProductos ? 'Mis productos' : 'Productos' ?></h1>
  <p><a href="/productos/create">Crear producto</a></p>

  <?php if ($error !== null): ?>
    <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
  <?php elseif ($productos === []): ?>
    <p>Todavía no hay productos cargados.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Descripción</th>
          <th>Creado</th>
          <?php if ($misProductos): ?><th>Estado</th><th>Acciones</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $producto): ?>
          <tr>
            <td><?= htmlspecialchars((string) $producto['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $producto['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $producto['precio'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $producto['descripcion'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $producto['creado_en'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <?php if ($misProductos): ?>
              <td><?= htmlspecialchars((string) $producto['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
              <td>
                <a href="/productos/<?= rawurlencode((string) $producto['id']) ?>">Ver</a>
                <a href="/productos/<?= rawurlencode((string) $producto['id']) ?>/editar">Editar</a>
                <form method="POST" action="/productos/<?= rawurlencode((string) $producto['id']) ?>/eliminar">
                  <button type="submit">Eliminar</button>
                </form>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>