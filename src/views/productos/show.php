<?php

declare(strict_types=1);

$producto = $producto ?? null;
$error = $error ?? null;
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
        </article>
    <?php endif; ?>
</main>