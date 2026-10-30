CREATE TABLE conversaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comprador_id INT NOT NULL,
    vendedor_id INT NOT NULL,
    emprendimiento_id INT NULL,
    producto_id INT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_conversaciones_comprador_actualizado (comprador_id, actualizado_en),
    KEY idx_conversaciones_vendedor_actualizado (vendedor_id, actualizado_en),
    KEY idx_conversaciones_producto (producto_id),
    CONSTRAINT fk_conversaciones_comprador
        FOREIGN KEY (comprador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_conversaciones_vendedor
        FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_conversaciones_emprendimiento
        FOREIGN KEY (emprendimiento_id) REFERENCES emprendimientos(id) ON DELETE SET NULL,
    CONSTRAINT fk_conversaciones_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversacion_id INT NOT NULL,
    remitente_id INT NOT NULL,
    contenido VARCHAR(2000) NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    leido_en TIMESTAMP NULL,
    KEY idx_mensajes_conversacion_id (conversacion_id, id),
    KEY idx_mensajes_no_leidos (conversacion_id, remitente_id, leido_en),
    CONSTRAINT fk_mensajes_conversacion
        FOREIGN KEY (conversacion_id) REFERENCES conversaciones(id) ON DELETE CASCADE,
    CONSTRAINT fk_mensajes_remitente
        FOREIGN KEY (remitente_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE admin_conversacion_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversacion_id INT NOT NULL,
    admin_actor VARCHAR(100) NOT NULL DEFAULT 'admin_pin_compartido',
    session_fingerprint CHAR(64) NOT NULL,
    motivo VARCHAR(500) NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_admin_conversacion_auditoria (conversacion_id, creado_en),
    CONSTRAINT fk_admin_conversacion_auditoria
        FOREIGN KEY (conversacion_id) REFERENCES conversaciones(id) ON DELETE CASCADE
);
