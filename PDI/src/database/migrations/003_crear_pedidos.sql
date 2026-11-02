CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    numero VARCHAR(32) NOT NULL UNIQUE,
    total DECIMAL(12, 2) NOT NULL,
    metodo_pago VARCHAR(20) NOT NULL,
    estado VARCHAR(30) NOT NULL DEFAULT 'pendiente_pago',
    nombre_envio VARCHAR(150) NOT NULL,
    calle_envio VARCHAR(200) NOT NULL,
    localidad_envio VARCHAR(100) NOT NULL,
    provincia_envio VARCHAR(100) NOT NULL,
    cp_envio VARCHAR(20) NOT NULL,
    telefono_envio VARCHAR(40) NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS pedido_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    nombre_snapshot VARCHAR(200) NOT NULL,
    precio_snapshot DECIMAL(12, 2) NOT NULL,
    cantidad INT NOT NULL,
    CONSTRAINT fk_pedido_items_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON DELETE CASCADE
);
