<?php

declare(strict_types=1);

function pendingNotificationCount(PDO $pdo, int $userId): int
{
  $query = $pdo->prepare(
    'SELECT (SELECT COUNT(*) FROM notificaciones WHERE usuario_id = :usuario_notificaciones AND leida_en IS NULL) + (SELECT COUNT(*) FROM mensajes m INNER JOIN conversaciones c ON c.id = m.conversacion_id WHERE (c.comprador_id = :usuario_comprador OR c.vendedor_id = :usuario_vendedor) AND m.remitente_id <> :usuario_remitente AND m.leido_en IS NULL)'
  );
  $query->execute([
    'usuario_notificaciones' => $userId,
    'usuario_comprador' => $userId,
    'usuario_vendedor' => $userId,
    'usuario_remitente' => $userId,
  ]);

  return (int) $query->fetchColumn();
}
