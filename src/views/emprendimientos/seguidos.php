<?php

declare(strict_types=1);

$emprendimientos = $emprendimientos ?? [];
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <h1>Emprendimientos que sigo</h1>
  <?php if ($emprendimientos === []): ?>
    <div class="empty-state"><h2>Todavía no seguís emprendimientos activos</h2><p>Visitá un perfil para seguir una tienda y encontrarla acá.</p><a class="button button-primary" href="/emprendimientos">Buscar emprendimientos</a></div>
  <?php else: ?>
    <section class="products-grid" aria-label="Emprendimientos seguidos">
      <?php foreach ($emprendimientos as $emprendimiento): ?>
        <?php
        $primaryColor = isValidStoreColor((string) ($emprendimiento['color_primario'] ?? '')) ? (string) $emprendimiento['color_primario'] : '#166534';
        $secondaryColor = isValidStoreColor((string) ($emprendimiento['color_secundario'] ?? '')) ? (string) $emprendimiento['color_secundario'] : '#f0fdf4';
        ?>
        <article class="form-card store-search-card" style="--store-primary: <?= html($primaryColor) ?>; --store-primary-text: <?= html(storeBrandColorText($primaryColor)) ?>; --store-secondary: <?= html($secondaryColor) ?>;">
          <a class="store-search-cover" href="/emprendimientos/<?= rawurlencode((string) $emprendimiento['id']) ?>" aria-label="Ver <?= html((string) $emprendimiento['nombre']) ?>">
            <?php if (!empty($emprendimiento['banner_path'])): ?><img src="<?= html((string) $emprendimiento['banner_path']) ?>" alt="" loading="lazy"><?php else: ?><span class="store-cover-default"><span class="store-category-art" aria-hidden="true"><?= html(storeCategoryIcon((string) $emprendimiento['rubro'])) ?></span><?= html(productCategories()[$emprendimiento['rubro']] ?? 'Emprendimiento') ?></span><?php endif; ?>
            <?php if (!empty($emprendimiento['logo_path'])): ?><img class="store-search-logo" src="<?= html((string) $emprendimiento['logo_path']) ?>" alt="" loading="lazy"><?php endif; ?>
          </a>
          <div class="store-search-content">
          <h2><?= html((string) $emprendimiento['nombre']) ?></h2>
          <p class="eyebrow"><?= html(productCategories()[$emprendimiento['rubro']] ?? 'Otros') ?></p>
          <?php if (!empty($emprendimiento['localidad']) || !empty($emprendimiento['provincia'])): ?>
            <p><?= html(implode(', ', array_filter([$emprendimiento['localidad'] ?? '', $emprendimiento['provincia'] ?? '']))) ?></p>
          <?php endif; ?>
          <p><?= html((string) $emprendimiento['descripcion']) ?></p>
          <p><?= (int) $emprendimiento['productos'] ?> productos aprobados</p>
          <a class="button button-outline" href="/emprendimientos/<?= rawurlencode((string) $emprendimiento['id']) ?>">Ver emprendimiento</a>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
