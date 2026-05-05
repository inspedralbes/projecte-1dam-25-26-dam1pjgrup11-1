-- Aquest script NOMÉS s'executa la primera vegada que es crea el contenidor.
-- Si es vol recrear les taules de nou cal esborrar el contenidor, o bé les dades del contenidor
-- és a dir, 
-- esborrar el contingut de la carpeta db_data 
-- o canviant el nom de la carpeta, però atenció a no pujar-la a git


-- És un exemple d'script per crear una base de dades i una taula
-- i afegir-hi dades inicials

-- Si creem la BBDD aquí podem control·lar la codificació i el collation
-- en canvi en el docker-compose no podem especificar el collation ni la codificació

-- Per assegurar-nes de que la codificació dels caràcters d'aquest script és la correcta
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS incidencies
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON incidencies.* TO 'usuari'@'%';
FLUSH PRIVILEGES;

USE incidencies;

DROP TABLE IF EXISTS actuacio;
DROP TABLE IF EXISTS incidencia;
DROP TABLE IF EXISTS tecnic;
DROP TABLE IF EXISTS tipologia;
DROP TABLE IF EXISTS departament;

-- ======================
-- TAULES
-- ======================

CREATE TABLE departament (
    departament_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200)
);

CREATE TABLE tipologia (
    tipologia_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200)
);

CREATE TABLE tecnic (
    tecnic_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200),
    cognom VARCHAR(200)
);

CREATE TABLE incidencia (
    incidencia_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    departament_id INT(11),
    descripcio_incidencia VARCHAR(200),
    data_incidencia DATE,
    data_final DATE,
    prioritat ENUM('Alta', 'Mitja', 'Baixa'),
    tecnic_id INT(11),
    tipologia_id INT(11)
);

CREATE TABLE actuacio (
    actuacio_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    incidencia_id INT(11),
    tecnic_id INT(11),
    temps VARCHAR(30),
    data_actuacio DATE,
    descripcio_actuacio VARCHAR(200),
    visible INT(1)
);

-- ======================
-- CLAUS FORANES
-- ======================

ALTER TABLE incidencia
ADD CONSTRAINT fk_incidencia_departament
FOREIGN KEY (departament_id) REFERENCES departament(departament_id);

ALTER TABLE incidencia
ADD CONSTRAINT fk_incidencia_tecnic
FOREIGN KEY (tecnic_id) REFERENCES tecnic(tecnic_id);

ALTER TABLE incidencia
ADD CONSTRAINT fk_incidencia_tipologia
FOREIGN KEY (tipologia_id) REFERENCES tipologia(tipologia_id);

ALTER TABLE actuacio
ADD CONSTRAINT fk_actuacio_tecnic
FOREIGN KEY (tecnic_id) REFERENCES tecnic(tecnic_id);

ALTER TABLE actuacio
ADD CONSTRAINT fk_actuacio_incidencia
FOREIGN KEY (incidencia_id) REFERENCES incidencia(incidencia_id);

-- ======================
-- DADES
-- ======================

-- Departaments
INSERT INTO departament (nom) VALUES
('Informàtica'),
('Anglès'),
('Manteniment'),
('Matemàtiques'),
('Ciències Naturals'),
('Física i Química'),
('Educació Física'),
('Administració');

-- Tipologies
INSERT INTO tipologia (nom) VALUES
('Maquinari'),
('Programari'),
('Xarxes'),
('Impressió'),
('Accés d’usuaris'),
('Servidor'),
('Seguretat'),
('Audiovisuals'),
('Connexió Internet'),
('Manteniment general');

-- Tècnics
INSERT INTO tecnic (nom, cognom) VALUES
('Joan', 'Pérez'),
('Maria', 'García'),
('Arnau', 'López'),
('Laia', 'Martínez'),
('Pau', 'Soler'),
('Marc', 'Ferrer'),
('Clara', 'Vila'),
('Jordi', 'Roca'),
('Núria', 'Costa'),
('Xavier', 'Puig'),
('Sergi', 'Batlle');

-- ======================
-- INCIDÈNCIES
-- (ALGUNES JA TENEN TÈCNIC ASSIGNAT)
-- ======================

INSERT INTO incidencia (departament_id, descripcio_incidencia, data_incidencia, data_final, prioritat, tecnic_id, tipologia_id)
VALUES (1, 'No funciona lordinador de laula 1', '2026-04-01', NULL, 'Alta', 1, 1);

INSERT INTO incidencia VALUES (NULL, 2, 'Problema amb la WiFi de laula', '2026-04-02', NULL, 'Alta', 2, 3);

INSERT INTO incidencia VALUES (NULL, 3, 'Projector espatllat a la sala de reunions', '2026-04-03', NULL, 'Mitja', 3, 8);

INSERT INTO incidencia VALUES (NULL, 4, 'Impressora sense paper i error', '2026-04-04', NULL, 'Baixa', 4, 4);

INSERT INTO incidencia VALUES (NULL, 5, 'Servidor lent en accés a fitxers', '2026-04-05', NULL, 'Alta', 5, 6);

INSERT INTO incidencia VALUES (NULL, 6, 'Problema general de connexió a internet', '2026-04-06', NULL, 'Alta', NULL, 9);

INSERT INTO incidencia VALUES (NULL, 7, 'Equip de so no funciona al gimnàs', '2026-04-07', NULL, 'Mitja', 7, 8);

INSERT INTO incidencia VALUES (NULL, 8, 'Error al sistema daccés administratiu', '2026-04-08', NULL, 'Alta', 8, 5);

INSERT INTO incidencia VALUES (NULL, 1, 'Pantalla no encén a laula informàtica', '2026-04-09', NULL, 'Mitja', 1, 1);

INSERT INTO incidencia VALUES (NULL, 2, 'Problema amb la plataforma educativa', '2026-04-10', NULL, 'Alta', 2, 2);

INSERT INTO incidencia VALUES (NULL, 3, 'Cable de xarxa desconnectat al passadís', '2026-04-11', NULL, 'Baixa', NULL, 3);

INSERT INTO incidencia VALUES (NULL, 4, 'Error en impressions múltiples', '2026-04-12', NULL, 'Mitja', 4, 4);

-- ======================
-- ACTUACIONS
-- (temps en minuts, sense "h")
-- ======================

INSERT INTO actuacio (incidencia_id, tecnic_id, temps, data_actuacio, descripcio_actuacio, visible)
VALUES
(1, 1, '120', '2026-04-01', 'Reinstal·lació del sistema operatiu', 1),
(2, 2, '60', '2026-04-02', 'Configuració del punt WiFi de laula', 1),
(3, 3, '90', '2026-04-03', 'Substitució del projector', 1),
(4, 4, '30', '2026-04-04', 'Reposició de paper a la impressora', 1),
(5, 5, '180', '2026-04-05', 'Optimització del servidor', 1),
(6, 6, '120', '2026-04-06', 'Revisió del router principal', 1),
(7, 7, '60', '2026-04-07', 'Reparació del sistema de so', 1),
(8, 8, '120', '2026-04-08', 'Correcció del sistema daccés', 1),
(9, 1, '60', '2026-04-09', 'Substitució de pantalla defectuosa', 1),
(10, 2, '60', '2026-04-10', 'Reinici de la plataforma educativa', 1),
(11, 3, '120', '2026-04-11', 'Reconnectar cablejat de xarxa', 1),
(12, 4, '60', '2026-04-12', 'Reparació del sistema dimpressió', 1);
