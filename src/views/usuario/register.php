<?php

declare(strict_types=1);

$error = $error ?? null;
$formData = $formData ?? [];
?>
<main class="page-content narrow-content">
	<a class="back-link" href="/">← Volver al inicio</a>
	<section class="form-card">
		<p class="eyebrow">Un espacio para comprar y publicar</p>
		<h1>Crear tu cuenta</h1>
		<p class="form-intro">Elegí cómo querés participar. Si empezás como visitante, siempre vas a poder abrir tu puesto más adelante.</p>

		<?php if ($error !== null): ?>
			<p class="notice notice-error" role="alert"><?= html($error) ?></p>
		<?php endif; ?>

		<form class="stacked-form" method="post" action="/usuarios" data-georef-address>
			<input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
			<fieldset class="role-options">
				<legend>¿Cómo querés recorrer la feria?</legend>
				<label class="role-option">
					<input type="radio" name="rol" value="visitante" required <?= ($formData['rol'] ?? '') === 'visitante' ? 'checked' : '' ?>>
					<span class="role-option-content">
						<strong>Quiero comprar</strong>
						<span>Explorá productos, guardá favoritos y contactá emprendimientos.</span>
					</span>
				</label>
				<label class="role-option">
					<input type="radio" name="rol" value="emprendedor" required <?= ($formData['rol'] ?? '') === 'emprendedor' ? 'checked' : '' ?>>
					<span class="role-option-content">
						<strong>Quiero emprender</strong>
						<span>Además de recorrer la feria, vas a tener tu puesto para publicar y administrar productos.</span>
					</span>
				</label>
			</fieldset>
			<div class="form-grid">
				<div class="form-field">
					<label for="nombre">Nombre</label>
					<input id="nombre" name="nombre" type="text" maxlength="255" autocomplete="name" value="<?= html((string) ($formData['nombre'] ?? '')) ?>" required>
				</div>
				<div class="form-field">
					<label for="gmail">Correo electrónico</label>
					<input id="gmail" name="gmail" type="email" maxlength="255" autocomplete="email" value="<?= html((string) ($formData['gmail'] ?? '')) ?>" required>
				</div>
				<div class="form-field">
					<label for="telefono">Teléfono</label>
					<input id="telefono" name="telefono" type="tel" maxlength="20" pattern="[0-9+(). /-]{8,20}" title="Ingresá un teléfono argentino de 10 dígitos, con un máximo de 13 cifras." autocomplete="tel" data-argentina-phone inputmode="tel" value="<?= html((string) ($formData['telefono'] ?? '')) ?>" required>
				</div>
				<div class="form-field">
					<label for="direccion">Dirección (calle y altura)</label>
					<input id="direccion" name="direccion" type="text" maxlength="255" autocomplete="street-address" value="<?= html((string) ($formData['direccion'] ?? '')) ?>" required>
				</div>
				<div class="form-field">
					<label for="localidad">Localidad</label>
					<input id="localidad" name="localidad" type="text" maxlength="100" list="localidades-argentinas" autocomplete="address-level2" value="<?= html((string) ($formData['localidad'] ?? '')) ?>" required>
					<datalist id="localidades-argentinas"></datalist>
				</div>
				<div class="form-field">
					<label for="provincia">Provincia</label>
					<select id="provincia" name="provincia" data-selected="<?= html((string) ($formData['provincia'] ?? '')) ?>" autocomplete="address-level1" required>
						<option value="">Cargando provincias...</option>
					</select>
				</div>
				<p id="georef-status" class="georef-status" data-georef-status role="status">Las localidades y direcciones se verifican con Georef Argentina.</p>
				<div class="form-field">
					<label for="codigo_postal">Código postal</label>
					<input id="codigo_postal" name="codigo_postal" type="text" maxlength="12" autocomplete="postal-code" value="<?= html((string) ($formData['codigo_postal'] ?? '')) ?>">
				</div>
				<div class="form-field">
					<label for="password">Contraseña</label>
					<input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
				</div>
				<div class="form-field">
					<label for="password_confirmation">Repetir contraseña</label>
					<input id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
				</div>
			</div>
			<button class="button button-primary button-wide" type="submit">Crear cuenta</button>
		</form>
		<p class="form-footer">¿Ya tenés cuenta? <a href="/usuarios/login">Iniciá sesión</a></p>
	</section>
</main>
<script src="/georef-address.js" defer></script>
