CREATE TABLE emprendimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(500) NOT NULL DEFAULT '',
    localidad VARCHAR(100) NULL,
    provincia VARCHAR(100) NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'activo',
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_emprendimientos_usuario_nombre (usuario_id, nombre),
    KEY idx_emprendimientos_estado_nombre (estado, nombre),
    CONSTRAINT fk_emprendimientos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

INSERT INTO emprendimientos (usuario_id, nombre, descripcion, localidad, provincia)
SELECT id, CONCAT('Emprendimiento de ', nombre), COALESCE(descripcion_vendedor, ''), localidad, provincia
FROM usuarios
WHERE estado <> 'eliminado';

ALTER TABLE productos
    ADD COLUMN emprendimiento_id INT NULL,
    ADD KEY idx_productos_emprendimiento_estado (emprendimiento_id, estado),
    ADD CONSTRAINT fk_productos_emprendimiento
        FOREIGN KEY (emprendimiento_id) REFERENCES emprendimientos(id) ON DELETE SET NULL;

UPDATE productos p
INNER JOIN emprendimientos e ON e.usuario_id = p.usuario_id
SET p.emprendimiento_id = e.id
WHERE p.emprendimiento_id IS NULL;

ALTER TABLE compra_items
    ADD COLUMN emprendimiento_id INT NULL,
    ADD COLUMN nombre_emprendimiento VARCHAR(255) NOT NULL DEFAULT '',
    ADD KEY idx_compra_items_emprendimiento (emprendimiento_id),
    ADD CONSTRAINT fk_compra_items_emprendimiento
        FOREIGN KEY (emprendimiento_id) REFERENCES emprendimientos(id) ON DELETE SET NULL;

UPDATE compra_items i
INNER JOIN productos p ON p.id = i.producto_id
INNER JOIN emprendimientos e ON e.id = p.emprendimiento_id
SET i.emprendimiento_id = e.id,
    i.nombre_emprendimiento = e.nombre;
