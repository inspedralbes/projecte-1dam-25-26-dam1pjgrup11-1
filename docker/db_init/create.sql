SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS incidencies
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE incidencies;

GRANT ALL PRIVILEGES ON incidencies.* TO 'usuari'@'%';
FLUSH PRIVILEGES;

-- ======================
-- DROP (ordre correcte)
-- ======================
DROP TABLE IF EXISTS actuacio;
DROP TABLE IF EXISTS incidencia;
DROP TABLE IF EXISTS tecnic;
DROP TABLE IF EXISTS tipologia;
DROP TABLE IF EXISTS departament;
DROP TABLE IF EXISTS usuari;

-- ======================
-- TAULES
-- ======================

CREATE TABLE usuari (
    usuari_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'tecnic', 'professor') NOT NULL
);

CREATE TABLE departament (
    departament_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200)
);

CREATE TABLE tipologia (
    tipologia_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200)
);

CREATE TABLE tecnic (
    tecnic_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200),
    cognom VARCHAR(200),
    usuari_id INT,
    FOREIGN KEY (usuari_id) REFERENCES usuari(usuari_id)
);

CREATE TABLE incidencia (
    incidencia_id INT AUTO_INCREMENT PRIMARY KEY,
    departament_id INT,
    usuari_id INT,
    descripcio_incidencia VARCHAR(200),
    data_incidencia DATE,
    data_final DATE,
    prioritat ENUM('Alta', 'Mitja', 'Baixa'),
    estat ENUM('Oberta', 'En Curs', 'Finalitzada') DEFAULT 'Oberta',
    tecnic_id INT,
    tipologia_id INT,
    FOREIGN KEY (departament_id) REFERENCES departament(departament_id),
    FOREIGN KEY (tecnic_id) REFERENCES tecnic(tecnic_id),
    FOREIGN KEY (tipologia_id) REFERENCES tipologia(tipologia_id),
    FOREIGN KEY (usuari_id) REFERENCES usuari(usuari_id)
);

CREATE TABLE actuacio (
    actuacio_id INT AUTO_INCREMENT PRIMARY KEY,
    incidencia_id INT,
    tecnic_id INT,
    temps VARCHAR(30),
    data_actuacio DATE,
    descripcio_actuacio VARCHAR(200),
    visible INT(1),
    FOREIGN KEY (incidencia_id) REFERENCES incidencia(incidencia_id),
    FOREIGN KEY (tecnic_id) REFERENCES tecnic(tecnic_id)
);

-- ======================
-- DADES
-- ======================

-- USUARIS (primer de tot!)
INSERT INTO usuari (email, password, rol) VALUES
('joan.perez@incidencies.cat','pass123','tecnic'),
('maria.garcia@incidencies.cat','pass123','tecnic'),
('arnau.lopez@incidencies.cat','pass123','tecnic'),
('laia.martinez@incidencies.cat','pass123','tecnic'),
('pau.soler@incidencies.cat','pass123','tecnic'),
('marc.ferrer@incidencies.cat','pass123','tecnic'),
('clara.vila@incidencies.cat','pass123','tecnic'),
('jordi.roca@incidencies.cat','pass123','tecnic'),

('admin1@incidencies.cat','admin123','admin'),
('admin2@incidencies.cat','admin123','admin'),

('prof.informatica1@incidencies.cat','pass123','professor'),
('prof.informatica2@incidencies.cat','pass123','professor'),
('prof.angles@incidencies.cat','pass123','professor'),
('prof.manteniment@incidencies.cat','pass123','professor'),
('prof.matematiques1@incidencies.cat','pass123','professor'),
('prof.matematiques2@incidencies.cat','pass123','professor'),
('prof.naturals@incidencies.cat','pass123','professor'),
('prof.fisicaquimica@incidencies.cat','pass123','professor'),
('prof.educaciofisica1@incidencies.cat','pass123','professor'),
('prof.educaciofisica2@incidencies.cat','pass123','professor');

-- DEPARTAMENTS
INSERT INTO departament (nom) VALUES
('Informàtica'),('Anglès'),('Manteniment'),('Matemàtiques'),
('Ciències Naturals'),('Física i Química'),('Educació Física'),
('Administració'),('Orientació'),('Biblioteca'),
('Secretaria'),('Direcció');

-- TIPOLOGIA
INSERT INTO tipologia (nom) VALUES
('Maquinari'),('Programari'),('Xarxes'),('Impressió'),
('Accés d’usuaris'),('Servidor'),('Seguretat'),
('Audiovisuals'),('Connexió Internet'),('Manteniment general');

-- TÈCNICS (ara sí, perquè ja existeixen usuaris)
INSERT INTO tecnic (nom, cognom, usuari_id) VALUES
('Joan','Pérez',1),
('Maria','García',2),
('Arnau','López',3),
('Laia','Martínez',4),
('Pau','Soler',5),
('Marc','Ferrer',6),
('Clara','Vila',7),
('Jordi','Roca',8);

-- INCIDÈNCIES
INSERT INTO incidencia (
    departament_id,
    usuari_id,
    descripcio_incidencia,
    data_incidencia,
    data_final,
    prioritat,
    estat,
    tecnic_id,
    tipologia_id
) VALUES
(1,11,'PC aula 1 no arrenca','2026-04-01',NULL,'Alta','Oberta',1,1),
(2,12,'WiFi inestable','2026-04-02',NULL,'Alta','Oberta',2,3),
(3,13,'Projector avariat','2026-04-03','2026-04-06','Mitja','Finalitzada',3,8);

-- ACTUACIONS
INSERT INTO actuacio (incidencia_id, tecnic_id, temps, data_actuacio, descripcio_actuacio, visible)
VALUES
(1,1,'120','2026-04-01','Reinstal·lació SO',1),
(2,2,'60','2026-04-02','Configuració router',1),
(3,3,'90','2026-04-03','Canvi projector',1);