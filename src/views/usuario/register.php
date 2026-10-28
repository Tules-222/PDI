<?php

declare(strict_types=1);

$error = $error ?? null;
$success = $success ?? null;
$next = $next ?? '/';
?>
<main class="auth-page register-page">
	<section class="register-container" aria-labelledby="register-title">
	<a class="auth-home" href="/">Overshine Contacts</a>
	<h1 id="register-title">Crear cuenta</h1>
	<p class="auth-intro">Sumate para publicar, comprar y vender.</p>

	<?php if ($error !== null): ?>
		<p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
	<?php endif; ?>

	<?php if ($success !== null): ?>
		<p role="status"><?= htmlspecialchars($success, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
	<?php endif; ?>

	<form method="POST" action="/usuarios">
		<input type="hidden" name="next" value="<?= htmlspecialchars((string) $next, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input id="nombre" name="nombre" type="text" maxlength="255" required>
		</div>

		<div class="form-group">
			<label for="gmail">Correo electrónico</label>
			<input id="gmail" name="gmail" type="email" maxlength="255" required>
		</div>

		<div class="form-group full-width">
			<label for="password">Contraseña (mínimo 8 caracteres)</label>
			<input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required>
		</div>

		<div class="form-group">
			<label for="telefono">Teléfono</label>
			<input id="telefono" name="telefono" type="tel" maxlength="30" required>
		</div>

		<div class="form-group">
			<label for="direccion">Dirección</label>
			<input id="direccion" name="direccion" type="text" maxlength="255" required>
		</div>

		<div class="submit-group"><button type="submit">Registrarme</button></div>
	</form>
	<p class="form-footer">¿Ya tenés una cuenta? <a href="/usuarios/login?next=<?= rawurlencode((string) $next) ?>">Iniciá sesión</a></p>
	</section>
</main>
