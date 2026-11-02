ALTER TABLE comentarios_productos
    ADD COLUMN tipo_producto ENUM('catalogo', 'publicacion') NOT NULL DEFAULT 'catalogo' AFTER producto_id,
    ADD INDEX idx_comentarios_productos_tipo_fecha (tipo_producto, producto_id, creado_en);
