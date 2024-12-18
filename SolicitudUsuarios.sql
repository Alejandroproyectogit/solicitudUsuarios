CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    tipoDocumento ENUM('CC', 'TI', 'CE', 'PP'),
    documento INT(10),
    nombres VARCHAR(100),
    apellidos VARCHAR(100),
    telefono INT(10),
    correo VARCHAR(200),
    cargo VARCHAR(150)
);

CREATE TABLE usuarios(
    id INT PRIMARY KEY,
    nombre INT(100),
    usuario INT(10),
    contrasena VARCHAR(50)
);

CREATE TABLE sistemas_de_informacion(
    id INT PRIMARY KEY,
    nombreSistema VARCHAR(70);

);

CREATE TABLE rol(idRol INT PRIMARY KEY, rol VARCHAR(50));

ALTER TABLE
    solicitudes
ADD
    FOREIGN KEY (id_sistema) REFERENCES sistemas_de_informacion(id);

ALTER TABLE
    usuarios
ADD
    FOREIGN (id_rol) REFERENCES rol(idRol);

ALTER TABLE
    solicitudes
ADD
    FOREIGN KEY(QuienSolicita) REFERENCES usuarios(id);