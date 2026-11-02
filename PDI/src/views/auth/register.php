<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#14213d">
    <script src="/site-theme.js"></script>
    <link rel="stylesheet" href="/register.css">
    <link rel="stylesheet" href="/site-theme.css">
    <title>Crear cuenta | Emprendi</title>
</head>
<body>
    <header class="register-header">
        <a href="/" class="register-logo" aria-label="Emprendi, ir al catálogo">
            <span class="register-logo-mark" aria-hidden="true">E</span>
            <span>Emprendi</span>
        </a>
        <a href="/" class="register-back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
            Volver al catálogo
        </a>
    </header>

    <main class="register-main">
        <section class="register-shell" aria-label="Crear una cuenta">
            <div class="register-welcome">
                <span class="register-eyebrow">Creá tu cuenta</span>
                <div class="register-welcome-copy">
                    <h1>Un gran comienzo para comprar mejor.</h1>
                    <p>Registrate para descubrir productos únicos y conectar con emprendimientos locales.</p>
                </div>
                <div class="register-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.7 3.2 7.7 7.5 9.5 4.3-1.8 7.5-4.8 7.5-9.5V6L12 3Z"/><path d="m9 12 2 2 4-4"/></svg>
                    <span>Tu cuenta, tus compras, todo en un solo lugar.</span>
                </div>
            </div>

            <div class="register-card">
                <h2>Crear cuenta</h2>
                <p class="register-intro">Completá tus datos para empezar.</p>

                <?php if (!empty($errors)): ?>
                    <ul class="register-errors" role="alert" aria-label="No se pudo completar el registro">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars((string) $error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form action="/auth/register" method="POST" class="register-form">
                    <div class="register-field register-field-wide">
                        <label for="nombre">Nombre completo</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="<?= htmlspecialchars((string) ($data['nombre'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                            placeholder="Ej.: María Pérez"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <div class="register-field register-field-wide">
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

                    <div class="register-field register-field-wide">
                        <label for="tel">Teléfono</label>
                        <input
                            type="tel"
                            id="tel"
                            name="tel"
                            value="<?= htmlspecialchars((string) ($data['tel'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                            placeholder="Ej.: +57 300 123 4567"
                            autocomplete="tel"
                            required
                        >
                    </div>

                    <div class="register-field register-field-wide">
                        <label for="password">Contraseña</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Al menos 8 caracteres"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >
                        <p class="register-hint">Usá al menos 8 caracteres.</p>
                    </div>

                    <div class="register-submit">
                        <button type="submit" class="btn-register">Crear cuenta</button>
                    </div>
                </form>

                <div class="register-footer">
                    <p>¿Ya tenés una cuenta? <a href="/auth/login">Iniciá sesión</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
