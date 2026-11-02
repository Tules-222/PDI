<?php

declare(strict_types=1);

$conversaciones = $conversaciones ?? [];
$currentUserId = $currentUser['id'] ?? 0;
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <h1>Mensajes</h1>
  <p class="form-intro">Tus consultas privadas con compradores y emprendimientos.</p>
  <?php if ($conversaciones === []): ?>
    <div class="empty-state"><h2>Todavía no tenés conversaciones</h2><p>Podés iniciar una consulta desde el perfil de un emprendimiento o desde un producto.</p></div>
  <?php else: ?>
    <section class="conversation-list" aria-label="Tus conversaciones">
      <?php foreach ($conversaciones as $conversacion): ?>
        <article class="conversation-card form-card">
          <div>
            <h2><a href="/conversaciones/<?= rawurlencode((string) $conversacion['id']) ?>"><?= html((string) $conversacion['interlocutor']) ?></a></h2>
            <p><?= html((string) ($conversacion['emprendimiento'] ?? 'Emprendimiento')) ?><?= !empty($conversacion['producto']) ? ' · ' . html((string) $conversacion['producto']) : '' ?></p>
            <p class="conversation-preview">
              <?php if (!empty($conversacion['ultimo_mensaje'])): ?>
                <span class="conversation-direction"><?= (int) ($conversacion['ultimo_remitente_id'] ?? 0) === (int) $currentUserId ? 'Enviado:' : 'Recibido:' ?></span>
                <?= html((string) $conversacion['ultimo_mensaje']) ?>
              <?php else: ?>
                Iniciá la conversación con un mensaje.
              <?php endif; ?>
            </p>
          </div>
          <div class="conversation-meta">
            <?php if ((int) $conversacion['no_leidos'] > 0): ?><span class="conversation-unread"><?= (int) $conversacion['no_leidos'] ?> sin leer</span><?php endif; ?>
            <time><?= html((string) $conversacion['actualizado_en']) ?></time>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
