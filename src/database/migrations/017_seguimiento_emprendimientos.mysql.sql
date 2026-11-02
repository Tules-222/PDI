CREATE TABLE emprendimiento_seguidores (
    usuario_id INT NOT NULL,
    emprendimiento_id INT NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, emprendimiento_id),
    KEY idx_emprendimiento_seguidores_emprendimiento (emprendimiento_id, usuario_id),
    CONSTRAINT fk_emprendimiento_seguidores_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_emprendimiento_seguidores_emprendimiento
        FOREIGN KEY (emprendimiento_id) REFERENCES emprendimientos(id) ON DELETE CASCADE
);
