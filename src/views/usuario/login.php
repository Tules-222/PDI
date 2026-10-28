<?php

declare(strict_types=1);

$error = $error ?? null;
$next = $next ?? '/';
?>
<main class="auth-page login-page">
    <section class="login-card" aria-labelledby="login-title">
    <a class="auth-home" href="/">Overshine Contacts</a>
    <h1 id="login-title">Iniciar sesión</h1>
    <p class="auth-intro">Accedé a tus publicaciones, compras y ventas.</p>

    <?php if ($error !== null): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/usuarios/login">
        <input type="hidden" name="next" value="<?= htmlspecialchars((string) $next, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
        <div class="form-group">
            <label for="gmail">Correo electrónico</label>
            <input id="gmail" name="gmail" type="email" autocomplete="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <button type="submit">Ingresar</button>
    </form>
    <p class="auth-footer">¿No tenés una cuenta? <a href="/usuarios/register?next=<?= rawurlencode((string) $next) ?>">Registrate</a></p>
    </section>
</main>
