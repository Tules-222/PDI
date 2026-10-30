<?php

declare(strict_types=1);

$error = $error ?? null;
$email = $email ?? '';
$sent = $sent ?? false;
$smtpConfigured = $smtpConfigured ?? false;
?>
<main class="page-content narrow-content">
  <a class="back-link" href="/usuarios/login">← Volver a iniciar sesión</a>
  <section class="form-card form-card-small">
    <p class="eyebrow">Recuperar acceso</p>
    <h1>Restablecer contraseña</h1>
    <p class="form-intro">Ingresá el correo asociado a tu cuenta y te enviaremos un enlace de un solo uso, válido por una hora.</p>

    <?php if ($error !== null): ?>
      <p class="notice notice-error" role="alert"><?= html($error) ?></p>
    <?php elseif ($sent): ?>
      <p class="notice notice-review" role="status">Si existe una cuenta activa con ese correo, recibirás instrucciones para restablecer la contraseña.</p>
    <?php elseif (!$smtpConfigured): ?>
      <p class="notice notice-review" role="status">La recuperación por correo no está configurada en este sitio.</p>
    <?php endif; ?>

    <form class="stacked-form" method="post" action="/usuarios/recuperar">
      <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
      <div class="form-field">
        <label for="gmail">Correo electrónico</label>
        <input id="gmail" name="gmail" type="email" maxlength="255" autocomplete="email" value="<?= html((string) $email) ?>" required>
      </div>
      <button class="button button-primary button-wide" type="submit" <?= !$smtpConfigured ? 'disabled' : '' ?>>Enviar enlace de recuperación</button>
    </form>
  </section>
</main>
