<?php

declare(strict_types=1);

$ofertas = $ofertas ?? [];
?>
<main class="page-content admin-page">
  <a class="back-link" href="/admin">← Volver a administración</a>
  <p class="eyebrow">Moderación</p>
  <h1>Ofertas pendientes</h1>
  <?php if ($ofertas === []): ?>
    <section class="empty-state"><h2>No hay ofertas pendientes</h2><p>Las nuevas propuestas de vendedores aparecerán acá.</p></section>
  <?php else: ?>
    <section class="admin-list">
      <?php foreach ($ofertas as $oferta): ?>
        <article class="admin-card">
          <h2><?= html((string) $oferta['nombre']) ?></h2>
          <p>Emprendimiento: <?= html((string) $oferta['emprendimiento']) ?> · Vendedor: <?= html((string) $oferta['vendedor']) ?></p>
          <p>Precio regular: <?= html(formatArs($oferta['precio'])) ?> · Precio propuesto: <strong><?= html(formatArs($oferta['oferta_precio'])) ?></strong></p>
          <p>Vigencia: <?= html(formatProductOfferDate($oferta['oferta_inicio'])) ?> al <?= html(formatProductOfferDate($oferta['oferta_fin'])) ?></p>
          <form class="stacked-form" method="post" action="/admin/ofertas/<?= rawurlencode((string) $oferta['id']) ?>/revisar">
            <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
            <label for="motivo-<?= html((string) $oferta['id']) ?>">Motivo (obligatorio al rechazar)</label>
            <textarea id="motivo-<?= html((string) $oferta['id']) ?>" name="motivo" minlength="10" maxlength="500" rows="2"></textarea>
            <div class="card-actions">
              <button class="button button-primary" type="submit" name="accion" value="aprobar">Aprobar oferta</button>
              <button class="button button-danger" type="submit" name="accion" value="rechazar">Rechazar oferta</button>
            </div>
          </form>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
