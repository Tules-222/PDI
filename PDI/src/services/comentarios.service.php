<?php

declare(strict_types=1);

function listar_comentarios_producto(int $productoId, bool $esPublicacion = false): array
{
    global $database;

    $connection = $database->getConnection();
    $statement = $connection->prepare(
        'SELECT c.id, c.calificacion, c.comentario, c.creado_en, u.nombre AS autor
         FROM comentarios_productos c
         INNER JOIN usuarios u ON u.id = c.usuario_id
         WHERE c.producto_id = :producto_id
           AND c.tipo_producto = :tipo_producto
         ORDER BY c.creado_en DESC, c.id DESC'
    );
    $statement->execute([
        ':producto_id' => $productoId,
        ':tipo_producto' => $esPublicacion ? 'publicacion' : 'catalogo',
    ]);

    return $statement->fetchAll();
}

function crear_comentario_producto(
    int $productoId,
    int $usuarioId,
    int $calificacion,
    string $comentario,
    bool $esPublicacion = false,
): void
{
    global $database;

    $connection = $database->getConnection();
    $statement = $connection->prepare(
        'INSERT INTO comentarios_productos (producto_id, tipo_producto, usuario_id, calificacion, comentario)
         VALUES (:producto_id, :tipo_producto, :usuario_id, :calificacion, :comentario)'
    );
    $statement->execute([
        ':producto_id' => $productoId,
        ':tipo_producto' => $esPublicacion ? 'publicacion' : 'catalogo',
        ':usuario_id' => $usuarioId,
        ':calificacion' => $calificacion,
        ':comentario' => $comentario,
    ]);
}
