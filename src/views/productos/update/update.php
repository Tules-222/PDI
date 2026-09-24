<?php
// vistas/productos/update.php
// Renderiza el formulario para editar un producto existente.
// Se espera que el controlador le pase a esta vista la variable
// $producto (array o objeto) con los datos actuales del registro,
// obtenidos con el id recibido en GET /productos/update/{id}.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar producto #<?= htmlspecialchars($producto['id']) ?></h1>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!--
        HTML no soporta el verbo PUT en los forms, solo GET y POST.
        Por eso mandamos el form como POST y agregamos un campo oculto
        _method=PUT. En el router/controlador tenés que revisar ese
        campo y tratarlo como si fuera un PUT real. Si vos armaste tu
        propio router a mano, ahí es donde tenés que contemplar esto.
    -->
    <form action="/productos/<?= htmlspecialchars($producto['id']) ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre"
               value="<?= htmlspecialchars($producto['nombre']) ?>" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea><br><br>

        <label for="precio">Precio:</label><br>
        <input type="number" id="precio" name="precio" step="0.01" min="0"
               value="<?= htmlspecialchars($producto['precio']) ?>" required><br><br>

        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" min="0"
               value="<?= htmlspecialchars($producto['stock']) ?>" required><br><br>

        <button type="submit">Guardar cambios</button>
    </form>

    <a href="/productos">Volver al listado</a>
</body>
</html>
