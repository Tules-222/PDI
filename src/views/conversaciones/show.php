<?php

declare(strict_types=1);

$conversacion = $conversacion ?? [];
$mensajes = $mensajes ?? [];
$currentUserId = $currentUserId ?? 0;
$lastMessageId = $lastMessageId ?? 0;
?>
<main class="page-content narrow-content">
  <a class="back-link" href="/conversaciones">← Volver a mensajes</a>
  <section class="form-card">
    <p class="eyebrow"><?= html((string) ($conversacion['emprendimiento'] ?? 'Emprendimiento')) ?><?= !empty($conversacion['producto']) ? ' · ' . html((string) $conversacion['producto']) : '' ?></p>
    <h1>Conversación con <?= html((string) $conversacion['interlocutor']) ?></h1>
    <p class="conversation-privacy-note">Solo las personas participantes pueden leer y responder estos mensajes.</p>

    <ol class="chat-messages" id="chat-messages" data-conversation-id="<?= html((string) $conversacion['id']) ?>" data-current-user="<?= (int) $currentUserId ?>" data-last-id="<?= (int) $lastMessageId ?>" aria-live="polite">
      <?php foreach ($mensajes as $mensaje): ?>
        <li class="chat-message <?= (int) $mensaje['remitente_id'] === (int) $currentUserId ? 'chat-message-mine' : '' ?>">
          <p><?= html((string) $mensaje['contenido']) ?></p>
          <time><?= html((string) $mensaje['creado_en']) ?></time>
        </li>
      <?php endforeach; ?>
    </ol>
    <p id="chat-update-status" class="conversation-privacy-note" role="status">Actualización automática cada 5 segundos.</p>

    <form class="stacked-form" method="post" action="/conversaciones/<?= rawurlencode((string) $conversacion['id']) ?>/mensajes">
      <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
      <div class="form-field">
        <label for="contenido">Tu mensaje</label>
        <textarea id="contenido" name="contenido" maxlength="2000" rows="4" required></textarea>
      </div>
      <button class="button button-primary" type="submit">Enviar mensaje</button>
    </form>
  </section>
</main>
<script>
(() => {
  const list = document.getElementById('chat-messages');
  const status = document.getElementById('chat-update-status');
  if (!list || !status) return;

  const conversationId = list.dataset.conversationId;
  const currentUserId = Number(list.dataset.currentUser);
  let lastId = Number(list.dataset.lastId);

  const poll = async () => {
    try {
      const response = await fetch(`/conversaciones/${encodeURIComponent(conversationId)}/nuevos?despues=${encodeURIComponent(String(lastId))}`, {
        headers: { Accept: 'application/json' },
        cache: 'no-store'
      });
      if (!response.ok) throw new Error('No se pudo actualizar la conversación.');
      const payload = await response.json();
      if (!Array.isArray(payload.mensajes)) throw new Error('Respuesta inválida del servidor.');

      for (const message of payload.mensajes) {
        const item = document.createElement('li');
        item.className = `chat-message${Number(message.remitente_id) === currentUserId ? ' chat-message-mine' : ''}`;
        const content = document.createElement('p');
        content.textContent = message.contenido;
        const time = document.createElement('time');
        time.textContent = message.creado_en;
        item.append(content, time);
        list.append(item);
        lastId = Math.max(lastId, Number(message.id));
      }
      if (payload.mensajes.length > 0) list.scrollTop = list.scrollHeight;
      status.textContent = 'Conectado. Actualización automática cada 5 segundos.';
    } catch (error) {
      status.textContent = 'No se pudo actualizar ahora. Se volverá a intentar en 5 segundos.';
    } finally {
      window.setTimeout(poll, 5000);
    }
  };

  window.setTimeout(poll, 5000);
})();
</script>
