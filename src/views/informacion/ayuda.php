<?php

declare(strict_types=1);

$supportEmail = $supportEmail ?? null;
?>
<main class="page-content narrow-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <section class="form-card">
    <p class="eyebrow">Emprendi</p>
    <h1>Ayuda y soporte</h1>
    <div class="help-list">
      <section>
        <h2>¿Cómo publico un producto?</h2>
        <p>Iniciá sesión, completá los datos del producto y agregá al menos una imagen válida. La publicación aparecerá en el catálogo después de la revisión.</p>
      </section>
      <section>
        <h2>¿Cuándo puedo comprar?</h2>
        <p>Agregá productos aprobados que tengan stock confirmado al carrito. El vendedor debe registrar el costo de envío antes de que el pedido pueda pagarse.</p>
      </section>
      <section>
        <h2>¿Cómo funciona el envío?</h2>
        <p>Por el momento, el vendedor carga manualmente la cotización del envío. Emprendi todavía no obtiene tarifas ni etiquetas automáticamente de Correo Argentino.</p>
      </section>
      <section>
        <h2>¿Cómo se habilitan los pagos?</h2>
        <p>Los pagos requieren credenciales de Mercado Pago y una dirección pública HTTPS configuradas por el equipo de Emprendi. Si no están configuradas, el sitio informa que el pago está deshabilitado y no simula cobros.</p>
      </section>
    </div>
    <section class="help-contact">
      <h2>¿Necesitás más ayuda?</h2>
      <?php if (is_string($supportEmail)): ?>
        <p>Escribinos a <a href="mailto:<?= html($supportEmail) ?>"><?= html($supportEmail) ?></a>.</p>
      <?php else: ?>
        <p>El canal de soporte todavía no está configurado. Consultá nuevamente más adelante.</p>
      <?php endif; ?>
    </section>
  </section>
</main>
