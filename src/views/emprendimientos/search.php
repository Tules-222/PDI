<?php

declare(strict_types=1);

$emprendimientos = $emprendimientos ?? [];
$search = $search ?? '';
$categories = $categories ?? productCategories();
$category = $category ?? '';
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <section class="catalog-heading">
    <div>
      <p class="eyebrow">Perfiles comerciales</p>
      <h1>Buscar emprendimientos</h1>
    </div>
  </section>
  <form class="store-search-form" action="/emprendimientos" method="get" role="search">
    <label>
      Buscar emprendimientos
      <input id="store-search" type="search" name="q" value="<?= html($search) ?>" placeholder="Nombre, descripción o ubicación">
    </label>
    <label>
      Rubro
      <select name="rubro">
        <option value="">Todos los rubros</option>
        <?php foreach ($categories as $value => $label): ?>
          <option value="<?= html($value) ?>" <?= $category === $value ? 'selected' : '' ?>><?= html($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="button button-primary" type="submit">Buscar</button>
  </form>
  <section class="products-grid" aria-label="Resultados de emprendimientos">
    <?php if ($emprendimientos === []): ?>
      <div class="empty-state">
        <h2>No encontramos emprendimientos</h2>
        <p>Probá con otro nombre, descripción o ubicación.</p>
      </div>
    <?php else: ?>
      <?php foreach ($emprendimientos as $store): ?>
        <?php
        $primaryColor = isValidStoreColor((string) $store['color_primario']) ? (string) $store['color_primario'] : '#166534';
        $secondaryColor = isValidStoreColor((string) $store['color_secundario']) ? (string) $store['color_secundario'] : '#f0fdf4';
        ?>
        <article class="form-card store-search-card" style="--store-primary: <?= html($primaryColor) ?>; --store-primary-text: <?= html(storeBrandColorText($primaryColor)) ?>; --store-secondary: <?= html($secondaryColor) ?>;">
          <a class="store-search-cover" href="/emprendimientos/<?= rawurlencode((string) $store['id']) ?>" aria-label="Ver <?= html((string) $store['nombre']) ?>">
            <?php if (!empty($store['banner_path'])): ?>
              <img src="<?= html((string) $store['banner_path']) ?>" alt="" loading="lazy">
            <?php else: ?>
              <span class="store-cover-default"><span class="store-category-art" aria-hidden="true"><?= html(storeCategoryIcon((string) $store['rubro'])) ?></span><?= html($categories[$store['rubro']] ?? 'Emprendimiento') ?></span>
            <?php endif; ?>
            <?php if (!empty($store['logo_path'])): ?><img class="store-search-logo" src="<?= html((string) $store['logo_path']) ?>" alt="" loading="lazy"><?php endif; ?>
          </a>
          <div class="store-search-content">
            <p class="eyebrow"><?= html($categories[$store['rubro']] ?? 'Otros') ?> · <?= (int) $store['productos'] ?> productos</p>
            <h2><?= html((string) $store['nombre']) ?></h2>
            <p><?= html((string) $store['descripcion']) ?></p>
            <p><?= html(implode(', ', array_filter([$store['localidad'] ?? '', $store['provincia'] ?? '']))) ?></p>
            <a class="button button-outline" href="/emprendimientos/<?= rawurlencode((string) $store['id']) ?>">Ver perfil y productos</a>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>
