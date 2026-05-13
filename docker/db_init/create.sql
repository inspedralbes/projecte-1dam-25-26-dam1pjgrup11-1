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

-- USUARIS
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

-- TÈCNICS
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

-- ======================
-- INFORMÀTICA (5)
-- ======================
(1,11,'PC aula 3 no arrenca','2026-04-25',NULL,NULL,'Oberta',NULL,NULL),
(1,11,'Problema login xarxa alumnes','2026-04-26',NULL,'Mitja','En Curs',1,5),
(1,12,'Fallada servidor virtual','2026-04-27',NULL,NULL,'Oberta',NULL,NULL),
(1,13,'Actualització Windows fallida','2026-04-28',NULL,'Alta','En Curs',2,2),
(1,14,'Tall DHCP aula informàtica','2026-04-29',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- ANGLÈS (2)
-- ======================
(2,12,'WiFi aula anglès inestable','2026-04-25',NULL,NULL,'Oberta',NULL,NULL),
(2,13,'Ordinador professor anglès no encén','2026-04-26',NULL,'Baixa','En Curs',2,1),

-- ======================
-- MANTENIMENT (3)
-- ======================
(3,14,'Llums passadís no funcionen','2026-04-25',NULL,NULL,'Oberta',NULL,NULL),
(3,15,'Porta aula tancament trencat','2026-04-26',NULL,'Alta','En Curs',5,10),
(3,16,'Aire condicionat soroll excessiu','2026-04-27','2026-04-28','Baixa','Finalitzada',5,10),

-- ======================
-- MATEMÀTIQUES (3)
-- ======================
(4,15,'Projector aula matemàtiques defectuós','2026-04-25',NULL,NULL,'Oberta',NULL,NULL),
(4,16,'Software calculadores error','2026-04-26',NULL,'Mitja','En Curs',3,2),
(4,17,'Impressora exàmens no imprimeix','2026-04-27',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- CIÈNCIES NATURALS (2)
-- ======================
(5,17,'Microscopi digital no connecta','2026-04-25',NULL,'Alta','En Curs',3,1),
(5,18,'Ordinador laboratori molt lent','2026-04-26',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- FÍSICA I QUÍMICA (2)
-- ======================
(6,18,'Sensor temperatura error','2026-04-25',NULL,'Alta','En Curs',6,1),
(6,19,'Programari simulació no obre','2026-04-26',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- EDUCACIÓ FÍSICA (1)
-- ======================
(7,19,'Altaveus gimnàs distorsionen so','2026-04-25',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- ADMINISTRACIÓ (2)
-- ======================
(8,20,'Impressora secretaria bloquejada','2026-04-25','2026-04-26','Alta','Finalitzada',4,4),
(8,20,'Error gestió expedients alumnes','2026-04-26',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- ORIENTACIÓ (1)
-- ======================
(9,11,'Ordinador orientació molt lent','2026-04-25',NULL,NULL,'Oberta',NULL,NULL),

-- ======================
-- BIBLIOTECA (1)
-- ======================
(10,12,'Catàleg digital no respon','2026-04-25',NULL,'Alta','En Curs',7,6),

-- ======================
-- SECRETARIA (1)
-- ======================
(11,13,'Sistema matrícula amb errors','2026-04-24','2026-04-25','Alta','Finalitzada',4,5),

-- ======================
-- DIRECCIÓ (2)
-- ======================
(12,14,'Email direcció no envia correus','2026-04-25',NULL,'Alta','En Curs',1,5),
(12,15,'Problema accés informes direcció','2026-04-26',NULL,NULL,'Oberta',NULL,NULL);

-- ACTUACIONS

-- ACTUACIONS

INSERT INTO actuacio (
    incidencia_id,
    tecnic_id,
    temps,
    data_actuacio,
    descripcio_actuacio,
    visible
) VALUES

      (1,1,'60','2026-04-25','Diagnosi PC aula 3','1'),
      (2,2,'45','2026-04-26','Revisió login xarxa i permisos','1'),
      (3,3,'90','2026-04-27','Reinici i restauració servidor virtual','1'),
      (4,2,'120','2026-04-28','Reinstal·lació actualització Windows','1'),
      (5,3,'50','2026-04-29','Reconfiguració DHCP aula informàtica','1'),

      (6,5,'40','2026-04-25','Revisió xarxa WiFi aula anglès','1'),
      (7,2,'30','2026-04-26','Canvi cable alimentació ordinador','1'),

      (8,5,'60','2026-04-25','Reparació sistema enllumenat','1'),
      (9,5,'80','2026-04-26','Reparació porta aula i ajust','1'),
      (10,5,'100','2026-04-27','Substitució components aire condicionat','1'),

      (11,3,'70','2026-04-25','Revisió projector matemàtiques','1'),
      (12,3,'55','2026-04-26','Correcció error programari calculadores','1'),
      (13,4,'65','2026-04-27','Reparació impressora exàmens','1'),

      (14,3,'90','2026-04-25','Reparació microscopi digital','1'),
      (15,6,'45','2026-04-26','Optimització ordinador laboratori','1'),

      (16,6,'80','2026-04-25','Calibració sensor temperatura','1'),
      (17,3,'50','2026-04-26','Reinstal·lació programari simulació','1'),

      (18,7,'35','2026-04-25','Substitució altaveus gimnàs','1'),

      (19,4,'60','2026-04-25','Reparació impressora secretaria','1'),
      (20,4,'75','2026-04-26','Correcció error expedients','1'),

      (21,7,'40','2026-04-25','Neteja sistema orientació','1'),

      (22,7,'55','2026-04-25','Reparació servidor biblioteca digital','1'),

      (23,4,'90','2026-04-25','Revisió sistema matrícula','1'),

      (24,1,'65','2026-04-25','Reparació servidor email direcció','1'),
      (25,2,'50','2026-04-26','Revisió accés informes direcció','1');