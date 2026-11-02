<header class="site-header">
  <div class="container header-main">
    <a href="/" class="logo" aria-label="Emprendi, inicio">
      <span class="logo-mark" aria-hidden="true">E</span>
      <span class="logo-text">Emprendi</span>
    </a>
    <form class="search" role="search" method="get" action="/buscar">
      <label for="site-search" class="sr-only">Buscar productos por nombre</label>
      <input id="site-search" type="search" name="q" placeholder="Buscar productos, marcas y emprendimientos..." autocomplete="off" required />
      <button type="submit" class="search-btn" aria-label="Buscar">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      </button>
    </form>
    <nav class="account-nav" aria-label="Cuenta">
      <?php if (!empty($usuarioAutenticado)): ?>
        <a href="/cuenta" class="nav-link account-link"><span>Mi cuenta</span></a>
      <?php else: ?>
        <a href="/auth/login" class="nav-link"><span>Iniciar sesión</span></a>
        <a href="/auth/register" class="nav-outline">Registrarse</a>
      <?php endif; ?>
      <a href="/carrito" class="cart-btn" aria-label="Ir al carrito">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
        <span class="cart-label">Carrito</span>
        <span class="cart-count" id="cart-count" aria-live="polite"><?= html((string) $cantidadCarrito) ?></span>
      </a>
    </nav>
  </div>
</header>

<main class="carrito-page confirmation-page">
  <?= $this->fetch("carrito/pasos.php", ["pasoActual" => 3]) ?>

  <section class="confirmation-card" aria-labelledby="confirmation-title">
    <div class="confirmation-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
    </div>
    <p class="carrito-eyebrow">Pedido recibido</p>
    <h1 id="confirmation-title">¡Recibimos tu pedido!</h1>
    <p class="confirmation-number">Número de pedido <strong><?= html((string) $pedido['numeroPedido']) ?></strong></p>
    <p class="confirmation-message">Tu pedido quedó registrado y está pendiente de pago. Nos comunicaremos con vos para coordinar el pago y el envío.</p>

    <div class="confirmation-items">
      <?php foreach ($pedido['items'] as $item): ?>
        <div class="confirmation-item">
          <span><?= html((string) $item['producto']['nombre']) ?> <strong>×<?= (int) $item['cantidad'] ?></strong></span>
          <strong>$<?= number_format((float) $item['subtotal'], 0, ',', '.') ?></strong>
        </div>
      <?php endforeach; ?>
      <div class="confirmation-total">
        <span>Total estimado</span>
        <strong>$<?= number_format((float) $pedido['total'], 0, ',', '.') ?></strong>
      </div>
    </div>

    <p class="confirmation-contact">
      Contacto: <?= html((string) $pedido['direccion']['telefono']) ?><br>
      Entrega: <?= html((string) $pedido['direccion']['calle']) ?>,
      <?= html((string) $pedido['direccion']['localidad']) ?>,
      <?= html((string) $pedido['direccion']['provincia']) ?>
    </p>
    <a href="/" class="carrito-boton confirmation-button">Volver al catálogo</a>
  </section>
</main>
