ALTER TABLE productos
    ADD COLUMN usuario_id INT NULL,
    ADD CONSTRAINT fk_productos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE SET NULL;
