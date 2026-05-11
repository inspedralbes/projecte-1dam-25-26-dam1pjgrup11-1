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
DROP TABLE IF EXISTS usuari;

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
                            estat ENUM('Oberta', 'En Curs', 'Finalitzada') DEFAULT 'Oberta',
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

CREATE TABLE usuari (
                        usuari_id INT AUTO_INCREMENT PRIMARY KEY,
                        email VARCHAR(255) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        rol ENUM('admin', 'tecnic', 'professor') NOT NULL
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

-- DEPARTAMENTS (12)
INSERT INTO departament (nom) VALUES
                                  ('Informàtica'),
                                  ('Anglès'),
                                  ('Manteniment'),
                                  ('Matemàtiques'),
                                  ('Ciències Naturals'),
                                  ('Física i Química'),
                                  ('Educació Física'),
                                  ('Administració'),
                                  ('Orientació'),
                                  ('Biblioteca'),
                                  ('Secretaria'),
                                  ('Direcció');

-- TIPUS (igual)
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

-- TÈCNICS (8)
INSERT INTO tecnic (nom, cognom) VALUES
                                     ('Joan', 'Pérez'),
                                     ('Maria', 'García'),
                                     ('Arnau', 'López'),
                                     ('Laia', 'Martínez'),
                                     ('Pau', 'Soler'),
                                     ('Marc', 'Ferrer'),
                                     ('Clara', 'Vila'),
                                     ('Jordi', 'Roca');

-- ======================
-- INCIDÈNCIES (30)
-- ======================

INSERT INTO incidencia (
    incidencia_id,
    departament_id,
    descripcio_incidencia,
    data_incidencia,
    data_final,
    prioritat,
    estat,
    tecnic_id,
    tipologia_id
) VALUES
      (NULL,1,'PC aula 1 no arrenca','2026-04-01',NULL,'Alta','Oberta',1,1),
      (NULL,2,'WiFi inestable','2026-04-02',NULL,'Alta','Oberta',2,3),
      (NULL,3,'Projector avariat','2026-04-03','2026-04-06','Mitja','Finalitzada',3,8),
      (NULL,4,'Impressora bloquejada','2026-04-04',NULL,'Baixa','Oberta',4,4),
      (NULL,5,'Servidor lent','2026-04-05','2026-04-07','Alta','Finalitzada',5,6),
      (NULL,6,'Xarxa caiguda','2026-04-06',NULL,NULL,'Oberta',NULL,3),
      (NULL,7,'Altaveus no funcionen','2026-04-07',NULL,'Mitja','Oberta',6,8),
      (NULL,8,'Error accés usuari','2026-04-08',NULL,'Alta','Oberta',7,5),
      (NULL,9,'Pantalla negra aula','2026-04-09',NULL,NULL,'Oberta',NULL,1),
      (NULL,10,'Plataforma educativa lenta','2026-04-10',NULL,'Mitja','Oberta',2,2),
      (NULL,11,'Cable desconnectat','2026-04-11',NULL,NULL,'Oberta',NULL,NULL),
      (NULL,12,'Error impressió massiva','2026-04-12',NULL,'Baixa','Oberta',4,4),
      (NULL,1,'Equip no encén','2026-04-13',NULL,NULL,'Oberta',NULL,1),
      (NULL,2,'Problema connexió WiFi','2026-04-14','2026-04-15','Alta','Finalitzada',2,3),
      (NULL,3,'Micròfons no funcionen','2026-04-15',NULL,NULL,'Oberta',NULL,8),
      (NULL,4,'Paper encallat','2026-04-16',NULL,NULL,'Oberta',NULL,4),
      (NULL,5,'Backup fallat','2026-04-17','2026-04-18','Alta','Finalitzada',5,6),
      (NULL,6,'Internet lent','2026-04-18',NULL,NULL,'Oberta',NULL,9),
      (NULL,7,'Sistema audio error','2026-04-19',NULL,'Mitja','Oberta',6,8),
      (NULL,8,'Accés bloquejat','2026-04-20',NULL,'Alta','Oberta',7,5),
      (NULL,9,'Monitor trencat','2026-04-21',NULL,NULL,'Oberta',NULL,1),
      (NULL,10,'App no respon','2026-04-22',NULL,NULL,'Oberta',NULL,2),
      (NULL,11,'Cable xarxa defectuós','2026-04-23',NULL,NULL,'Oberta',NULL,NULL),
      (NULL,12,'Servidor caigut','2026-04-24','2026-04-25','Alta','Finalitzada',5,6),
      (NULL,1,'Teclat no funciona','2026-04-25',NULL,NULL,'Oberta',NULL,1),
      (NULL,2,'WiFi aula 3 lenta','2026-04-26',NULL,NULL,'Oberta',NULL,3),
      (NULL,3,'Projector sense senyal','2026-04-27',NULL,'Mitja','Oberta',3,8),
      (NULL,4,'Impressora sense tinta','2026-04-28',NULL,'Baixa','Oberta',4,4),
      (NULL,5,'Error base dades','2026-04-29','2026-04-30','Alta','Finalitzada',5,6);
-- ======================
-- ACTUACIONS (50)
-- ======================

INSERT INTO actuacio (incidencia_id, tecnic_id, temps, data_actuacio, descripcio_actuacio, visible)
VALUES
    (1,1,'120','2026-04-01','Reinstal·lació SO',1),
    (1,2,'30','2026-04-01','Diagnosi inicial',1),
    (1,1,'45','2026-04-02','Substitució disc',1),

    (2,2,'60','2026-04-02','Configuració router',1),
    (2,3,'40','2026-04-02','Revisió senyal',1),

    (3,3,'90','2026-04-03','Canvi projector',1),

    (4,4,'30','2026-04-04','Reposició paper',1),

    (5,5,'180','2026-04-05','Optimització servidor',1),
    (5,5,'60','2026-04-06','Neteja logs',1),

    (6,6,'50','2026-04-06','Reinici xarxa',1),
    (6,2,'40','2026-04-06','Test connexió',1),

    (7,6,'70','2026-04-07','Reparació altaveus',1),

    (8,7,'120','2026-04-08','Reset usuaris',1),

    (10,2,'60','2026-04-10','Reinici sistema',1),

    (12,4,'45','2026-04-12','Reparació impressora',1),

    (13,1,'90','2026-04-13','Substitució font alimentació',1),

    (14,2,'60','2026-04-14','Configuració WiFi',1),

    (17,5,'100','2026-04-17','Backup restaurat',1),

    (19,6,'80','2026-04-19','Reparació audio',1),

    (20,7,'110','2026-04-20','Desbloqueig comptes',1),

    (24,5,'130','2026-04-24','Reparació servidor',1),

    (27,3,'60','2026-04-27','Revisió projector',1),

    (28,4,'40','2026-04-28','Canvi tinta',1),

    (29,5,'150','2026-04-29','Correcció BBDD',1),

-- extra actuacions per incidències amb més activitat
    (1,3,'20','2026-04-02','Prova final',1),
    (1,2,'25','2026-04-03','Validació',1),
    (2,1,'30','2026-04-03','Test final',1),
    (5,2,'45','2026-04-06','Optimització extra',1),
    (6,1,'35','2026-04-07','Revisió final',1),
    (8,2,'55','2026-04-09','Control accés',1),
    (20,6,'60','2026-04-21','Auditoria',1),
    (24,5,'40','2026-04-25','Revisió backup',1);

INSERT INTO usuari (email, password, rol) VALUES
                                              ('joan.perez@incidencies.cat', 'pass123', 'tecnic'),
                                              ('maria.garcia@incidencies.cat', 'pass123', 'tecnic'),
                                              ('arnau.lopez@incidencies.cat', 'pass123', 'tecnic'),
                                              ('laia.martinez@incidencies.cat', 'pass123', 'tecnic'),
                                              ('pau.soler@incidencies.cat', 'pass123', 'tecnic'),
                                              ('marc.ferrer@incidencies.cat', 'pass123', 'tecnic'),
                                              ('clara.vila@incidencies.cat', 'pass123', 'tecnic'),
                                              ('jordi.roca@incidencies.cat', 'pass123', 'tecnic');

INSERT INTO usuari (email, password, rol) VALUES
                                              ('admin1@incidencies.cat', 'admin123', 'admin'),
                                              ('admin2@incidencies.cat', 'admin123', 'admin');


INSERT INTO usuari (email, password, rol) VALUES
                                              ('prof.informatica1@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.informatica2@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.angles@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.manteniment@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.matematiques1@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.matematiques2@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.naturals@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.fisicaquimica@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.educaciofisica1@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.educaciofisica2@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.administracio@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.orientacio@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.biblioteca@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.secretaria@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.direccio@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.extra1@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.extra2@incidencies.cat', 'pass123', 'professor'),
                                              ('prof.extra3@incidencies.cat', 'pass123', 'professor');