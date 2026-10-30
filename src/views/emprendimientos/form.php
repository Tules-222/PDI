<?php

declare(strict_types=1);

$emprendimiento = $emprendimiento ?? [];
$editing = $editing ?? false;
$error = $error ?? null;
$id = $emprendimiento['id'] ?? '';
?>
<main class="page-content narrow-content">
  <a class="back-link" href="/mis-emprendimientos">← Volver a mis emprendimientos</a>
  <section class="form-card">
    <p class="eyebrow">Perfil comercial</p>
    <h1><?= $editing ? 'Editar emprendimiento' : 'Crear emprendimiento' ?></h1>
    <?php if ($error !== null): ?><p class="notice notice-error" role="alert"><?= html($error) ?></p><?php endif; ?>
    <form class="stacked-form" method="post" action="<?= $editing ? '/emprendimientos/' . rawurlencode((string) $id) . '/actualizar' : '/emprendimientos' ?>">
      <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
      <div class="form-field">
        <label for="nombre">Nombre del emprendimiento</label>
        <input id="nombre" name="nombre" type="text" maxlength="255" value="<?= html((string) ($emprendimiento['nombre'] ?? '')) ?>" required>
      </div>
      <div class="form-field">
        <label for="descripcion">Presentación comercial</label>
        <textarea id="descripcion" name="descripcion" maxlength="500" rows="4"><?= html((string) ($emprendimiento['descripcion'] ?? '')) ?></textarea>
      </div>
      <div class="form-grid">
        <div class="form-field">
          <label for="localidad">Localidad</label>
          <input id="localidad" name="localidad" type="text" maxlength="100" value="<?= html((string) ($emprendimiento['localidad'] ?? '')) ?>">
        </div>
        <div class="form-field">
          <label for="provincia">Provincia</label>
          <input id="provincia" name="provincia" type="text" maxlength="100" value="<?= html((string) ($emprendimiento['provincia'] ?? '')) ?>">
        </div>
      </div>
      <button class="button button-primary button-wide" type="submit"><?= $editing ? 'Guardar cambios' : 'Crear emprendimiento' ?></button>
    </form>
  </section>
</main>
