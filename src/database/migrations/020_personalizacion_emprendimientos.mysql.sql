ALTER TABLE emprendimientos
    ADD COLUMN logo_path VARCHAR(255) NULL,
    ADD COLUMN banner_path VARCHAR(255) NULL,
    ADD COLUMN color_primario CHAR(7) NOT NULL DEFAULT '#166534',
    ADD COLUMN color_secundario CHAR(7) NOT NULL DEFAULT '#f0fdf4';
