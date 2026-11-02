<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#14213d">
    <script src="/site-theme.js"></script>
    <link rel="stylesheet" href="/login.css">
    <link rel="stylesheet" href="/site-theme.css">
    <title>Iniciar sesión | Emprendi</title>
</head>
<body>
    <header class="login-header">
        <a href="/" class="logo" aria-label="Emprendi, ir al catálogo">
            <span class="logo-mark" aria-hidden="true">E</span>
            <span>Emprendi</span>
        </a>
        <a href="/" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
            Volver al catálogo
        </a>
    </header>

    <main class="login-container">
        <section class="login-shell" aria-label="Acceso a tu cuenta">
            <div class="welcome-panel">
                <span class="welcome-eyebrow">Tu comunidad emprendedora</span>
                <div class="welcome-copy">
                    <h2>Qué bueno tenerte de vuelta.</h2>
                    <p>Ingresá a tu cuenta para descubrir productos únicos y seguir apoyando emprendimientos locales.</p>
                </div>
                <div class="welcome-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.7 3.2 7.7 7.5 9.5 4.3-1.8 7.5-4.8 7.5-9.5V6L12 3Z"/><path d="m9 12 2 2 4-4"/></svg>
                    <span>Un espacio seguro para tus compras.</span>
                </div>
            </div>

            <div class="login-card">
                <h1>Iniciar sesión</h1>
                <p class="login-intro">Ingresá tus datos para acceder a tu cuenta.</p>

                <?php if (!empty($errors)): ?>
                    <ul class="login-errors" role="alert" aria-label="No se pudo iniciar sesión">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars((string) $error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form action="/auth/login" method="POST">
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars((string) ($data['email'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                            placeholder="nombre@ejemplo.com"
                            autocomplete="email"
                            inputmode="email"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Ingresá tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-login">Iniciar sesión</button>
                </form>

                <div class="login-footer">
                    <p>¿Todavía no tenés una cuenta? <a href="/auth/register">Registrate gratis</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
