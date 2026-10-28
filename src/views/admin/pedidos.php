<?php

declare(strict_types=1);

$pedidos = $pedidos ?? [];
?>
<main>
    <h1>Contratos y pedidos</h1>
    <p><a href="/">Volver al inicio</a></p>

    <?php if ($pedidos === []): ?>
        <p>Todavía no hay pedidos registrados.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Producto</th>
                    <th>Unidades</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                    <th>Comprador (snapshot)</th>
                    <th>Vendedor propietario (snapshot)</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td>#<?= htmlspecialchars((string) $pedido['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>
                            <?= htmlspecialchars((string) $pedido['nombre_producto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                            <small>ID original: <?= htmlspecialchars((string) ($pedido['producto_id_snapshot'] ?? 'eliminado'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
                        </td>
                        <td><?= htmlspecialchars((string) $pedido['cantidad'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars((string) $pedido['precio_unitario'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars((string) $pedido['total'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>
                            <?= htmlspecialchars((string) $pedido['comprador_nombre_snapshot'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                            <small>ID <?= htmlspecialchars((string) $pedido['comprador_id_snapshot'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars((string) $pedido['comprador_email_snapshot'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars((string) $pedido['nombre_vendedor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                            <small>ID <?= htmlspecialchars((string) ($pedido['propietario_usuario_id_snapshot'] ?? 'no disponible'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars((string) ($pedido['email_vendedor_snapshot'] ?? 'no disponible'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
                        </td>
                        <td><?= htmlspecialchars((string) $pedido['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $pedido['creado_en'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
