<?php

declare(strict_types=1);

$productos = $productos ?? [];
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al inicio</a>
  <div class="section-heading">
    <div>
      <p class="eyebrow">Tu actividad</p>
      <h1>Mis productos</h1>
    </div>
    <a class="button button-primary" href="/productos/create">Publicar producto</a>
  </div>
  <?php if ($productos === []): ?>
    <section class="empty-state">
      <h2>Todavía no publicaste productos</h2>
      <p>Cuando publiques uno, vas a poder editarlo o eliminarlo desde acá.</p>
      <a class="button button-primary" href="/productos/create">Publicar mi primer producto</a>
    </section>
  <?php else: ?>
    <section class="products-grid">
      <?php foreach ($productos as $producto): ?>
        <article class="product-card">
          <?php if (!empty($producto['imagenes'][0]['url'])): ?>
            <img class="product-card-image" src="<?= html($producto['imagenes'][0]['url']) ?>" alt="<?= html((string) $producto['nombre']) ?>" loading="lazy">
          <?php else: ?>
            <div class="product-card-image-placeholder">Sin imagen</div>
          <?php endif; ?>
          <div class="product-info">
            <span class="product-tag product-status-<?= html((string) $producto['estado']) ?>">
              <?= $producto['estado'] === 'pendiente' ? 'Pendiente de revisión' : ($producto['estado'] === 'rechazado' ? 'No aprobado' : 'Aprobado') ?>
            </span>
            <?php if (!empty($producto['motivo_revision'])): ?>
              <p class="review-reason"><?= html((string) $producto['motivo_revision']) ?></p>
            <?php endif; ?>
            <span class="category-chip"><?= html($categorias[$producto['categoria']] ?? 'Otros') ?></span>
            <?php if (!empty($producto['emprendimiento_nombre'])): ?><p>Emprendimiento: <?= html((string) $producto['emprendimiento_nombre']) ?></p><?php endif; ?>
            <h2 class="product-name"><?= html((string) $producto['nombre']) ?></h2>
            <p class="product-description"><?= html((string) $producto['descripcion']) ?></p>
            <p class="price-wholesale">$<?= html(number_format((float) $producto['precio'], 2, ',', '.')) ?></p>
            <section class="offer-management">
              <h3>Oferta promocional</h3>
              <?php
              $offerStates = [
                'sin_oferta' => 'Sin oferta',
                'pendiente' => 'Pendiente de aprobación',
                'aprobada' => 'Aprobada',
                'rechazada' => 'Rechazada',
                'desactivada' => 'Desactivada',
              ];
              ?>
              <p>Estado: <?= html($offerStates[$producto['oferta_estado']] ?? 'Sin oferta') ?></p>
              <?php if (!empty($producto['oferta_precio'])): ?>
                <p>Precio propuesto: $<?= html(number_format((float) $producto['oferta_precio'], 2, ',', '.')) ?> · Vigencia: <?= html(formatProductOfferDate($producto['oferta_inicio'])) ?> al <?= html(formatProductOfferDate($producto['oferta_fin'])) ?></p>
              <?php endif; ?>
              <?php if (!empty($producto['oferta_motivo_revision'])): ?>
                <p class="review-reason">Motivo de rechazo: <?= html((string) $producto['oferta_motivo_revision']) ?></p>
              <?php endif; ?>
              <?php if ($producto['estado'] === 'aprobado' && $producto['emprendimiento_estado'] === 'activo' && (float) $producto['precio'] >= 0.02): ?>
                <form class="stacked-form" method="post" action="/productos/<?= rawurlencode((string) $producto['id']) ?>/oferta">
                  <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
                  <input type="hidden" name="accion" value="proponer">
                  <label for="oferta-precio-<?= html((string) $producto['id']) ?>">Precio de oferta (menor a $<?= html(number_format((float) $producto['precio'], 2, ',', '.')) ?>)</label>
                  <input id="oferta-precio-<?= html((string) $producto['id']) ?>" name="oferta_precio" type="number" min="0.01" max="<?= html(number_format((float) $producto['precio'] - 0.01, 2, '.', '')) ?>" step="0.01" required>
                  <label for="oferta-inicio-<?= html((string) $producto['id']) ?>">Inicio</label>
                  <input id="oferta-inicio-<?= html((string) $producto['id']) ?>" name="oferta_inicio" type="datetime-local" required>
                  <label for="oferta-fin-<?= html((string) $producto['id']) ?>">Fin</label>
                  <input id="oferta-fin-<?= html((string) $producto['id']) ?>" name="oferta_fin" type="datetime-local" required>
                  <button class="button button-outline" type="submit">Proponer oferta</button>
                </form>
              <?php endif; ?>
              <?php if (in_array($producto['oferta_estado'], ['pendiente', 'aprobada'], true)): ?>
                <form method="post" action="/productos/<?= rawurlencode((string) $producto['id']) ?>/oferta">
                  <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
                  <input type="hidden" name="accion" value="desactivar">
                  <button class="text-button" type="submit">Desactivar oferta</button>
                </form>
              <?php endif; ?>
            </section>
            <div class="card-actions">
              <a class="button button-outline" href="/productos/<?= rawurlencode((string) $producto['id']) ?>">Ver</a>
              <a class="button button-outline" href="/productos/<?= rawurlencode((string) $producto['id']) ?>/editar">Editar</a>
              <form method="post" action="/productos/<?= rawurlencode((string) $producto['id']) ?>/eliminar">
                <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
                <button class="button button-danger" type="submit">Eliminar</button>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
