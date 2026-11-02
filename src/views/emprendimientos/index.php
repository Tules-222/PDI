<?php

declare(strict_types=1);

$emprendimientos = $emprendimientos ?? [];
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <div class="section-heading">
    <div>
      <p class="eyebrow">Tu actividad</p>
      <h1>Mis emprendimientos</h1>
    </div>
    <a class="button button-primary" href="/emprendimientos/crear">Crear emprendimiento</a>
  </div>
  <?php if ($emprendimientos === []): ?>
    <section class="empty-state">
      <h2>Todavía no creaste un emprendimiento</h2>
      <p>Creá un perfil comercial para organizar y publicar productos.</p>
      <a class="button button-primary" href="/emprendimientos/crear">Crear emprendimiento</a>
    </section>
  <?php else: ?>
    <section class="products-grid" aria-label="Emprendimientos">
      <?php foreach ($emprendimientos as $store): ?>
        <?php
        $primaryColor = isValidStoreColor((string) $store['color_primario']) ? (string) $store['color_primario'] : '#166534';
        $secondaryColor = isValidStoreColor((string) $store['color_secundario']) ? (string) $store['color_secundario'] : '#f0fdf4';
        ?>
        <article class="form-card store-search-card" style="--store-primary: <?= html($primaryColor) ?>; --store-primary-text: <?= html(storeBrandColorText($primaryColor)) ?>; --store-secondary: <?= html($secondaryColor) ?>;">
          <div class="store-search-cover" aria-hidden="true">
            <?php if (!empty($store['banner_path'])): ?><img src="<?= html((string) $store['banner_path']) ?>" alt="" loading="lazy"><?php else: ?><span class="store-cover-default"><span class="store-category-art"><?= html(storeCategoryIcon((string) $store['rubro'])) ?></span><?= html(productCategories()[$store['rubro']] ?? 'Emprendimiento') ?></span><?php endif; ?>
            <?php if (!empty($store['logo_path'])): ?><img class="store-search-logo" src="<?= html((string) $store['logo_path']) ?>" alt="" loading="lazy"><?php endif; ?>
          </div>
          <div class="store-search-content">
          <span class="product-tag"><?= $store['estado'] === 'activo' ? 'Activo' : 'Pausado' ?></span>
          <h2><?= html((string) $store['nombre']) ?></h2>
          <p class="eyebrow"><?= html(productCategories()[$store['rubro']] ?? 'Otros') ?></p>
          <p><?= html((string) $store['descripcion']) ?></p>
          <p><?= html(implode(', ', array_filter([$store['localidad'] ?? '', $store['provincia'] ?? '']))) ?></p>
          <p><?= (int) $store['productos'] ?> productos</p>
          <div class="card-actions">
            <a class="button button-outline" href="/emprendimientos/<?= rawurlencode((string) $store['id']) ?>">Ver perfil</a>
            <a class="button button-outline" href="/emprendimientos/<?= rawurlencode((string) $store['id']) ?>/editar">Editar</a>
            <form method="post" action="/emprendimientos/<?= rawurlencode((string) $store['id']) ?>/estado">
              <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
              <input type="hidden" name="estado" value="<?= $store['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
              <button class="button button-outline" type="submit"><?= $store['estado'] === 'activo' ? 'Pausar' : 'Reactivar' ?></button>
            </form>
          </div>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
