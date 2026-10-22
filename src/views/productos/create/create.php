<?php

declare(strict_types=1);

$error = $error ?? null;
?>
<main>
    <h1>Crear producto</h1>

    <?php if ($error !== null): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/productos">
        <div>
            <label for="nombre">Nombre del producto</label>
            <input id="nombre" name="nombre" type="text" maxlength="255" required>
        </div>

        <div>
            <label for="precio">Precio</label>
            <input id="precio" name="precio" type="number" min="0" step="0.01" required>
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" maxlength="1000" rows="4" required></textarea>
        </div>

        <button type="submit">Crear producto</button>
    </form>
</main>