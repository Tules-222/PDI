<header class="site-header">
  <div class="container header-main">
    <a href="/" class="logo" aria-label="Emprendi, inicio">
      <span class="logo-mark" aria-hidden="true">E</span>
      <span class="logo-text">Emprendi</span>
    </a>
    <form class="search" role="search" id="search-form" method="get" action="/buscar">
      <label for="site-search" class="sr-only">Buscar productos, marcas y emprendimientos</label>
      <input id="site-search" type="search" name="q" placeholder="Buscar productos, marcas y emprendimientos..." autocomplete="off" required />
      <button type="submit" class="search-btn" aria-label="Buscar">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      </button>
    </form>
    <nav class="account-nav" aria-label="Cuenta">
      <?php if (empty($usuarioAutenticado)): ?>
        <a href="/auth/login" class="nav-link"><span>Iniciar sesión</span></a>
        <a href="/auth/register" class="nav-outline">Registrarse</a>
      <?php else: ?>
        <a href="/cuenta" class="nav-link account-link"><span>Mi cuenta</span></a>
      <?php endif; ?>
      <a href="/carrito" class="cart-btn" aria-label="Ir al carrito">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
        <span class="cart-label">Carrito</span>
        <span class="cart-count" id="cart-count" aria-live="polite"><?= html((string) ($cantidadCarrito ?? 0)) ?></span>
      </a>
    </nav>
  </div>
</header>

<main class="carrito-page">
  <?php $cantidadTotal = (int) array_sum(array_column($items, 'cantidad')); ?>
  <?= $this->fetch("carrito/pasos.php", ["pasoActual" => 1]) ?>

  <div class="carrito-heading">
    <div>
      <p class="carrito-eyebrow">Tu selección</p>
      <h1>Tu carrito</h1>
      <p class="carrito-intro">
        <?= empty($items) ? 'Todavía no agregaste productos.' : 'Revisá tus productos antes de continuar.' ?>
      </p>
    </div>
    <?php if (!empty($items)): ?>
      <span class="carrito-count"><?= $cantidadTotal ?> <?= $cantidadTotal === 1 ? 'producto' : 'productos' ?></span>
    <?php endif; ?>
  </div>

  <?php if (empty($items)): ?>
    <section class="carrito-empty">
      <div class="carrito-empty-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
      </div>
      <h2>Tu carrito está vacío</h2>
      <p>Explorá el catálogo y encontrá algo especial hecho por emprendedores locales.</p>
      <a href="/" class="carrito-boton">Explorar productos</a>
    </section>
  <?php else: ?>
    <div class="carrito-grid">
      <section class="carrito-items" aria-label="Productos en tu carrito">
        <?php foreach ($items as $item): ?>
          <?php
          $producto = $item['producto'];
          $imagen = $producto['images'][0] ?? null;
          $imagenSrc = is_array($imagen) ? (string) ($imagen['src'] ?? '') : '';
          $imagenAlt = is_array($imagen) ? (string) ($imagen['alt'] ?? $producto['nombre']) : (string) $producto['nombre'];
          ?>
          <article class="carrito-item">
            <?php if ($imagenSrc !== ''): ?>
              <img class="carrito-imagen" src="<?= html($imagenSrc) ?>" alt="<?= html($imagenAlt) ?>" />
            <?php else: ?>
              <div class="carrito-imagen carrito-imagen-vacia" aria-hidden="true">
                <?= html(strtoupper(substr((string) $producto['nombre'], 0, 1))) ?>
              </div>
            <?php endif; ?>

            <div class="carrito-producto">
              <?php if (!empty($producto['seller'])): ?>
                <p class="carrito-vendedor"><?= html((string) $producto['seller']) ?></p>
              <?php endif; ?>
              <h2><?= html((string) $producto['nombre']) ?></h2>
              <div class="carrito-cantidad-control" aria-label="Cambiar cantidad">
                <form method="post" action="/carrito/<?= rawurlencode((string) ($producto['cartId'] ?? $producto['id'])) ?>/cantidad">
                  <button
                    class="carrito-cantidad-btn"
                    type="submit"
                    name="accion"
                    value="restar"
                    aria-label="Disminuir cantidad de <?= html((string) $producto['nombre']) ?>"
                  >−</button>
                </form>
                <output class="carrito-cantidad" aria-label="Cantidad"><?= (int) $item['cantidad'] ?></output>
                <form method="post" action="/carrito/<?= rawurlencode((string) ($producto['cartId'] ?? $producto['id'])) ?>/cantidad">
                  <button
                    class="carrito-cantidad-btn"
                    type="submit"
                    name="accion"
                    value="sumar"
                    aria-label="Aumentar cantidad de <?= html((string) $producto['nombre']) ?>"
                  >+</button>
                </form>
              </div>
            </div>

            <div class="carrito-precios">
              <p class="carrito-precio-unitario">$<?= number_format((float) $producto['precio'], 0, ',', '.') ?> c/u</p>
              <p class="carrito-subtotal">$<?= number_format((float) $item['subtotal'], 0, ',', '.') ?></p>
            </div>
          </article>
        <?php endforeach; ?>

        <a href="/" class="carrito-seguir">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
          Seguir comprando
        </a>
      </section>

      <aside class="carrito-resumen" aria-labelledby="carrito-resumen-titulo">
        <h2 id="carrito-resumen-titulo">Resumen del pedido</h2>
        <div class="carrito-resumen-linea">
          <span>Productos (<?= $cantidadTotal ?>)</span>
          <span>$<?= number_format((float) $total, 0, ',', '.') ?></span>
        </div>
        <div class="carrito-resumen-linea carrito-envio">
          <span>Envío</span>
          <span>A coordinar</span>
        </div>
        <div class="carrito-total">
          <span>Total</span>
          <strong>$<?= number_format((float) $total, 0, ',', '.') ?></strong>
        </div>
        <p class="carrito-nota">El total no incluye el costo de envío.</p>
        <a href="/carrito/pago" class="carrito-boton carrito-boton-secundario">Realizar compra</a>
        <p class="carrito-seguro">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.7 3.2 7.7 7.5 9.5 4.3-1.8 7.5-4.8 7.5-9.5V6L12 3Z"/><path d="m9 12 2 2 4-4"/></svg>
          Compra protegida
        </p>
      </aside>
    </div>
  <?php endif; ?>
</main>

<?= $this->fetch("layouts/footer.php", ["usuarioAutenticado" => $usuarioAutenticado ?? false]) ?>
