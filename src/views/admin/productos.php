<?php

declare(strict_types=1);

$productos = $productos ?? [];
?>
<main>
    <h1>Revisión de productos</h1>
    <p><a href="/">Volver al catálogo</a></p>

    <?php if ($productos === []): ?>
        <p>No hay productos para revisar.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $producto['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars((string) $producto['precio'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $producto['descripcion'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $producto['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>
                            <a href="/productos/<?= rawurlencode((string) $producto['id']) ?>">Ver</a>
                            <form method="POST" action="/admin/productos/<?= rawurlencode((string) $producto['id']) ?>/estado">
                                <button type="submit" name="estado" value="aprobado">Aprobar</button>
                                <button type="submit" name="estado" value="rechazado">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
