<header class="site-header">
  <div class="container header-main">
    <a href="/" class="logo" aria-label="Emprendi, inicio">
      <span class="logo-mark" aria-hidden="true">E</span>
      <span class="logo-text">Emprendi</span>
    </a>
    <form class="search" role="search" id="search-form" method="get" action="/buscar">
      <label for="site-search" class="sr-only">Buscar productos por nombre</label>
      <input id="site-search" type="search" name="q" value="<?= html($terminoBusqueda) ?>" placeholder="Buscar productos, marcas y emprendimientos..." autocomplete="off" required />
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
        <span class="cart-count" id="cart-count" aria-live="polite"><?= html((string) $cantidadCarrito) ?></span>
      </a>
    </nav>
  </div>
</header>

<main class="container products-section">
  <div class="section-head">
    <div>
      <h1>Resultados de búsqueda</h1>
      <?php if ($terminoBusqueda !== ''): ?>
        <p>Productos cuyo nombre coincide con “<?= html($terminoBusqueda) ?>”.</p>
      <?php else: ?>
        <p>Escribí un nombre para buscar productos.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($productos === []): ?>
    <div class="empty-state">
      <?php if ($terminoBusqueda === ''): ?>
        Ingresá un texto en la barra de búsqueda para ver resultados.
      <?php else: ?>
        No encontramos productos cuyo nombre coincida con “<?= html($terminoBusqueda) ?>”.
      <?php endif; ?>
    </div>
  <?php else: ?>
    <p class="results-note" aria-live="polite">
      <?= count($productos) ?> <?= count($productos) === 1 ? 'producto encontrado' : 'productos encontrados' ?>
    </p>
    <div class="product-grid">
      <?php foreach ($productos as $producto): ?>
        <?php
        $nombre = (string) $producto['nombre'];
        $imagen = $producto['images'][0] ?? null;
        $imagenSrc = is_array($imagen) ? (string) ($imagen['src'] ?? '') : '';
        $imagenAlt = is_array($imagen) ? (string) ($imagen['alt'] ?? $nombre) : $nombre;
        $precio = (float) $producto['precio'];
        $precioAnterior = isset($producto['originalPrice']) ? (float) $producto['originalPrice'] : null;
        $descuento = $precioAnterior !== null && $precioAnterior > $precio
          ? (int) round((1 - $precio / $precioAnterior) * 100)
          : 0;
        $idProducto = (string) $producto['id'];
        $urlProducto = (string) ($producto['url'] ?? '/productos/' . rawurlencode($idProducto));
        $idCarrito = (string) ($producto['cartId'] ?? $idProducto);
        ?>
        <article class="card">
          <a class="product-detail-link" href="<?= html($urlProducto) ?>" aria-label="Ver detalles de <?= html($nombre) ?>">
            <div class="media">
              <?php if ($imagenSrc !== ''): ?>
                <img src="<?= html($imagenSrc) ?>" alt="<?= html($imagenAlt) ?>" loading="lazy" />
              <?php else: ?>
                <span class="media-placeholder" aria-hidden="true"><?= html(strtoupper(substr($nombre, 0, 1))) ?></span>
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
            <p class="seller"><?= html((string) $producto['seller']) ?></p>
            <h2 class="card-title"><a href="<?= html($urlProducto) ?>"><?= html($nombre) ?></a></h2>
            <?php if (isset($producto['rating'])): ?>
              <div class="rating">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3-4.6-4.5 6.3-.9z"/></svg>
                <strong><?= html((string) $producto['rating']) ?></strong>
                <?php if (isset($producto['reviews'])): ?><span>(<?= html((string) $producto['reviews']) ?>)</span><?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if ($precioAnterior !== null && $precioAnterior > $precio): ?>
              <p class="price-old">$<?= html(number_format($precioAnterior, 0, ',', '.')) ?></p>
            <?php endif; ?>
            <div class="price-row">
              <p class="price">$<?= html(number_format($precio, 0, ',', '.')) ?></p>
              <?php if ($descuento > 0): ?><span class="discount"><?= html((string) $descuento) ?>% OFF</span><?php endif; ?>
            </div>
            <?php if (!empty($producto['freeShipping'])): ?><p class="shipping">Envío gratis</p><?php endif; ?>
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
    </div>
  <?php endif; ?>
</main>

<?= $this->fetch("layouts/footer.php", ["usuarioAutenticado" => $usuarioAutenticado]) ?>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="/productos.js" defer></script>
