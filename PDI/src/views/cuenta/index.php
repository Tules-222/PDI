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
      <a href="/cuenta" class="nav-link account-link" aria-current="page"><span>Mi cuenta</span></a>
      <a href="/carrito" class="cart-btn" aria-label="Ir al carrito">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.2a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.8L21 7H6"/></svg>
        <span class="cart-label">Carrito</span>
        <span class="cart-count" id="cart-count" aria-live="polite"><?= html((string) $cantidadCarrito) ?></span>
      </a>
    </nav>
  </div>
</header>

<main class="container account-page">
  <div class="account-heading">
    <div>
      <p class="product-detail-category">Tu espacio en Emprendi</p>
      <h1>Mi cuenta</h1>
      <p>Consultá tus datos y administrá tus publicaciones.</p>
    </div>
    <div class="account-actions">
      <button type="button" class="account-theme-button" id="site-theme-toggle" aria-pressed="false">
        Activar modo oscuro
      </button>
      <form method="post" action="/auth/logout">
        <input type="hidden" name="csrf_token" value="<?= html((string) $csrfToken) ?>">
        <button type="submit" class="account-logout-button">Cerrar sesión</button>
      </form>
    </div>
  </div>

  <?php if ($publicacionCreada): ?>
    <p class="account-success" role="status">Tu publicación se creó y ya aparece en el catálogo.</p>
  <?php endif; ?>
  <?php if ($publicacionEliminada): ?>
    <p class="account-success" role="status">La publicación se eliminó correctamente.</p>
  <?php endif; ?>
  <?php if ($errorEliminacion): ?>
    <p class="comment-errors" role="alert">No se pudo eliminar la publicación. Actualizá la página e intentá nuevamente.</p>
  <?php endif; ?>

  <section class="account-panel" aria-labelledby="account-details-title">
    <div class="account-panel-heading">
      <div>
        <p class="product-detail-category">Información personal</p>
        <h2 id="account-details-title">Tus datos</h2>
      </div>
    </div>
    <dl class="account-details">
      <div><dt>Nombre</dt><dd><?= html((string) $usuario['nombre']) ?></dd></div>
      <div><dt>Correo electrónico</dt><dd><?= html((string) $usuario['email']) ?></dd></div>
      <div><dt>Teléfono</dt><dd><?= html((string) $usuario['tel']) ?></dd></div>
    </dl>
  </section>

  <section class="account-panel" aria-labelledby="account-listings-title">
    <div class="account-panel-heading">
      <div>
        <p class="product-detail-category">Tu emprendimiento</p>
        <h2 id="account-listings-title">Mis publicaciones</h2>
      </div>
      <a href="/publicaciones/nueva" class="account-text-link">Crear publicación</a>
    </div>
    <?php if ($publicaciones === []): ?>
      <p class="account-empty">Todavía no creaste publicaciones.</p>
    <?php else: ?>
      <ul class="account-listings">
        <?php foreach ($publicaciones as $publicacion): ?>
          <li>
            <a href="/publicaciones/<?= (int) $publicacion['id'] ?>"><?= html((string) $publicacion['nombre']) ?></a>
            <span>$<?= html(number_format((float) $publicacion['precio'], 0, ',', '.')) ?></span>
            <span><?= (int) $publicacion['stock'] ?> unidades</span>
            <form method="post" action="/publicaciones/<?= (int) $publicacion['id'] ?>/eliminar" onsubmit="return confirm('¿Eliminar esta publicación? Esta acción no se puede deshacer.');">
              <input type="hidden" name="csrf_token" value="<?= html((string) $csrfToken) ?>">
              <button type="submit" class="account-delete-button">Eliminar</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>
</main>

<?= $this->fetch("layouts/footer.php", ["usuarioAutenticado" => true]) ?>
