<?php

declare(strict_types=1);

$error = $error ?? null;
$token = $token ?? '';
?>
<main class="page-content narrow-content">
  <a class="back-link" href="/usuarios/login">← Volver a iniciar sesión</a>
  <section class="form-card form-card-small">
    <p class="eyebrow">Elegí una contraseña nueva</p>
    <h1>Restablecer contraseña</h1>
    <p class="form-intro">El enlace vence una hora después de solicitarlo y solo puede usarse una vez.</p>

    <?php if ($error !== null): ?>
      <p class="notice notice-error" role="alert"><?= html($error) ?></p>
    <?php endif; ?>

    <form class="stacked-form" method="post" action="/usuarios/restablecer">
      <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
      <input type="hidden" name="token" value="<?= html((string) $token) ?>">
      <div class="form-field">
        <label for="password">Nueva contraseña</label>
        <input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
      </div>
      <div class="form-field">
        <label for="password_confirmation">Repetir contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
      </div>
      <button class="button button-primary button-wide" type="submit">Guardar contraseña</button>
    </form>
  </section>
</main>
