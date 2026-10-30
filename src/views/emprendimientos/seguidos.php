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
        <article class="form-card">
          <h2><?= html((string) $emprendimiento['nombre']) ?></h2>
          <?php if (!empty($emprendimiento['localidad']) || !empty($emprendimiento['provincia'])): ?>
            <p><?= html(implode(', ', array_filter([$emprendimiento['localidad'] ?? '', $emprendimiento['provincia'] ?? '']))) ?></p>
          <?php endif; ?>
          <p><?= html((string) $emprendimiento['descripcion']) ?></p>
          <p><?= (int) $emprendimiento['productos'] ?> productos aprobados</p>
          <a class="button button-outline" href="/emprendimientos/<?= rawurlencode((string) $emprendimiento['id']) ?>">Ver emprendimiento</a>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
