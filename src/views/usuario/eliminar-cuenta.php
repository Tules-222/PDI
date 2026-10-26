<?php

declare(strict_types=1);

$error = $error ?? null;
?>
<main>
    <h1>Eliminar cuenta</h1>
    <p>Esta acción elimina tus productos y no se puede deshacer. Las solicitudes de compra que recibiste conservarán los datos del pedido, pero dejarán de estar asociadas a tu cuenta.</p>

    <?php if ($error !== null): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/mi-cuenta/eliminar">
        <label for="password">Confirmá tu contraseña</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button type="submit">Eliminar mi cuenta</button>
    </form>
    <p><a href="/">Cancelar</a></p>
</main>
