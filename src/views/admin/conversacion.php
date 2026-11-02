<?php

declare(strict_types=1);

$conversacion = $conversacion ?? [];
$mensajes = $mensajes ?? [];
$motivoAcceso = $motivoAcceso ?? '';
?>
<main class="page-content admin-page">
  <a class="back-link" href="/admin/conversaciones">← Volver a conversaciones</a>
  <h1>Conversación #<?= (int) $conversacion['id'] ?></h1>
  <p>Participantes: <?= html((string) $conversacion['comprador']) ?> y <?= html((string) $conversacion['vendedor']) ?></p>
  <p>Emprendimiento: <?= html((string) ($conversacion['emprendimiento'] ?? 'No disponible')) ?><?= !empty($conversacion['producto']) ? ' · Producto: ' . html((string) $conversacion['producto']) : '' ?></p>
  <p class="notice notice-review">Acceso registrado bajo el PIN compartido. Motivo: <?= html((string) $motivoAcceso) ?></p>
  <ol class="chat-messages admin-chat-messages">
    <?php foreach ($mensajes as $mensaje): ?>
      <li class="chat-message">
        <strong><?= html((string) $mensaje['remitente']) ?></strong>
        <p><?= html((string) $mensaje['contenido']) ?></p>
        <time><?= html((string) $mensaje['creado_en']) ?></time>
      </li>
    <?php endforeach; ?>
  </ol>
</main>
