create TABLE productos(
    id int(10) AUTO_INCREMENT PRIMARY KEY,
    nombre char(255),
    descripcion char(255) UNIQUE,
    precio int(10),
    stock int(10),
);