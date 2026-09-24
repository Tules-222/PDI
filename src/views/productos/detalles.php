<?php
// vistas/productos/show.php
// Renderiza el detalle de un producto puntual.
// Se espera que el controlador ya haya buscado el producto por id
// y se lo pase a esta vista como $producto (array/objeto).
// Si el id no existe, el controlador NO debe incluir esta vista:
// tiene que incluir not_found.php en su lugar.
//
// El botón "Eliminar" manda un DELETE real con fetch(), en vez de
// simularlo con un form + _method oculto.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Producto</title>
</head>
<body>
    <h1><?= htmlspecialchars($producto['nombre']) ?></h1>

    <p><strong>ID:</strong> <?= htmlspecialchars($producto['id']) ?></p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['descripcion']) ?></p>
    <p><strong>Precio:</strong> $<?= htmlspecialchars($producto['precio']) ?></p>
    <p><strong>Stock:</strong> <?= htmlspecialchars($producto['stock']) ?></p>

    <a href="/productos/<?= htmlspecialchars($producto['id']) ?>/update">Editar</a>
    &nbsp;|&nbsp;
    <button id="btn-eliminar" data-id="<?= htmlspecialchars($producto['id']) ?>">Eliminar</button>
    &nbsp;|&nbsp;
    <a href="/productos">Volver al listado</a>

    <p id="mensaje-error" style="color:red;"></p>

    <script>
        document.getElementById('btn-eliminar').addEventListener('click', async function () {
            if (!confirm('¿Seguro que querés eliminar este producto?')) return;

            const id = this.dataset.id;

            try {
                const resp = await fetch(`/productos/${id}`, { method: 'DELETE' });

                if (resp.ok) {
                    window.location.href = '/productos';
                } else {
                    const data = await resp.json().catch(() => ({}));
                    document.getElementById('mensaje-error').textContent =
                        data.error || 'Hubo un error al eliminar el producto.';
                }
            } catch (err) {
                document.getElementById('mensaje-error').textContent =
                    'No se pudo conectar con el servidor.';
            }
        });
    </script>
</body>
</html>
