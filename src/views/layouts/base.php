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
                    <a class="home-link" href="/">La feria</a>
                    <nav class="site-actions desktop-actions" aria-label="Acciones principales">
                        <a class="nav-link" href="/emprendimientos">Emprendimientos</a>
                        <a class="nav-link cart-link" href="/carrito">Carrito</a>
                        <?php if ($currentUser !== null): ?>
                            <a class="nav-link nav-link-primary" href="/mi-puesto"><?= $currentUser['rol'] === 'emprendedor' ? 'Mi puesto' : 'Quiero emprender' ?></a>
                            <details class="account-menu">
                                <summary>Mi cuenta</summary>
                                <div class="account-menu-panel">
                                    <span class="menu-greeting">Hola, <?= html($currentUser['nombre']) ?></span>
                                    <?php if ($currentUser['rol'] === 'emprendedor'): ?>
                                        <a href="/mi-puesto">Panel de mi puesto</a>
                                        <a href="/productos/create">Publicar producto</a>
                                        <a href="/conversaciones">Mensajes</a>
                                        <a href="/ventas">Ventas recibidas</a>
                                    <?php else: ?>
                                        <a href="/mi-puesto">Abrir mi puesto</a>
                                        <a href="/emprendimientos/seguidos">Emprendimientos que sigo</a>
                                    <?php endif; ?>
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
                            <a class="nav-link nav-link-primary" href="/usuarios/register">Crear cuenta</a>
                        <?php endif; ?>
                    </nav>
                    <details class="mobile-navigation">
                        <summary aria-label="Abrir navegación">Menú</summary>
                        <nav class="mobile-navigation-panel" aria-label="Navegación">
                            <a href="/">Recorrer la feria</a>
                            <a href="/emprendimientos">Emprendimientos</a>
                            <a href="/carrito">Carrito</a>
                            <?php if ($currentUser !== null): ?>
                                <span class="menu-greeting">Hola, <?= html($currentUser['nombre']) ?></span>
                                <a href="/mi-puesto"><?= $currentUser['rol'] === 'emprendedor' ? 'Mi puesto' : 'Quiero emprender' ?></a>
                                <?php if ($currentUser['rol'] === 'emprendedor'): ?>
                                    <a href="/productos/create">Publicar producto</a>
                                    <a href="/conversaciones">Mensajes</a>
                                    <a href="/ventas">Ventas recibidas</a>
                                <?php else: ?>
                                    <a href="/emprendimientos/seguidos">Emprendimientos que sigo</a>
                                <?php endif; ?>
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
                                <a href="/usuarios/register">Crear cuenta</a>
                            <?php endif; ?>
                            <a href="/ayuda">Ayuda</a>
                            <a href="/nosotros">Nosotros</a>
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
