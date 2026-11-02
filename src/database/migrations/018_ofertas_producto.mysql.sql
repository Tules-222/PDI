ALTER TABLE productos
    ADD COLUMN oferta_precio DECIMAL(10, 2) NULL,
    ADD COLUMN oferta_inicio DATETIME NULL,
    ADD COLUMN oferta_fin DATETIME NULL,
    ADD COLUMN oferta_estado VARCHAR(20) NOT NULL DEFAULT 'sin_oferta',
    ADD COLUMN oferta_motivo_revision VARCHAR(500) NULL,
    ADD KEY idx_productos_oferta_estado_vigencia (oferta_estado, oferta_inicio, oferta_fin);
