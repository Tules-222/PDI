CREATE TABLE usuario_reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_reportado_id INT NOT NULL,
    usuario_reportante_id INT NULL,
    motivo VARCHAR(40) NOT NULL,
    detalle VARCHAR(500) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_usuario_reportes_estado_fecha (estado, creado_en),
    KEY idx_usuario_reportes_objetivo (usuario_reportado_id, estado),
    CONSTRAINT fk_usuario_reportes_reportado
        FOREIGN KEY (usuario_reportado_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_usuario_reportes_reportante
        FOREIGN KEY (usuario_reportante_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

ALTER TABLE admin_auditoria
    ADD COLUMN actor VARCHAR(100) NOT NULL DEFAULT 'admin_pin_compartido',
    ADD KEY idx_admin_auditoria_actor_fecha (actor, creado_en),
    ADD KEY idx_admin_auditoria_entidad_accion (entidad, accion, creado_en);
