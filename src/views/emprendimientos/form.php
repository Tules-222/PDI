<?php

declare(strict_types=1);

$emprendimiento = $emprendimiento ?? [];
$editing = $editing ?? false;
$error = $error ?? null;
$id = $emprendimiento['id'] ?? '';
$primaryColor = isValidStoreColor((string) ($emprendimiento['color_primario'] ?? ''))
  ? (string) $emprendimiento['color_primario']
  : '#166534';
$secondaryColor = isValidStoreColor((string) ($emprendimiento['color_secundario'] ?? ''))
  ? (string) $emprendimiento['color_secundario']
  : '#f0fdf4';
?>
<main class="page-content narrow-content">
  <a class="back-link" href="/mis-emprendimientos">← Volver a mis emprendimientos</a>
  <section class="form-card">
    <p class="eyebrow">Perfil comercial</p>
    <h1><?= $editing ? 'Editar emprendimiento' : 'Crear emprendimiento' ?></h1>
    <?php if ($error !== null): ?><p class="notice notice-error" role="alert"><?= html($error) ?></p><?php endif; ?>
    <form class="stacked-form" method="post" enctype="multipart/form-data" action="<?= $editing ? '/emprendimientos/' . rawurlencode((string) $id) . '/actualizar' : '/emprendimientos' ?>">
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
      <fieldset class="store-branding-fields">
        <legend>Identidad visual</legend>
        <p>Usá imágenes JPEG, PNG o WebP de hasta 5 MB. El logo debe medir al menos 100 × 100 píxeles y el banner 640 × 180.</p>
        <?php foreach (['logo' => 'Logo', 'banner' => 'Banner'] as $imageKey => $imageLabel): ?>
          <?php $imagePath = $emprendimiento[$imageKey . '_path'] ?? null; ?>
          <div class="form-field">
            <label for="<?= $imageKey ?>"><?= $imageLabel ?> del emprendimiento</label>
            <input id="<?= $imageKey ?>" name="<?= $imageKey ?>" type="file" accept="image/jpeg,image/png,image/webp">
            <?php if ($editing && is_string($imagePath) && $imagePath !== ''): ?>
              <img class="store-branding-preview store-branding-preview-<?= $imageKey ?>" src="<?= html($imagePath) ?>" alt="<?= $imageLabel ?> actual">
              <label class="store-branding-remove">
                <input type="checkbox" name="eliminar_<?= $imageKey ?>" value="1">
                Quitar el <?= strtolower($imageLabel) ?> actual
              </label>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
        <div class="form-grid">
          <div class="form-field">
            <label for="color_primario">Color principal</label>
            <input id="color_primario" name="color_primario" type="color" value="<?= html($primaryColor) ?>" required>
          </div>
          <div class="form-field">
            <label for="color_secundario">Color de acento</label>
            <input id="color_secundario" name="color_secundario" type="color" value="<?= html($secondaryColor) ?>" required>
          </div>
        </div>
      </fieldset>
      <button class="button button-primary button-wide" type="submit"><?= $editing ? 'Guardar cambios' : 'Crear emprendimiento' ?></button>
    </form>
  </section>
</main>
