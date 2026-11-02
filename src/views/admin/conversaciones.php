<?php

declare(strict_types=1);

$conversaciones = $conversaciones ?? [];
?>
<main class="page-content admin-page">
  <a class="back-link" href="/admin">← Volver al panel</a>
  <h1>Conversaciones</h1>
  <p class="notice notice-review">El listado no muestra mensajes. Para consultar contenido, registrá un motivo de al menos 20 caracteres. El acceso se audita bajo el PIN administrativo compartido.</p>

  <?php if ($conversaciones === []): ?>
    <div class="empty-state"><h2>No hay conversaciones</h2></div>
  <?php else: ?>
    <section class="admin-conversation-list" aria-label="Conversaciones">
      <?php foreach ($conversaciones as $conversacion): ?>
        <article class="form-card admin-conversation-card">
          <div>
            <h2>Conversación #<?= (int) $conversacion['id'] ?></h2>
            <p>Comprador: <?= html((string) $conversacion['comprador']) ?> · Emprendimiento: <?= html((string) ($conversacion['emprendimiento'] ?? 'No disponible')) ?></p>
            <p>Vendedor: <?= html((string) $conversacion['vendedor']) ?><?= !empty($conversacion['producto']) ? ' · Producto: ' . html((string) $conversacion['producto']) : '' ?></p>
            <p><?= (int) $conversacion['cantidad_mensajes'] ?> mensajes · Última actividad: <?= html((string) $conversacion['actualizado_en']) ?></p>
          </div>
          <form class="stacked-form" method="post" action="/admin/conversaciones/<?= (int) $conversacion['id'] ?>/acceso">
            <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
            <label for="motivo-<?= (int) $conversacion['id'] ?>">Motivo del acceso (20 a 500 caracteres)</label>
            <textarea id="motivo-<?= (int) $conversacion['id'] ?>" name="motivo" minlength="20" maxlength="500" rows="3" required></textarea>
            <button class="button button-outline" type="submit">Registrar motivo y consultar</button>
          </form>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
