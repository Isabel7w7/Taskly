CREATE DATABASE Taskly_DB;

USE Taskly_DB;

CREATE TABLE users(
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'usuario'
);

CREATE TABLE equipo(
    id_equipo BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre_equipo VARCHAR(50),
    fecha_creacion DATE
);

CREATE TABLE integrante(
    id_integrante BIGINT PRIMARY KEY AUTO_INCREMENT,
    rol_en_equipo VARCHAR(50),
    id_usuario BIGINT,
    CONSTRAINT fk_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES users(id),

    id_equipo BIGINT,
    CONSTRAINT fk_equipo
    FOREIGN KEY (id_equipo)
    REFERENCES equipo(id_equipo)
);

CREATE TABLE tareas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    usuario_id BIGINT,
    descripcion TEXT NOT NULL,
    estatus VARCHAR(50) DEFAULT 'Por hacer',
    fecha_entrega DATE,

    FOREIGN KEY (usuario_id)
    REFERENCES users(id)
    ON DELETE SET NULL
);