<?php

declare(strict_types=1);

$error = $error ?? null;
$success = $success ?? null;
?>
<main>
	<h1>Registro de usuario</h1>

	<?php if ($error !== null): ?>
		<p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
	<?php endif; ?>

	<?php if ($success !== null): ?>
		<p role="status"><?= htmlspecialchars($success, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
	<?php endif; ?>

	<form method="POST" action="/usuarios">
		<div>
			<label for="nombre">Nombre</label>
			<input id="nombre" name="nombre" type="text" maxlength="255" required>
		</div>

		<div>
			<label for="rol">Rol</label>
			<input id="rol" name="rol" type="text" maxlength="100" required>
		</div>

		<div>
			<label for="gmail">Correo electrónico</label>
			<input id="gmail" name="gmail" type="email" maxlength="255" required>
		</div>

		<div>
			<label for="telefono">Teléfono</label>
			<input id="telefono" name="telefono" type="tel" maxlength="30" required>
		</div>

		<div>
			<label for="direccion">Dirección</label>
			<input id="direccion" name="direccion" type="text" maxlength="255" required>
		</div>

		<button type="submit">Registrarme</button>
	</form>
</main>
