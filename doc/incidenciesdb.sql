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
    data_incidencia DATE DEFAULT CURRENT_TIMESTAMP,
    data_final DATE,
    prioritat ENUM('Alta', 'Mitja', 'Baixa'),
    tecnic_id INT(11),
    tipologia_id INT(11)
);

CREATE TABLE actuacio (
    actuacio_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    incidencia_id INT(11),
    tecnic_id INT(11),
    temps INT(6),
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
