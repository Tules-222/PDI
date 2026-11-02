ALTER TABLE emprendimientos
    ADD COLUMN rubro VARCHAR(40) NOT NULL DEFAULT 'otros',
    ADD KEY idx_emprendimientos_rubro_estado (rubro, estado);
