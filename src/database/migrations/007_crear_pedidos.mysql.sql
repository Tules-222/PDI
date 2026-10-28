CREATE TABLE pedidos (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    comprador_id INTEGER NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    total DECIMAL(10, 2) NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_comprador FOREIGN KEY (comprador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE pedido_items (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    pedido_id INTEGER NOT NULL,
    producto_id INTEGER NULL,
    vendedor_id INTEGER NULL,
    nombre_vendedor VARCHAR(255) NOT NULL,
    nombre_producto VARCHAR(255) NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    cantidad INTEGER NOT NULL,
    CONSTRAINT fk_items_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    CONSTRAINT fk_items_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL,
    CONSTRAINT fk_items_vendedor FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
