<?php
// vistas/productos/create.php
// Renderiza el formulario para crear un nuevo producto.
// El form hace POST a /productos, que es la ruta que va a
// guardar el registro en la base de datos.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
</head>
<body>
    <h1>Nuevo producto</h1>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/productos" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion"></textarea><br><br>

        <label for="precio">Precio:</label><br>
        <input type="number" id="precio" name="precio" step="0.01" min="0" required><br><br>

        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" min="0" required><br><br>

        <button type="submit">Guardar</button>
    </form>

    <a href="/productos">Volver al listado</a>
</body>
</html>