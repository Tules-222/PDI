ALTER TABLE usuarios
    ADD COLUMN password_hash VARCHAR(255) NULL;

ALTER TABLE productos
    ADD COLUMN usuario_id INTEGER NULL,
    ADD COLUMN estado VARCHAR(20) NOT NULL DEFAULT 'aprobado',
    ADD CONSTRAINT fk_productos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE SET NULL;
