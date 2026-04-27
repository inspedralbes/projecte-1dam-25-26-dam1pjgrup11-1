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

-- Donem permisos a l'usuari 'usuari' per accedir a la base de dades 'persones'
-- sinó, aquest usuari no podrà veure la base de dades i no podrà accedir a les taules
GRANT ALL PRIVILEGES ON incidencies.* TO 'usuari'@'%';
FLUSH PRIVILEGES;


-- Després de crear la base de dades, cal seleccionar-la per treballar-hi
USE incidencies;


DROP TABLE IF EXISTS actuacio;
DROP TABLE IF EXISTS incidencia;
DROP TABLE IF EXISTS tecnic;
DROP TABLE IF EXISTS tipologia;
DROP TABLE IF EXISTS departament;

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

-- INCIDENCIA -> DEPARTAMENT
ALTER TABLE incidencia
    ADD CONSTRAINT fk_incidencia_departament
        FOREIGN KEY (departament_id)
            REFERENCES departament(departament_id);

-- INCIDENCIA -> TECNIC
ALTER TABLE incidencia
    ADD CONSTRAINT fk_incidencia_tecnic
        FOREIGN KEY (tecnic_id)
            REFERENCES tecnic(tecnic_id);

-- INCIDENCIA -> TIPOLOGIA
ALTER TABLE incidencia
    ADD CONSTRAINT fk_incidencia_tipologia
        FOREIGN KEY (tipologia_id)
            REFERENCES tipologia(tipologia_id);

-- ACTUACIO -> TECNIC
ALTER TABLE actuacio
    ADD CONSTRAINT fk_actuacio_tecnic
        FOREIGN KEY (tecnic_id)
            REFERENCES tecnic(tecnic_id);

-- ACTUACIO -> INCIDENCIA
ALTER TABLE actuacio
    ADD CONSTRAINT fk_actuacio_incidencia
        FOREIGN KEY (incidencia_id)
            REFERENCES incidencia(incidencia_id);

-- AQUI ES FICAN DADES A LES TAULES:

-- Departaments

INSERT INTO departament (nom) VALUES ('Informàtica');
INSERT INTO departament (nom) VALUES ('Anglès');
INSERT INTO departament (nom) VALUES ('Manteniment');
INSERT INTO departament (nom) VALUES ('Matemàtiques');
INSERT INTO departament (nom) VALUES ('Ciències Naturals');
INSERT INTO departament (nom) VALUES ('Física i Química');
INSERT INTO departament (nom) VALUES ('Educació Física');
INSERT INTO departament (nom) VALUES ('Administració');

-- Tipologia

INSERT INTO tipologia (nom) VALUES ('Maquinari');
INSERT INTO tipologia (nom) VALUES ('Programari');
INSERT INTO tipologia (nom) VALUES ('Xarxes');
INSERT INTO tipologia (nom) VALUES ('Impressió');
INSERT INTO tipologia (nom) VALUES ('Accés usuaris');
INSERT INTO tipologia (nom) VALUES ('Servidor');
INSERT INTO tipologia (nom) VALUES ('Seguretat');
INSERT INTO tipologia (nom) VALUES ('Audiovisuals');
INSERT INTO tipologia (nom) VALUES ('Connexió Internet');
INSERT INTO tipologia (nom) VALUES ('Manteniment general');
-- Tecnic

INSERT INTO tecnic (nom, cognom) VALUES ('Joan', 'Pérez');
INSERT INTO tecnic (nom, cognom) VALUES ('Maria', 'García');
INSERT INTO tecnic (nom, cognom) VALUES ('Arnau', 'López');
INSERT INTO tecnic (nom, cognom) VALUES ('Laia', 'Martínez');
INSERT INTO tecnic (nom, cognom) VALUES ('Pau', 'Soler');
INSERT INTO tecnic (nom, cognom) VALUES ('Marc', 'Ferrer');
INSERT INTO tecnic (nom, cognom) VALUES ('Clara', 'Vila');
INSERT INTO tecnic (nom, cognom) VALUES ('Jordi', 'Roca');
INSERT INTO tecnic (nom, cognom) VALUES ('Núria', 'Costa');
INSERT INTO tecnic (nom, cognom) VALUES ('Xavier', 'Puig');
INSERT INTO tecnic (nom, cognom) VALUES ('Sergi', 'Batlle');


INSERT INTO incidencia (departament_id, descripcio_incidencia, data_incidencia, data_final, prioritat, tecnic_id, tipologia_id) VALUES
((1, 'Incidència sense prioritat assignada - Revisar servidor', '2024-03-01', NULL, NULL, 1, 1),
(2, 'Problema amb la impressora del departament', '2024-03-02', NULL, NULL, 2, 3),

-- Incidències sense tècnic assignat (NULL)
(3, 'Incidència pendent d''assignar a tècnic', '2024-03-03', NULL, 'Alta', NULL, 2),
(4, 'Falla elèctrica a la sala de servidors', '2024-03-04', NULL, 'Alta', NULL, 7),
(1, 'Monitor trencat a l''oficina', '2024-03-05', NULL, 'Baixa', NULL, 3),

-- Incidències sense tipologia (NULL)
(2, 'Problema general per reportar', '2024-03-06', NULL, 'Mitja', 1, NULL),
(4, 'Incidència sense categoritzar', '2024-03-07', NULL, 'Baixa', 2, NULL),
(3, 'Ajuda amb programari nou', '2024-03-08', NULL, 'Mitja', 3, NULL),

-- Incidències amb tots els camps NULL (només descripció i departament)
(5, 'Incidència pendent de revisió completa', '2024-03-10', NULL, NULL, NULL, NULL),
(1, 'Reportar problema sense dades específiques', '2024-03-11', NULL, NULL, NULL, NULL),
(2, 'Incidència urgent sense assignar', '2024-03-12', NULL, NULL, NULL, NULL),

-- Combinacions variades de NULL
(3, 'Incidència amb prioritat però sense tècnic', '2024-03-13', NULL, 'Alta', NULL, 4),
(4, 'Incidència amb tècnic però sense prioritat', '2024-03-14', NULL, NULL, 1, 5),
(5, 'Incidència amb tipologia però sense prioritat', '2024-03-15', NULL, NULL, 2, 6),
(1, 'Incidència sense tècnic ni tipologia', '2024-03-16', NULL, 'Baixa', NULL, NULL),
(2, 'Incidència sense prioritat ni tipologia', '2024-03-17', NULL, NULL, 3, NULL);


-- Actuacions (en un futur)


