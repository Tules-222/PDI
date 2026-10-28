<?php

declare(strict_types=1);

$pedidos = $pedidos ?? [];
$esVenta = $esVenta ?? false;
?>
<main>
    <h1><?= $esVenta ? 'Mis ventas' : 'Mis compras' ?></h1>
    <p><a href="/">Volver al catálogo</a></p>

    <?php if ($pedidos === []): ?>
        <p><?= $esVenta ? 'Todavía no recibiste solicitudes de compra.' : 'Todavía no realizaste compras.' ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                    <th><?= $esVenta ? 'Comprador' : 'Vendedor' ?></th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <?php if ($esVenta): ?><th>Acción</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td>#<?= htmlspecialchars((string) $pedido['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $pedido['nombre_producto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $pedido['cantidad'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars((string) $pedido['precio_unitario'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars((string) $pedido['total'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td>
                            <?= htmlspecialchars((string) ($esVenta ? $pedido['comprador_nombre'] : $pedido['nombre_vendedor']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                            <small><?= htmlspecialchars((string) ($esVenta ? $pedido['comprador_email_snapshot'] : $pedido['email_vendedor_snapshot']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
                        </td>
                        <td><?= htmlspecialchars((string) $pedido['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $pedido['creado_en'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <?php if ($esVenta): ?>
                            <td>
                                <?php if ($pedido['estado'] === 'pendiente'): ?>
                                    <form method="POST" action="/ventas/<?= rawurlencode((string) $pedido['id']) ?>/estado">
                                        <button type="submit" name="estado" value="aceptado">Aceptar</button>
                                        <button type="submit" name="estado" value="rechazado">Rechazar</button>
                                    </form>
                                <?php else: ?>
                                    Sin acciones
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
