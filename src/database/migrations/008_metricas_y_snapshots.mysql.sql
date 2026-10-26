ALTER TABLE productos
    ADD COLUMN busquedas INTEGER NOT NULL DEFAULT 0;

UPDATE productos SET estado = 'aprobado' WHERE estado = 'pendiente';

ALTER TABLE pedidos
    ADD COLUMN comprador_id_snapshot INTEGER NULL,
    ADD COLUMN comprador_nombre_snapshot VARCHAR(255) NULL,
    ADD COLUMN comprador_email_snapshot VARCHAR(255) NULL;

UPDATE pedidos p
JOIN usuarios u ON u.id = p.comprador_id
SET p.comprador_id_snapshot = u.id,
    p.comprador_nombre_snapshot = u.nombre,
    p.comprador_email_snapshot = u.gmail;

ALTER TABLE pedidos
    MODIFY comprador_id_snapshot INTEGER NOT NULL,
    MODIFY comprador_nombre_snapshot VARCHAR(255) NOT NULL,
    MODIFY comprador_email_snapshot VARCHAR(255) NOT NULL,
    MODIFY comprador_id INTEGER NULL;

ALTER TABLE pedidos DROP FOREIGN KEY fk_pedidos_comprador;

ALTER TABLE pedidos
    ADD CONSTRAINT fk_pedidos_comprador FOREIGN KEY (comprador_id) REFERENCES usuarios(id) ON DELETE SET NULL;

ALTER TABLE pedido_items
    ADD COLUMN producto_id_snapshot INTEGER NULL,
    ADD COLUMN propietario_usuario_id_snapshot INTEGER NULL,
    ADD COLUMN email_vendedor_snapshot VARCHAR(255) NULL;

UPDATE pedido_items i
LEFT JOIN productos p ON p.id = i.producto_id
LEFT JOIN usuarios u ON u.id = COALESCE(i.vendedor_id, p.usuario_id)
SET i.producto_id_snapshot = i.producto_id,
    i.propietario_usuario_id_snapshot = COALESCE(i.vendedor_id, p.usuario_id),
    i.email_vendedor_snapshot = u.gmail;