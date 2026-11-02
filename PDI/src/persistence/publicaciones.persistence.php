<?php

declare(strict_types=1);

function listar_publicaciones_catalogo(): array
{
    global $database;

    $statement = $database->getConnection()->query(
        'SELECT p.id, p.nombre, p.precio, p.stock, p.imagen_path, u.nombre AS vendedor
         FROM productos p
         INNER JOIN usuarios u ON u.id = p.usuario_id
         ORDER BY p.id DESC'
    );

    return $statement->fetchAll();
}

function listar_publicaciones_usuario(int $usuarioId): array
{
    global $database;

    $statement = $database->getConnection()->prepare(
        'SELECT id, nombre, precio, stock, imagen_path
         FROM productos
         WHERE usuario_id = :usuario_id
         ORDER BY id DESC'
    );
    $statement->execute([':usuario_id' => $usuarioId]);

    return $statement->fetchAll();
}

function buscar_publicacion_por_id(int $id): ?array
{
    global $database;

    $statement = $database->getConnection()->prepare(
        'SELECT p.id, p.nombre, p.precio, p.stock, p.imagen_path, u.nombre AS vendedor
         FROM productos p
         INNER JOIN usuarios u ON u.id = p.usuario_id
         WHERE p.id = :id
         LIMIT 1'
    );
    $statement->execute([':id' => $id]);
    $publicacion = $statement->fetch();

    return $publicacion === false ? null : $publicacion;
}

function crear_publicacion_usuario(int $usuarioId, string $nombre, float $precio, int $stock, ?string $imagenPath): int
{
    global $database;

    $statement = $database->getConnection()->prepare(
        'INSERT INTO productos (usuario_id, nombre, precio, stock, imagen_path)
         VALUES (:usuario_id, :nombre, :precio, :stock, :imagen_path)'
    );
    $statement->execute([
        ':usuario_id' => $usuarioId,
        ':nombre' => $nombre,
        ':precio' => number_format($precio, 2, '.', ''),
        ':stock' => $stock,
        ':imagen_path' => $imagenPath,
    ]);

    return (int) $database->getConnection()->lastInsertId();
}

/**
 * @return array{imagen_path: string|null}|null
 */
function eliminar_publicacion_usuario(int $publicacionId, int $usuarioId): ?array
{
    global $database;

    return $database->runTransaction(
        static function (PDO $connection) use ($publicacionId, $usuarioId): ?array {
            $select = $connection->prepare(
                'SELECT imagen_path
                 FROM productos
                 WHERE id = :id AND usuario_id = :usuario_id
                 FOR UPDATE'
            );
            $select->execute([
                ':id' => $publicacionId,
                ':usuario_id' => $usuarioId,
            ]);
            $publicacion = $select->fetch();

            if ($publicacion === false) {
                return null;
            }

            $deleteComments = $connection->prepare(
                "DELETE FROM comentarios_productos
                 WHERE producto_id = :producto_id AND tipo_producto = 'publicacion'"
            );
            $deleteComments->execute([':producto_id' => $publicacionId]);

            $deleteProduct = $connection->prepare(
                'DELETE FROM productos
                 WHERE id = :id AND usuario_id = :usuario_id'
            );
            $deleteProduct->execute([
                ':id' => $publicacionId,
                ':usuario_id' => $usuarioId,
            ]);

            if ($deleteProduct->rowCount() !== 1) {
                throw new RuntimeException('No se pudo eliminar la publicación del usuario.');
            }

            return ['imagen_path' => is_string($publicacion['imagen_path']) ? $publicacion['imagen_path'] : null];
        }
    );
}
