create TABLE user(
    id int(10) AUTO_INCREMENT PRIMARY KEY,
    nombre char(255),
    rol char(255) UNIQUE,
    gmail char(255) UNIQUE,
    telefono int(10),
    direccion char(255),
);