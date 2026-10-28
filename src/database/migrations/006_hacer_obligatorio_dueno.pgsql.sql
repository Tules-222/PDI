ALTER TABLE productos DROP CONSTRAINT fk_productos_usuario;

DELETE FROM productos WHERE usuario_id IS NULL;

ALTER TABLE productos ALTER COLUMN usuario_id SET NOT NULL;

ALTER TABLE productos
    ADD CONSTRAINT fk_productos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE;
