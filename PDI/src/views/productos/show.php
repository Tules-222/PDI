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
        <span class="cart-count" id="cart-count" aria-live="polite"><?= html((string) $cantidadCarrito) ?></span>
      </a>
    </nav>
  </div>
</header>

<main class="container product-detail-page">
  <nav class="product-breadcrumb" aria-label="Migas de pan">
    <a href="/">Inicio</a>
    <span aria-hidden="true">/</span>
    <a href="/#productos">Productos</a>
    <span aria-hidden="true">/</span>
    <span aria-current="page"><?= html((string) $producto['nombre']) ?></span>
  </nav>

  <?php
  $precio = (float) $producto['precio'];
  $precioAnterior = isset($producto['originalPrice']) ? (float) $producto['originalPrice'] : null;
  $descuento = $precioAnterior !== null && $precioAnterior > $precio
      ? (int) round((1 - $precio / $precioAnterior) * 100)
      : 0;
  ?>
  <article class="product-detail">
    <div class="product-detail-gallery" aria-label="Fotos del producto">
      <?php foreach (($producto['images'] ?? []) as $index => $image): ?>
        <figure class="<?= $index === 0 ? 'product-detail-image-main' : 'product-detail-image-extra' ?>">
          <img src="<?= html((string) $image['src']) ?>" alt="<?= html((string) $image['alt']) ?>" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
        </figure>
      <?php endforeach; ?>
      <?php if (empty($producto['images'])): ?>
        <div class="product-detail-image-main media-placeholder" aria-label="Sin imagen disponible"><?= html(strtoupper(substr((string) $producto['nombre'], 0, 1))) ?></div>
      <?php endif; ?>
    </div>

    <div class="product-detail-info">
      <p class="product-detail-category"><?= html((string) $producto['categoria']) ?></p>
      <?php if (!empty($producto['bestSeller']) || $descuento > 0): ?>
        <div class="product-detail-badges">
          <?php if (!empty($producto['bestSeller'])): ?><span class="badge badge-best">Más vendido</span><?php endif; ?>
          <?php if ($descuento > 0): ?><span class="badge badge-offer">Oferta</span><?php endif; ?>
        </div>
      <?php endif; ?>

      <h1><?= html((string) $producto['nombre']) ?></h1>
      <p class="product-detail-seller">Vendido por <strong><?= html((string) $producto['seller']) ?></strong></p>

      <?php if (isset($producto['rating'])): ?>
        <div class="rating product-detail-rating">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3-4.6-4.5 6.3-.9z"/></svg>
          <strong><?= html((string) $producto['rating']) ?></strong>
          <span>(<?= html((string) $producto['reviews']) ?> reseñas)</span>
        </div>
      <?php endif; ?>

      <div class="product-detail-price">
        <?php if ($precioAnterior !== null && $precioAnterior > $precio): ?>
          <p class="price-old"><span class="sr-only">Precio anterior: </span>$<?= html(number_format($precioAnterior, 0, ',', '.')) ?></p>
        <?php endif; ?>
        <div class="price-row">
          <p class="price"><span class="sr-only">Precio: </span>$<?= html(number_format($precio, 0, ',', '.')) ?></p>
          <?php if ($descuento > 0): ?><span class="discount"><?= html((string) $descuento) ?>% OFF</span><?php endif; ?>
        </div>
        <?php if (!empty($producto['freeShipping'])): ?><p class="shipping">Envío gratis</p><?php endif; ?>
      </div>

      <p class="product-detail-description"><?= html((string) $producto['descripcion']) ?></p>

      <section class="product-detail-features" aria-labelledby="product-features-title">
        <h2 id="product-features-title">Características</h2>
        <ul>
          <?php foreach ($producto['caracteristicas'] as $caracteristica): ?>
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
              <?= html((string) $caracteristica) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>

      <form method="post" action="/carrito/agregar" data-add-to-cart class="product-detail-cart-form">
        <input type="hidden" name="producto_id" value="<?= html((string) ($producto['cartId'] ?? $producto['id'])) ?>">
        <button class="add-btn" type="submit">
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
          Agregar al carrito
        </button>
      </form>

      <a href="/#productos" class="product-detail-back">Volver al catálogo</a>
    </div>
  </article>

  <?php if (!empty($comentariosHabilitados)): ?>
  <section class="product-comments-section" id="comentarios" aria-labelledby="product-comments-title">
    <div class="product-section-heading">
      <div>
        <p class="product-detail-category">Experiencias reales</p>
        <h2 id="product-comments-title">Comentarios</h2>
      </div>
      <span class="product-comments-count"><?= count($comentarios) ?> <?= count($comentarios) === 1 ? 'comentario' : 'comentarios' ?></span>
    </div>

    <?php if ($comentarioPublicado): ?>
      <p class="comment-success" role="status">Tu comentario se publicó correctamente.</p>
    <?php endif; ?>

    <?php if (!empty($erroresComentario)): ?>
      <ul class="comment-errors" role="alert">
        <?php foreach ($erroresComentario as $error): ?>
          <li><?= html((string) $error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (!empty($comentariosHabilitados) && $usuarioAutenticado): ?>
      <form class="comment-form" method="post" action="<?= html((string) $commentActionPath) ?>">
        <input type="hidden" name="csrf_token" value="<?= html((string) $csrfToken) ?>">
        <h3>Dejá tu comentario</h3>
        <label for="comment-rating">Tu calificación</label>
        <select id="comment-rating" name="calificacion" required>
          <option value="">Elegí una calificación</option>
          <?php for ($rating = 5; $rating >= 1; $rating--): ?>
            <option value="<?= $rating ?>" <?= (string) ($datosComentario['calificacion'] ?? '') === (string) $rating ? 'selected' : '' ?>>
              <?= $rating ?> <?= $rating === 1 ? 'estrella' : 'estrellas' ?>
            </option>
          <?php endfor; ?>
        </select>
        <label for="comment-text">Comentario</label>
        <textarea id="comment-text" name="comentario" rows="4" maxlength="1000" required><?= html((string) ($datosComentario['comentario'] ?? '')) ?></textarea>
        <div class="comment-form-footer">
          <p>Máximo 1000 caracteres.</p>
          <button type="submit" class="btn btn-primary">Publicar comentario</button>
        </div>
      </form>
    <?php elseif (!empty($comentariosHabilitados)): ?>
      <div class="comment-login-prompt">
        <p>Iniciá sesión para compartir tu experiencia con este producto.</p>
        <a href="/auth/login">Iniciar sesión</a>
      </div>
    <?php endif; ?>

    <?php if ($comentarios === []): ?>
      <div class="comments-empty">
        <h3>Aún no hay comentarios</h3>
        <p>Sé la primera persona en compartir su experiencia con este producto.</p>
      </div>
    <?php else: ?>
      <div class="product-comments-list">
        <?php foreach ($comentarios as $comentario): ?>
          <article class="product-comment">
            <div class="product-comment-heading">
              <div>
                <h3><?= html((string) $comentario['autor']) ?></h3>
                <time datetime="<?= html((string) $comentario['creado_en']) ?>"><?= html(date('d/m/Y', strtotime((string) $comentario['creado_en']))) ?></time>
              </div>
              <span class="comment-rating" aria-label="<?= (int) $comentario['calificacion'] ?> de 5 estrellas">
                <?= str_repeat('★', (int) $comentario['calificacion']) ?><span><?= str_repeat('☆', 5 - (int) $comentario['calificacion']) ?></span>
              </span>
            </div>
            <p><?= nl2br(html((string) $comentario['comentario'])) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <?php if ($productosRecomendados !== []): ?>
    <section class="recommended-section" aria-labelledby="recommended-title">
      <div class="product-section-heading">
        <div>
          <p class="product-detail-category">Descubrí algo nuevo</p>
          <h2 id="recommended-title">Productos recomendados</h2>
        </div>
      </div>
      <div class="product-grid recommended-grid">
        <?php foreach ($productosRecomendados as $recomendado): ?>
          <?php
          $imagenRecomendado = $recomendado['images'][0] ?? null;
          $srcRecomendado = is_array($imagenRecomendado) ? (string) ($imagenRecomendado['src'] ?? '') : '';
          $altRecomendado = is_array($imagenRecomendado) ? (string) ($imagenRecomendado['alt'] ?? $recomendado['nombre']) : (string) $recomendado['nombre'];
          $precioOriginalRecomendado = isset($recomendado['originalPrice']) ? (float) $recomendado['originalPrice'] : null;
          $urlRecomendado = (string) ($recomendado['url'] ?? '/productos/' . rawurlencode((string) $recomendado['id']));
          ?>
          <article class="card recommended-card">
            <a class="product-detail-link" href="<?= html($urlRecomendado) ?>">
              <div class="media">
                <?php if ($srcRecomendado !== ''): ?>
                  <img src="<?= html($srcRecomendado) ?>" alt="<?= html($altRecomendado) ?>" loading="lazy">
                <?php else: ?>
                  <span class="media-placeholder" aria-hidden="true"><?= html(strtoupper(substr((string) $recomendado['nombre'], 0, 1))) ?></span>
                <?php endif; ?>
              </div>
            </a>
            <div class="card-body">
              <p class="seller"><?= html((string) $recomendado['seller']) ?></p>
              <h3 class="card-title"><a href="<?= html($urlRecomendado) ?>"><?= html((string) $recomendado['nombre']) ?></a></h3>
              <?php if (isset($recomendado['rating'])): ?>
                <div class="rating">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3-4.6-4.5 6.3-.9z"/></svg>
                  <strong><?= html((string) $recomendado['rating']) ?></strong>
                </div>
              <?php endif; ?>
              <div class="price-row">
                <p class="price">$<?= html(number_format((float) $recomendado['precio'], 0, ',', '.')) ?></p>
                <?php if ($precioOriginalRecomendado !== null && $precioOriginalRecomendado > (float) $recomendado['precio']): ?>
                  <span class="discount">Oferta</span>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</main>

<?= $this->fetch("layouts/footer.php", ["usuarioAutenticado" => $usuarioAutenticado ?? false]) ?>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="/productos.js" defer></script>
