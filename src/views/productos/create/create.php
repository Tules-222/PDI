<?php

declare(strict_types=1);

$error = $error ?? null;
$producto = $producto ?? null;
$formAction = $formAction ?? '/productos';
$value = static fn(string $field): string => htmlspecialchars((string) ($producto[$field] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<main>
    <h1><?= $producto === null ? 'Crear producto' : 'Editar producto' ?></h1>

    <?php if ($error !== null): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($formAction, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
        <div>
            <label for="nombre">Nombre del producto</label>
            <input id="nombre" name="nombre" type="text" maxlength="255" value="<?= $value('nombre') ?>" required>
        </div>

        <div>
            <label for="precio">Precio</label>
            <input id="precio" name="precio" type="number" min="0" step="0.01" value="<?= $value('precio') ?>" required>
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" maxlength="1000" rows="4" required><?= $value('descripcion') ?></textarea>
        </div>

        <button type="submit"><?= $producto === null ? 'Crear producto' : 'Guardar cambios' ?></button>
    </form>
</main>