<?php

declare(strict_types=1);

$notificaciones = $notificaciones ?? [];
$mensajesSinLeer = $mensajesSinLeer ?? [];
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <div class="section-heading">
    <div>
      <p class="eyebrow">Avisos de tu cuenta</p>
      <h1>Notificaciones</h1>
    </div>
    <?php if (count(array_filter($notificaciones, static fn(array $item): bool => $item['leida_en'] === null)) > 0): ?>
      <form method="post" action="/notificaciones/leer">
        <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
        <button class="button button-outline" type="submit">Marcar todas como leídas</button>
      </form>
    <?php endif; ?>
  </div>
  <?php if ($notificaciones === [] && $mensajesSinLeer === []): ?>
    <section class="empty-state"><h2>No tenés pendientes</h2><p>Los avisos de tus pedidos y mensajes sin leer van a aparecer acá.</p></section>
  <?php endif; ?>
  <?php if ($mensajesSinLeer !== []): ?>
    <section class="purchase-list" aria-label="Mensajes sin leer">
      <h2>Mensajes sin leer</h2>
      <?php foreach ($mensajesSinLeer as $mensaje): ?>
        <article class="purchase-card notification-unread">
          <p class="eyebrow">Mensaje de <?= html((string) $mensaje['interlocutor']) ?> · <?= html((string) $mensaje['creado_en']) ?></p>
          <p><?= html((string) $mensaje['contenido']) ?></p>
          <a class="button button-outline" href="/conversaciones/<?= rawurlencode((string) $mensaje['conversacion_id']) ?>">Abrir conversación</a>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
  <?php if ($notificaciones !== []): ?>
    <section class="purchase-list" aria-label="Notificaciones">
      <h2>Avisos de tu cuenta</h2>
      <?php foreach ($notificaciones as $item): ?>
        <article class="purchase-card <?= $item['leida_en'] === null ? 'notification-unread' : '' ?>">
          <p class="eyebrow"><?= html(ucfirst((string) $item['tipo'])) ?> · <?= html((string) $item['creado_en']) ?></p>
          <p><?= html((string) $item['mensaje']) ?></p>
          <?php if (!empty($item['enlace'])): ?><a class="button button-outline" href="<?= html((string) $item['enlace']) ?>">Ver detalle</a><?php endif; ?>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
