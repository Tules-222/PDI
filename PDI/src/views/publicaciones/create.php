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
      <a href="/cuenta" class="nav-link account-link"><span>Mi cuenta</span></a>
      <a href="/carrito" class="cart-btn" aria-label="Ir al carrito">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
        <span class="cart-label">Carrito</span>
        <span class="cart-count" id="cart-count" aria-live="polite"><?= html((string) $cantidadCarrito) ?></span>
      </a>
    </nav>
  </div>
</header>

<main class="container account-page">
  <a class="product-detail-back" href="/cuenta">Volver a mi cuenta</a>
  <section class="account-panel publication-form-panel">
    <div class="account-panel-heading">
      <div>
        <p class="product-detail-category">Compartí tu emprendimiento</p>
        <h1>Crear publicación</h1>
        <p>Completá la información del producto para publicarlo en el catálogo.</p>
      </div>
    </div>

    <?php if (!empty($errors)): ?>
      <ul class="register-errors" role="alert">
        <?php foreach ($errors as $error): ?>
          <li><?= html((string) $error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form class="publication-form" method="post" action="/publicaciones" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= html((string) $csrfToken) ?>">
      <label for="publication-name">Nombre del producto</label>
      <input id="publication-name" type="text" name="nombre" maxlength="100" value="<?= html((string) ($data['nombre'] ?? '')) ?>" required>

      <div class="publication-form-row">
        <div>
          <label for="publication-price">Precio</label>
          <input id="publication-price" type="number" name="precio" min="0.01" step="0.01" value="<?= html((string) ($data['precio'] ?? '')) ?>" required>
        </div>
        <div>
          <label for="publication-stock">Unidades disponibles</label>
          <input id="publication-stock" type="number" name="stock" min="1" step="1" value="<?= html((string) ($data['stock'] ?? '')) ?>" required>
        </div>
      </div>

      <label for="publication-image">Imagen del producto</label>
      <input id="publication-image" type="file" name="imagen" accept="image/jpeg,image/png,image/webp">
      <p class="publication-form-note">Opcional. Formatos JPG, PNG o WebP; tamaño máximo: 5 MB.</p>

      <p class="publication-form-note">Al publicarlo, el producto aparecerá en el catálogo y quedará asociado a tu cuenta.</p>
      <button type="submit" class="btn btn-primary">Publicar producto</button>
    </form>
  </section>
</main>

<?= $this->fetch("layouts/footer.php", ["usuarioAutenticado" => true]) ?>
