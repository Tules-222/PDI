ALTER TABLE productos DROP FOREIGN KEY fk_productos_usuario;

DELETE FROM productos WHERE usuario_id IS NULL;

ALTER TABLE productos
    MODIFY usuario_id INTEGER NOT NULL,
    ADD CONSTRAINT fk_productos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE;
