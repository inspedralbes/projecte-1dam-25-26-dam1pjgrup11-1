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
GRANT ALL PRIVILEGES ON persones.* TO 'usuari'@'%';
FLUSH PRIVILEGES;


-- Després de crear la base de dades, cal seleccionar-la per treballar-hi
USE persones;


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
