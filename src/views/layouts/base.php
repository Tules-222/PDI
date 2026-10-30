<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= html($pageTitle ?? $title ?? 'Emprendi') ?></title>
        <meta name="description" content="<?= html($metaDescription ?? 'Emprendi conecta emprendedores con productos y proveedores para sus negocios.') ?>">
        <?php if (!empty($ogImage)): ?><meta property="og:image" content="<?= html((string) $ogImage) ?>"><?php endif; ?>
        <?php if (!empty($stylesheet)): ?>
            <link rel="stylesheet" href="<?= html((string) $stylesheet) ?>" />
        <?php endif; ?>
    </head>
    <body>
        <header class="site-header">
            <div class="site-header-inner">
                <a class="site-logo" href="/" aria-label="Emprendi, ir al inicio">
                    <img src="/img/emprendi.png" alt="Emprendi">
                </a>
                <?php if (!empty($adminPanel)): ?>
                    <span class="admin-header-label">Panel de administración</span>
                    <a class="home-link" href="/">Volver al sitio</a>
                <?php else: ?>
                    <a class="home-link" href="/">Inicio</a>
                    <nav class="site-actions desktop-actions" aria-label="Acciones principales">
                        <a class="nav-link" href="/productos/create">Publicar producto</a>
                        <a class="nav-link cart-link" href="/carrito">Carrito</a>
                        <a class="nav-link" href="/nosotros">Nosotros</a>
                        <a class="nav-link" href="/ayuda">Ayuda</a>
                        <a class="nav-link" href="/emprendimientos">Emprendimientos</a>
                        <?php if ($currentUser !== null): ?>
                            <a class="nav-link" href="/conversaciones">Mensajes</a>
                            <details class="account-menu">
                                <summary>Mi cuenta</summary>
                                <div class="account-menu-panel">
                                    <span class="menu-greeting">Hola, <?= html($currentUser['nombre']) ?></span>
                                    <a href="/mis-emprendimientos">Mis emprendimientos</a>
                                    <a href="/emprendimientos/seguidos">Emprendimientos que sigo</a>
                                    <a href="/mis-productos">Ver mis productos</a>
                                    <a href="/ventas">Ventas recibidas</a>
                                    <a href="/mi-rendimiento">Rendimiento</a>
                                    <a href="/mis-compras">Ver mis compras</a>
                                    <a href="/favoritos">Mis favoritos</a>
                                    <a href="/notificaciones">Notificaciones</a>
                                    <a href="/perfil">Mi perfil</a>
                                    <form action="/usuarios/logout" method="post">
                                        <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
                                        <button type="submit">Cerrar sesión</button>
                                    </form>
                                </div>
                            </details>
                        <?php else: ?>
                            <a class="nav-link" href="/usuarios/login">Ingresar</a>
                            <a class="nav-link nav-link-primary" href="/usuarios/register">Registrarme</a>
                        <?php endif; ?>
                    </nav>
                    <details class="mobile-navigation">
                        <summary aria-label="Abrir navegación">Menú</summary>
                        <nav class="mobile-navigation-panel" aria-label="Navegación">
                            <a href="/productos/create">Publicar producto</a>
                            <a href="/carrito">Carrito</a>
                            <a href="/nosotros">Nosotros</a>
                            <a href="/ayuda">Ayuda</a>
                            <a href="/emprendimientos">Emprendimientos</a>
                            <?php if ($currentUser !== null): ?>
                                <a href="/conversaciones">Mensajes</a>
                                <span class="menu-greeting">Hola, <?= html($currentUser['nombre']) ?></span>
                                <a href="/mis-emprendimientos">Mis emprendimientos</a>
                                <a href="/emprendimientos/seguidos">Emprendimientos que sigo</a>
                                <a href="/mis-productos">Ver mis productos</a>
                                <a href="/ventas">Ventas recibidas</a>
                                <a href="/mi-rendimiento">Rendimiento</a>
                                <a href="/mis-compras">Ver mis compras</a>
                                <a href="/favoritos">Mis favoritos</a>
                                <a href="/notificaciones">Notificaciones</a>
                                <a href="/perfil">Mi perfil</a>
                                <form action="/usuarios/logout" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
                                    <button type="submit">Cerrar sesión</button>
                                </form>
                            <?php else: ?>
                                <a href="/usuarios/login">Ingresar</a>
                                <a href="/usuarios/register">Registrarme</a>
                            <?php endif; ?>
                        </nav>
                    </details>
                <?php endif; ?>
            </div>
        </header>
        <?php if (is_string($flash) && $flash !== ''): ?>
            <p class="flash-message" role="status"><?= html($flash) ?></p>
        <?php endif; ?>
        <?= $content ?>
    </body>
</html>
