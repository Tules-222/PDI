ALTER TABLE productos
    ADD COLUMN busquedas INTEGER NOT NULL DEFAULT 0;

UPDATE productos SET estado = 'aprobado' WHERE estado = 'pendiente';

ALTER TABLE pedidos
    ADD COLUMN comprador_id_snapshot INTEGER,
    ADD COLUMN comprador_nombre_snapshot VARCHAR(255),
    ADD COLUMN comprador_email_snapshot VARCHAR(255);

UPDATE pedidos p
SET comprador_id_snapshot = u.id,
    comprador_nombre_snapshot = u.nombre,
    comprador_email_snapshot = u.gmail
FROM usuarios u
WHERE u.id = p.comprador_id;

ALTER TABLE pedidos
    ALTER COLUMN comprador_id_snapshot SET NOT NULL,
    ALTER COLUMN comprador_nombre_snapshot SET NOT NULL,
    ALTER COLUMN comprador_email_snapshot SET NOT NULL,
    ALTER COLUMN comprador_id DROP NOT NULL;

ALTER TABLE pedidos DROP CONSTRAINT fk_pedidos_comprador;

ALTER TABLE pedidos
    ADD CONSTRAINT fk_pedidos_comprador FOREIGN KEY (comprador_id) REFERENCES usuarios(id) ON DELETE SET NULL;

ALTER TABLE pedido_items
    ADD COLUMN producto_id_snapshot INTEGER,
    ADD COLUMN propietario_usuario_id_snapshot INTEGER,
    ADD COLUMN email_vendedor_snapshot VARCHAR(255);

UPDATE pedido_items i
SET producto_id_snapshot = i.producto_id,
    propietario_usuario_id_snapshot = COALESCE(i.vendedor_id, (SELECT p.usuario_id FROM productos p WHERE p.id = i.producto_id)),
    email_vendedor_snapshot = (
        SELECT u.gmail
        FROM usuarios u
        WHERE u.id = COALESCE(i.vendedor_id, (SELECT p.usuario_id FROM productos p WHERE p.id = i.producto_id))
    );