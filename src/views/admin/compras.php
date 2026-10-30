<?php

declare(strict_types=1);

$error = $error ?? null;
$compraId = $compraId ?? '';
$motivo = $motivo ?? '';
$compra = $compra ?? null;
$items = $items ?? [];
?>
<main class="page-content admin-page">
  <a class="back-link" href="/admin">← Volver al panel</a>
  <h1>Historial de compras</h1>
  <p>La búsqueda requiere un motivo y queda registrada con el PIN administrativo compartido. Se muestran solo datos comerciales necesarios para la investigación.</p>

  <?php if ($error !== null): ?><p class="notice notice-error" role="alert"><?= html($error) ?></p><?php endif; ?>

  <section class="form-card">
    <form class="stacked-form" method="post" action="/admin/compras/consultar">
      <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
      <div class="form-field">
        <label for="compra_id">Número de compra</label>
        <input id="compra_id" name="compra_id" type="number" min="1" value="<?= html((string) $compraId) ?>" required>
      </div>
      <div class="form-field">
        <label for="motivo">Motivo de la consulta (20 a 500 caracteres)</label>
        <textarea id="motivo" name="motivo" minlength="20" maxlength="500" rows="3" required><?= html((string) $motivo) ?></textarea>
      </div>
      <button class="button button-primary" type="submit">Registrar motivo y consultar</button>
    </form>
  </section>

  <?php if ($compra !== null): ?>
    <section class="form-card admin-purchase-result">
      <h2>Compra #<?= (int) $compra['id'] ?></h2>
      <p>Comprador: <?= html((string) $compra['comprador']) ?></p>
      <p>Creada: <?= html((string) $compra['creado_en']) ?> · Estado: <?= html((string) $compra['estado']) ?> · Pago: <?= html((string) $compra['estado_pago']) ?></p>
      <p>Envío: <?= html((string) ($compra['envio_estado'] ?? 'sin cotizar')) ?><?= $compra['envio_tracking'] ? ' · Seguimiento: ' . html((string) $compra['envio_tracking']) : '' ?></p>
      <ul>
        <?php foreach ($items as $item): ?>
          <li><?= html((string) $item['nombre_emprendimiento']) ?> · <?= html((string) $item['nombre_producto']) ?> · <?= (int) $item['cantidad'] ?> <?= html((string) $item['producto_unidad']) ?> · <?= html(formatArs((float) $item['precio_unitario'] * (int) $item['cantidad'])) ?></li>
        <?php endforeach; ?>
      </ul>
      <p><strong>Total del pedido: <?= html(formatArs($compra['total'])) ?></strong></p>
      <?php if ($compra['envio_costo'] !== null): ?><p>Incluye costo de envío: <?= html(formatArs($compra['envio_costo'])) ?></p><?php endif; ?>
    </section>
  <?php endif; ?>
</main>
