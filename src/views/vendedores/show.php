<?php

declare(strict_types=1);

$seller = $seller ?? [];
$sellerProducts = $sellerProducts ?? [];
$sellerRating = $sellerRating ?? ['promedio' => null, 'cantidad' => 0];
$followerCount = $followerCount ?? 0;
$isFollowing = $isFollowing ?? false;
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <section class="seller-profile form-card">
    <p class="eyebrow">Emprendimiento de Emprendi</p>
    <h1><?= html((string) $seller['nombre']) ?></h1>
    <?php if (!empty($seller['localidad']) || !empty($seller['provincia'])): ?>
      <p class="seller-location"><?= html(implode(', ', array_filter([$seller['localidad'] ?? '', $seller['provincia'] ?? '']))) ?></p>
    <?php endif; ?>
    <?php if ((int) $sellerRating['cantidad'] > 0): ?>
      <p class="product-rating">★ <?= number_format((float) $sellerRating['promedio'], 1, ',', '.') ?> <span>(<?= (int) $sellerRating['cantidad'] ?> opiniones de compras entregadas)</span></p>
    <?php else: ?>
      <p class="product-rating-empty">Todavía sin opiniones verificadas</p>
    <?php endif; ?>
    <p class="store-follow-count"><?= (int) $followerCount ?> seguidores</p>
    <?php if (!empty($seller['descripcion_vendedor'])): ?><p><?= html((string) $seller['descripcion_vendedor']) ?></p><?php endif; ?>
    <p class="seller-trust-note">Las opiniones se habilitan únicamente después de confirmar una entrega.</p>
    <?php if ($currentUser === null): ?>
      <a class="button button-outline" href="/usuarios/login?return_to=<?= rawurlencode('/emprendimientos/' . (string) $seller['id']) ?>">Ingresá para consultar o seguir este emprendimiento</a>
    <?php elseif ((int) ($seller['usuario_id'] ?? 0) !== $currentUser['id']): ?>
      <form method="post" action="/emprendimientos/<?= rawurlencode((string) $seller['id']) ?>/seguir">
        <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
        <input type="hidden" name="accion" value="<?= $isFollowing ? 'dejar' : 'seguir' ?>">
        <button class="button button-outline" type="submit"><?= $isFollowing ? 'Dejar de seguir' : 'Seguir emprendimiento' ?></button>
      </form>
      <form method="post" action="/conversaciones">
        <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
        <input type="hidden" name="emprendimiento_id" value="<?= html((string) $seller['id']) ?>">
        <button class="button button-outline" type="submit">Consultar al emprendimiento</button>
      </form>
      <details class="product-report">
        <summary>Reportar esta cuenta</summary>
        <form class="stacked-form" method="post" action="/usuarios/<?= rawurlencode((string) $seller['usuario_id']) ?>/reportar">
          <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
          <label for="account-report-reason">Motivo</label>
          <select id="account-report-reason" name="motivo" required>
            <option value="">Elegí un motivo</option>
            <option value="informacion_falsa">Información falsa</option>
            <option value="fraude">Fraude o estafa</option>
            <option value="acoso">Acoso o conducta abusiva</option>
            <option value="suplantacion">Suplantación de identidad</option>
            <option value="otro">Otro</option>
          </select>
          <label for="account-report-detail">Detalle (10 a 500 caracteres)</label>
          <textarea id="account-report-detail" name="detalle" minlength="10" maxlength="500" required></textarea>
          <button class="button button-outline" type="submit">Enviar reporte confidencial</button>
        </form>
      </details>
    <?php endif; ?>
  </section>

  <section class="seller-products">
    <h2>Productos publicados</h2>
    <?php if ($sellerProducts === []): ?>
      <div class="empty-state"><p>Este vendedor todavía no tiene productos disponibles.</p></div>
    <?php else: ?>
      <div class="products-grid">
        <?php foreach ($sellerProducts as $producto): ?>
          <article class="product-card">
            <a class="product-card-image-link" href="/productos/<?= rawurlencode((string) $producto['id']) ?>">
              <?php if (!empty($producto['imagenes'][0]['url'])): ?>
                <img class="product-card-image" src="<?= html($producto['imagenes'][0]['url']) ?>" alt="<?= html((string) $producto['nombre']) ?>" loading="lazy">
              <?php else: ?>
                <span class="product-card-image-placeholder">Sin imagen</span>
              <?php endif; ?>
            </a>
            <div class="product-info">
              <span class="product-tag"><?= $producto['stock'] !== null && (int) $producto['stock'] > 0 ? 'Stock confirmado' : 'Consultar disponibilidad' ?></span>
              <h3 class="product-name"><a class="product-card-title-link" href="/productos/<?= rawurlencode((string) $producto['id']) ?>"><?= html((string) $producto['nombre']) ?></a></h3>
              <p class="product-description"><?= html((string) $producto['descripcion']) ?></p>
              <?php if (!empty($producto['oferta_vigente'])): ?>
                <span class="price-original"><del><?= html(formatArs($producto['precio_original'])) ?></del></span>
                <span class="price-wholesale"><?= html(formatArs($producto['precio'])) ?> <span class="product-tag">Oferta</span></span>
                <span class="price-label">Oferta hasta <?= html(formatProductOfferDate($producto['oferta_fin'])) ?></span>
              <?php else: ?>
                <span class="price-wholesale"><?= html(formatArs($producto['precio'])) ?></span>
              <?php endif; ?>
              <span class="price-label">por <?= html((string) $producto['unidad']) ?> · compra mínima <?= (int) $producto['cantidad_minima'] ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
