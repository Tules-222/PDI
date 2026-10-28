<?php

declare(strict_types=1);

$producto = $producto ?? null;
$error = $error ?? null;
$usuarioActual = $usuarioActual ?? null;
?>
<main>
    <p><a href="/">Volver al catálogo</a></p>

    <?php if ($error !== null): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php elseif ($producto === null): ?>
        <h1>Producto no encontrado</h1>
    <?php else: ?>
        <article>
            <h1><?= htmlspecialchars((string) $producto['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars((string) $producto['descripcion'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p><strong>Precio:</strong> $<?= htmlspecialchars((string) $producto['precio'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p><strong>Vendedor:</strong> <?= htmlspecialchars((string) $producto['vendedor_nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <?php if ($usuarioActual !== null && (int) $producto['usuario_id'] === (int) $usuarioActual['id']): ?>
                <a href="/productos/<?= rawurlencode((string) $producto['id']) ?>/editar">Editar producto</a>
                <form method="POST" action="/productos/<?= rawurlencode((string) $producto['id']) ?>/eliminar">
                    <button type="submit">Eliminar producto</button>
                </form>
            <?php elseif ($producto['estado'] === 'aprobado'): ?>
                <?php if ($usuarioActual === null): ?>
                    <a href="/usuarios/login?next=<?= rawurlencode('/productos/' . (string) $producto['id']) ?>">Iniciá sesión para comprar</a>
                <?php else: ?>
                    <form method="POST" action="/productos/<?= rawurlencode((string) $producto['id']) ?>/comprar">
                        <label for="cantidad">Cantidad</label>
                        <input id="cantidad" name="cantidad" type="number" min="1" max="99" value="1" required>
                        <button type="submit">Solicitar compra</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </article>
    <?php endif; ?>
</main>