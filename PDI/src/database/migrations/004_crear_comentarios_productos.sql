CREATE TABLE IF NOT EXISTS comentarios_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    calificacion TINYINT UNSIGNED NOT NULL,
    comentario VARCHAR(1000) NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_comentarios_productos_calificacion
        CHECK (calificacion BETWEEN 1 AND 5),
    CONSTRAINT fk_comentarios_productos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE,
    INDEX idx_comentarios_productos_producto_fecha (producto_id, creado_en)
);
