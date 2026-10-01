<h1>Crear cuenta</h1>

<?php if (isset($error)): ?>
    <p class="error"><?= html($error) ?></p>
<?php endif; ?>

<form method="POST" action="/auth/register">
    <label for="username">Usuario</label>
    <input
        type="text"
        id="username"
        name="username"
        value="<?= html($old["username"] ?? "") ?>"
        required
    >

    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        value="<?= html($old["email"] ?? "") ?>"
        required
    >

    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" required>

    <label for="telefono">teléfono</label>
    <input type="tel" id="telefono" name="telefono" required>    

    <label for="direccion">direccion</label>
    <input type="text" id="direccion" name="direccion" required>

    <button type="submit">Registrarme</button>
</form>

<p>¿Ya tenés cuenta? <a href="/auth/login">Iniciá sesión</a></p>
