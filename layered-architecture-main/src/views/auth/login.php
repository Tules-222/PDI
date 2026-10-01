<h1>Iniciar sesión</h1>

<?php if (isset($error)): ?>
    <p class="error"><?= html($error) ?></p>
<?php endif; ?>

<form method="POST" action="/auth/login">
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

    <button type="submit">Entrar</button>
</form>

<p>¿No tenés cuenta? <a href="/auth/register">Registrate</a></p>
