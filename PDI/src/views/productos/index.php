<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#14213d" />
  <title>Emprendi | El marketplace de los nuevos emprendedores</title>
  <meta name="description" content="Emprendi ayuda a emprendedores nuevos a vender sus productos en línea. Descubre productos únicos, ofertas y lo más vendido." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
  <script src="/site-theme.js"></script>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="/site-theme.css" />
</head>
<body>
  <a class="skip-link" href="#productos">Saltar al catálogo</a>

  <!-- HEADER -->
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
          <a href="/auth/login" class="nav-link">
            <svg class="icon mobile-only" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
            <span class="label-login">Iniciar sesión</span>
          </a>
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

    <div class="header-sub">
      <div class="container header-sub-inner">
        <span class="ship-note">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
          Envíos a todo el país
        </span>
        <nav class="categories" aria-label="Categorías" id="categories"></nav>
        <a href="#vender" class="sell-link">Vende en Emprendi</a>
      </div>
    </div>
  </header>

  <main>
    <!-- HERO -->
    <section class="hero" aria-labelledby="hero-title">
      <div class="container hero-content">
        <span class="hero-pill">Hecho por emprendedores, para ti</span>
        <h1 id="hero-title">Cada compra impulsa un sueño.</h1>
        <p>Descubre productos únicos de emprendimientos locales y apoya a quienes están empezando. Compra seguro, recibe en casa.</p>
        <div class="hero-actions">
          <a href="#productos" class="btn btn-accent">
            Explorar productos
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="#vender" class="btn btn-ghost">Quiero vender</a>
        </div>
      </div>
    </section>

    <!-- BENEFICIOS -->
    <section class="container benefits-wrap" aria-label="Beneficios">
      <ul class="benefits">
        <li>
          <span class="benefit-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg></span>
          <div><p class="benefit-title">Envíos a todo el país</p><p class="benefit-text">Gratis en productos seleccionados</p></div>
        </li>
        <li>
          <span class="benefit-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg></span>
          <div><p class="benefit-title">Paga como prefieras</p><p class="benefit-text">Tarjeta, transferencia o efectivo</p></div>
        </li>
        <li>
          <span class="benefit-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg></span>
          <div><p class="benefit-title">Compra protegida</p><p class="benefit-text">Te devolvemos tu dinero si algo falla</p></div>
        </li>
        <li>
          <span class="benefit-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21V11M12 11c0-4 3-6 7-6 0 4-3 6-7 6zM12 14c0-3-2.5-5-6-5 0 3 2.5 5 6 5z"/></svg></span>
          <div><p class="benefit-title">Apoyo local</p><p class="benefit-text">Más de 2.000 emprendedores activos</p></div>
        </li>
      </ul>
    </section>

    <!-- PRODUCTOS -->
    <section id="productos" class="container products-section" aria-labelledby="productos-title">
      <div class="section-head">
        <div>
          <h2 id="productos-title">Catálogo destacado</h2>
          <p>Productos seleccionados de emprendedores que están creciendo con Emprendi.</p>
        </div>
        <div class="filters" role="tablist" aria-label="Filtrar productos">
          <button type="button" role="tab" class="filter active" aria-selected="true" data-filter="all">Todos</button>
          <button type="button" role="tab" class="filter" aria-selected="false" data-filter="offers">Ofertas</button>
          <button type="button" role="tab" class="filter" aria-selected="false" data-filter="best">Más vendidos</button>
        </div>
      </div>
      <p class="results-note" id="results-note" aria-live="polite"></p>
      <div class="product-grid" id="product-grid">
        <?php if ($productos === []): ?>
          <div class="empty-state">Todavía no hay productos disponibles.</div>
        <?php else: ?>
          <?php foreach ($productos as $producto): ?>
            <?php
            $nombre = (string) ($producto['nombre'] ?? '');
            $categoria = (string) ($producto['categoria'] ?? '');
            $vendedor = (string) ($producto['seller'] ?? $categoria);
            $imagen = $producto['images'][0] ?? null;
            $imagenSrc = is_array($imagen) ? (string) ($imagen['src'] ?? '') : (string) ($producto['imagen'] ?? '');
            $imagenAlt = is_array($imagen) ? (string) ($imagen['alt'] ?? $nombre) : $nombre;
            $precio = (float) ($producto['precio'] ?? $producto['price'] ?? 0);
            $precioAnterior = isset($producto['originalPrice']) ? (float) $producto['originalPrice'] : null;
            $descuento = $precioAnterior !== null && $precioAnterior > $precio
              ? (int) round((1 - $precio / $precioAnterior) * 100)
              : 0;
            $idProducto = (string) ($producto['id'] ?? '');
            $urlProducto = (string) ($producto['url'] ?? '/productos/' . rawurlencode($idProducto));
            $idCarrito = (string) ($producto['cartId'] ?? $idProducto);
            ?>
            <article
              class="card"
              data-offer="<?= $descuento > 0 ? 'true' : 'false' ?>"
              data-best="<?= !empty($producto['bestSeller']) ? 'true' : 'false' ?>"
            >
              <a class="product-detail-link" href="<?= html($urlProducto) ?>" aria-label="Ver detalles de <?= html($nombre) ?>">
                <div class="media">
                  <?php if ($imagenSrc !== ''): ?>
                    <img src="<?= html($imagenSrc) ?>" alt="<?= html($imagenAlt) ?>" />
                  <?php else: ?>
                    <span class="media-placeholder" aria-hidden="true"><?= html(strtoupper(substr($categoria !== '' ? $categoria : $nombre, 0, 1))) ?></span>
                  <?php endif; ?>

                  <div class="badges">
                    <?php if (!empty($producto['bestSeller'])): ?>
                      <span class="badge badge-best">Más vendido</span>
                    <?php endif; ?>
                    <?php if ($descuento > 0): ?>
                      <span class="badge badge-offer">Oferta</span>
                    <?php endif; ?>
                  </div>
                </div>
              </a>

              <div class="card-body">
                <?php if ($vendedor !== ''): ?>
                  <p class="seller"><?= html($vendedor) ?></p>
                <?php endif; ?>
                <h3 class="card-title"><a href="<?= html($urlProducto) ?>"><?= html($nombre) ?></a></h3>

                <?php if (isset($producto['rating'])): ?>
                  <div class="rating">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3-4.6-4.5 6.3-.9z"/></svg>
                    <strong><?= html((string) $producto['rating']) ?></strong>
                    <?php if (isset($producto['reviews'])): ?>
                      <span>(<?= html((string) $producto['reviews']) ?>)</span>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>

                <div class="price-block">
                  <?php if ($precioAnterior !== null && $precioAnterior > $precio): ?>
                    <p class="price-old"><span class="sr-only">Precio anterior: </span>$<?= html(number_format($precioAnterior, 0, ',', '.')) ?></p>
                  <?php endif; ?>
                  <div class="price-row">
                    <p class="price"><span class="sr-only">Precio: </span>$<?= html(number_format($precio, 0, ',', '.')) ?></p>
                    <?php if ($descuento > 0): ?>
                      <span class="discount"><?= html((string) $descuento) ?>% OFF</span>
                    <?php endif; ?>
                  </div>
                  <?php if (!empty($producto['freeShipping'])): ?>
                    <p class="shipping">Envío gratis</p>
                  <?php elseif (!empty($producto['minimo'])): ?>
                    <p class="product-note"><?= html((string) $producto['minimo']) ?></p>
                  <?php endif; ?>
                </div>

                <form method="post" action="/carrito/agregar" data-add-to-cart>
                  <input type="hidden" name="producto_id" value="<?= html($idCarrito) ?>" />
                  <button class="add-btn" type="submit">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
                    Agregar al carrito
                  </button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <!-- VENDER -->
    <section id="vender" class="container sell-section" aria-labelledby="vender-title">
      <div class="sell-card">
        <div>
          <h2 id="vender-title">¿Tienes un emprendimiento? Llévalo al siguiente nivel.</h2>
          <p>Miles de compradores buscan productos como los tuyos. Sin costos de inicio, solo pagas cuando vendes.</p>
          <a href="#" class="btn btn-accent">
            Empieza a vender gratis
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        </div>
        <ol class="steps">
          <li><span class="step-n">1</span><div><p class="step-title">Crea tu cuenta</p><p class="step-text">Regístrate gratis en minutos.</p></div></li>
          <li><span class="step-n">2</span><div><p class="step-title">Publica tus productos</p><p class="step-text">Sube fotos, precio y descripción.</p></div></li>
          <li><span class="step-n">3</span><div><p class="step-title">Empieza a vender</p><p class="step-text">Nosotros te ayudamos con pagos y envíos.</p></div></li>
        </ol>
      </div>
    </section>
  </main>

  <?= $this->fetch("layouts/footer.php", ["usuarioAutenticado" => $usuarioAutenticado ?? false]) ?>

  <!-- CARRITO -->
  <div class="overlay" id="cart-overlay" hidden></div>
  <aside class="cart-drawer" id="cart-drawer" role="dialog" aria-modal="true" aria-labelledby="cart-title" aria-hidden="true">
    <div class="cart-head">
      <h2 id="cart-title">Tu carrito</h2>
      <button type="button" class="icon-btn" id="cart-close" aria-label="Cerrar carrito">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
      </button>
    </div>
    <ul class="cart-items" id="cart-items"></ul>
    <div class="cart-foot">
      <div class="cart-total"><span>Total</span><strong id="cart-total">$ 0</strong></div>
      <button type="button" class="btn btn-primary btn-block" id="checkout-btn">Continuar compra</button>
    </div>
  </aside>

  <div class="toast" id="toast" role="status" aria-live="polite"></div>

  <script src="/productos.js" defer></script>
</body>
</html>
