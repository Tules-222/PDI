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

<main class="carrito-page checkout-page">
  <?= $this->fetch("carrito/pasos.php", ["pasoActual" => 2]) ?>

  <div class="carrito-heading">
    <div>
      <p class="carrito-eyebrow">Último paso</p>
      <h1>Finalizar compra</h1>
      <p class="carrito-intro">Completá los datos para registrar tu pedido.</p>
    </div>
  </div>

  <?php if (!empty($errors)): ?>
    <ul class="checkout-errors" role="alert" aria-label="Revisá los datos del pedido">
      <?php foreach ($errors as $error): ?>
        <li><?= html((string) $error) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form action="/carrito/pago" method="post" class="checkout-grid">
    <section class="checkout-details" aria-label="Datos del pedido">
      <div class="checkout-card">
        <h2>Datos de entrega</h2>
        <p class="checkout-card-intro">¿A dónde enviamos tu pedido?</p>

        <div class="checkout-fields">
          <div class="checkout-field">
            <label for="nombre">Nombre y apellido</label>
            <input id="nombre" name="nombre" type="text" autocomplete="name" value="<?= html((string) ($data['nombre'] ?? '')) ?>" required>
          </div>
          <div class="checkout-field">
            <label for="telefono">Teléfono</label>
            <input id="telefono" name="telefono" type="tel" autocomplete="tel" value="<?= html((string) ($data['telefono'] ?? '')) ?>" required>
          </div>
          <div class="checkout-field checkout-field-wide">
            <label for="calle">Calle y número</label>
            <input id="calle" name="calle" type="text" autocomplete="street-address" value="<?= html((string) ($data['calle'] ?? '')) ?>" required>
          </div>
          <div class="checkout-field">
            <label for="localidad">Localidad</label>
            <input id="localidad" name="localidad" type="text" autocomplete="address-level2" value="<?= html((string) ($data['localidad'] ?? '')) ?>" required>
          </div>
          <div class="checkout-field">
            <label for="provincia">Provincia</label>
            <input id="provincia" name="provincia" type="text" autocomplete="address-level1" value="<?= html((string) ($data['provincia'] ?? '')) ?>" required>
          </div>
          <div class="checkout-field checkout-field-wide">
            <label for="codigo_postal">Código postal</label>
            <input id="codigo_postal" name="codigo_postal" type="text" autocomplete="postal-code" value="<?= html((string) ($data['codigo_postal'] ?? '')) ?>" required>
          </div>
        </div>
      </div>

      <fieldset class="checkout-card checkout-payment">
        <legend>Método de pago preferido</legend>
        <p class="checkout-card-intro">Coordinaremos el pago una vez confirmado el pedido.</p>
        <label class="payment-option">
          <input type="radio" name="metodo_pago" value="mercadopago" <?= ($data['metodo_pago'] ?? 'mercadopago') === 'mercadopago' ? 'checked' : '' ?> required>
          <span>
            <strong>Mercado Pago</strong>
            <small>Preferencia de pago; no se realizará ningún cargo ahora.</small>
          </span>
        </label>
        <label class="payment-option">
          <input type="radio" name="metodo_pago" value="tarjeta" <?= ($data['metodo_pago'] ?? '') === 'tarjeta' ? 'checked' : '' ?>>
          <span>
            <strong>Tarjeta</strong>
            <small>Te contactaremos para coordinar el pago de forma segura.</small>
          </span>
        </label>
        <p class="checkout-payment-note">Este sitio todavía no cuenta con una pasarela de pago. No ingreses datos de tarjeta.</p>
      </fieldset>
    </section>

    <aside class="checkout-summary" aria-labelledby="checkout-summary-title">
      <h2 id="checkout-summary-title">Resumen del pedido</h2>
      <div class="checkout-summary-items">
        <?php foreach ($items as $item): ?>
          <div class="checkout-summary-item">
            <span><?= html((string) $item['producto']['nombre']) ?> <strong>×<?= (int) $item['cantidad'] ?></strong></span>
            <strong>$<?= number_format((float) $item['subtotal'], 0, ',', '.') ?></strong>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="checkout-summary-total">
        <span>Total estimado</span>
        <strong>$<?= number_format((float) $total, 0, ',', '.') ?></strong>
      </div>
      <p class="checkout-summary-note">El envío se coordinará con vos. Al confirmar, el pedido quedará pendiente de pago.</p>
      <button type="submit" class="carrito-boton carrito-boton-secundario">Confirmar pedido</button>
      <a href="/carrito" class="checkout-back">Volver al carrito</a>
    </aside>
  </form>
</main>
