UPDATE usuarios
SET rol = CASE
    WHEN EXISTS (
        SELECT 1
        FROM productos
        WHERE productos.usuario_id = usuarios.id
    ) THEN 'emprendedor'
    ELSE 'visitante'
END
WHERE rol = 'usuario';
