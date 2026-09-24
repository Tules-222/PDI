<?php
// vistas/productos/not_found.php
// Se renderiza cuando el id pedido en GET /productos/{id}
// (o en update/delete) no existe en la base de datos.
// Conviene que el controlador setee el código de estado HTTP 404
// antes de incluir esta vista.
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Producto no encontrado</title>
</head>
<body>
    <h1>404 - Producto no encontrado</h1>
    <p>El producto que buscás no existe o fue eliminado.</p>

    <a href="/productos">Volver al listado</a>
</body>
</html>
