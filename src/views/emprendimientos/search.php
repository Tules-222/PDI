<?php

declare(strict_types=1);

$emprendimientos = $emprendimientos ?? [];
$search = $search ?? '';
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <section class="catalog-heading">
    <div>
      <p class="eyebrow">Perfiles comerciales</p>
      <h1>Buscar emprendimientos</h1>
    </div>
  </section>
  <form class="search-form" action="/emprendimientos" method="get" role="search">
    <label class="visually-hidden" for="store-search">Buscar emprendimientos</label>
    <input id="store-search" type="search" name="q" value="<?= html($search) ?>" placeholder="Nombre, descripción o ubicación">
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
          <?php if (!empty($store['logo_path'])): ?><img class="store-search-logo" src="<?= html((string) $store['logo_path']) ?>" alt="Logo de <?= html((string) $store['nombre']) ?>" loading="lazy"><?php endif; ?>
          <p class="eyebrow"><?= (int) $store['productos'] ?> productos aprobados</p>
          <h2><?= html((string) $store['nombre']) ?></h2>
          <p><?= html((string) $store['descripcion']) ?></p>
          <p><?= html(implode(', ', array_filter([$store['localidad'] ?? '', $store['provincia'] ?? '']))) ?></p>
          <a class="button button-outline" href="/emprendimientos/<?= rawurlencode((string) $store['id']) ?>">Ver perfil y productos</a>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>
