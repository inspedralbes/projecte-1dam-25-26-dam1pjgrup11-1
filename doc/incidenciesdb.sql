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

INSERT INTO incidencia (departament_id, usuari_id, descripcio_incidencia, data_incidencia, data_final, prioritat, estat, tecnic_id, tipologia_id) VALUES
(1,	11,	'PC aula 3 no arrenca',	'2026-04-25',	NULL,	'Alta','Oberta',	NULL,	NULL),
(1,	11,	'Problema login xarxa alumnes',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(1,	12,	'Fallada servidor virtual',	'2026-04-27',	NULL,	NULL,'Oberta',	NULL,	NULL),
(1,	13,	'Actualització Windows fallida',	'2026-04-28',	NULL,	NULL,'Oberta',	NULL,	NULL),
(1,	14,	'Tall DHCP aula informàtica',	'2026-04-29',	NULL,	NULL,'Oberta',	NULL,	NULL),
(2,	12,	'WiFi aula anglès inestable',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(2,	13,	'Ordinador professor anglès no encén',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(3,	14,	'Llums passadís no funcionen',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(3,	15,	'Porta aula tancament trencat',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(3,	16,	'Aire condicionat soroll excessiu',	'2026-04-27',	'2026-04-28',	'Baixa',	'Finalitzada',	5,	10),
(3,	16,	'Aire espallat', '2026-04-29',	'2026-05-01',	'Baixa',	'Finalitzada',	5,	10),
(4,	15,	'Projector aula matemàtiques defectuós',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(4,	16,	'Software calculadores error',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(4,	17,	'Impressora exàmens no imprimeix',	'2026-04-27',	NULL,	NULL,'Oberta',	NULL,	NULL),
(5,	17,	'Microscopi digital no connecta',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(5,	18,	'Ordinador laboratori molt lent',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(6,	18,	'Sensor temperatura error',	'2026-04-25',	NULL,	NULL, 'Oberta',	NULL,	NULL),
(6,	19,	'Programari simulació no obre',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(7,	19,	'Altaveus gimnàs distorsionen so',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(8,	20,	'Impressora secretaria bloquejada',	'2026-04-25',	'2026-04-26',	'Alta',	'Finalitzada',	4,	4),
(8,	20,	'Error gestió expedients alumnes',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(9,	11,	'Ordinador orientació molt lent',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(10, 12, 'Catàleg digital no respon',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(11, 13, 'Sistema matrícula amb errors',	'2026-04-24',	'2026-04-25',	'Alta',	'Finalitzada',	4,	5),
(12, 14, 'Email direcció no envia correus',	'2026-04-25',	NULL,	NULL,'Oberta',	NULL,	NULL),
(12, 15, 'Problema accés informes direcció',	'2026-04-26',	NULL,	NULL,'Oberta',	NULL,	NULL),
(2,	13,	'El Workbook no workbookea',	'2026-05-12',	NULL,	'Alta',	'En Curs',	5,	2),
(1,	11,	'Router aula 2 reinicis constants',	'2026-05-01',	NULL,	'Alta',	'En Curs',	1,	3),
(1,	12,	'Pantalla ordinador amb parpelleig',	'2026-05-02',	NULL,	NULL,'Oberta',	NULL,	NULL),
(1,	13,	'Error instal·lació antivirus',	'2026-05-02',	NULL,	'Mitja',	'En Curs',	2,	2),
(1,	14,	'Switch xarxa sense connexió',	'2026-05-03',	NULL,	'Alta',	'En Curs',	3,	3),
(2,	12,	'Projector aula anglès no detecta HDMI',	'2026-05-01',	NULL,	NULL,'Oberta',	NULL,	NULL),
(2,	13,	'Micròfon aula idiomes no funciona',	'2026-05-02',	NULL,	'Baixa',	'En Curs',	7,	8),
(2,	13,	'Accés Moodle professorat bloquejat',	'2026-05-03',	NULL,	'Alta',	'En Curs',	1,	5),
(3,	14,	'Persiana aula 1 encallada',	'2026-05-01',	NULL,	NULL,'Oberta',	NULL,	NULL),
(3,	15,	'Fuita aigua lavabo professors',	'2026-05-02',	NULL,	'Alta',	'En Curs',	5,	10),
(3,	16,	'Endoll laboratori sense corrent',	'2026-05-03',	NULL,	'Mitja',	'En Curs',	5,	10),
(3,	14,	'Pissarra digital despenjada',	'2026-05-03',	NULL,	NULL,'Oberta',	NULL,	NULL),
(4,	15,	'PC aula matemàtiques molt lent',	'2026-05-01',	NULL,	'Mitja',	'En Curs',	3,	1),
(4,	16,	'No funciona GeoGebra',	'2026-05-02',	NULL,	NULL,'Oberta',	NULL,	NULL),
(4,	17,	'Tall internet durant examen',	'2026-05-03',	NULL,	'Alta',	'En Curs',	1,	9),
(4,	15,	'Problema impressió exercicis',	'2026-05-04',	NULL,	'Mitja',	'En Curs',	4,	4),
(5,	17,	'Tauleta digital no carrega',	'2026-05-01',	NULL,	NULL,'Oberta',	NULL,	NULL),
(5,	18,	'Error software microscopia',	'2026-05-02',	NULL,	'Mitja',	'En Curs',	3,	2),
(5,	17,	'Servidor laboratori inaccessible',	'2026-05-03',	NULL,	'Alta',	'En Curs',	6,	6),
(6,	18,	'Projector laboratori sense imatge',	'2026-05-01',	NULL,	NULL,'Oberta',	NULL,	NULL),
(6,	19,	'PC simulacions es reinicia',	'2026-05-02',	NULL,	'Alta',	'En Curs',	2,	1),
(6,	18,	'Sensor pressió no sincronitza',	'2026-05-03',	NULL,	'Mitja',	'En Curs',	6,	1),
(7,	19,	'WiFi gimnàs no arriba correctament',	'2026-05-01',	NULL,	NULL,'Oberta',	NULL,	NULL),
(7,	20,	'Pantalla marcador electrònic apagada',	'2026-05-02',	NULL,	'Alta',	'En Curs',	7,	8),
(7,	19,	'Altaveu exterior sense so',	'2026-05-03',	NULL,	'Mitja',	'En Curs',	7,	8),
(8,	20,	'Escàner secretaria no detectat',	'2026-05-01',	NULL,	'Mitja',	'En Curs',	4,	1),
(8,	20,	'Error exportació PDF expedients',	'2026-05-02',	NULL,	NULL,'Oberta',	NULL,	NULL),
(8,	20,	'Connexió impressora xarxa fallida',	'2026-05-03',	NULL,	'Alta',	'En Curs',	1,	4),
(8,	20,	'Aplicació nòmines bloquejada',	'2026-05-04',	NULL,	'Alta',	'En Curs',	2,	2),
(9,	11,	'Càmera videoconferència no funciona',	'2026-05-01',	NULL,	NULL,'Oberta',	NULL,	NULL),
(9,	11,	'Problemes accés correu orientació',	'2026-05-02',	NULL,	'Alta',	'En Curs',	1,	5),
(9,	11,	'PC orientació sense àudio',	'2026-05-03',	NULL,	'Baixa',	'En Curs',	3,	1),
(10,	12,	'Lector codis barres no respon',	'2026-05-01',	NULL,	'Mitja',	'En Curs',	4,	1),
(10,	12,	'Catàleg online molt lent',	'2026-05-02',	NULL,	'Alta',	'En Curs',	7,	6),
(10,	12,	'Impressora biblioteca sense tinta',	'2026-05-03',	NULL,	NULL,'Oberta',	NULL,	NULL),
(11,	13,	'Error autenticació usuaris matrícula',	'2026-05-01',	NULL,	'Alta',	'En Curs',	4,	5),
(11,	13,	'Aplicació administrativa es tanca sola',	'2026-05-02',	NULL,	'Mitja',	'En Curs',	2,	2),
(11,	13,	'Servidor documents no accessible',	'2026-05-03',	NULL,	NULL,'Oberta',	NULL,	NULL),
(12,	14,	'Problemes sincronització correu mòbil',	'2026-05-01',	NULL,	'Mitja',	'En Curs',	1,	5),
(12,	15,	'Accés carpetes compartides denegat',	'2026-05-02',	NULL,	'Alta',	'En Curs',	2,	5),
(12,	14,	'Videotrucades amb talls continus',	'2026-05-03',	NULL,	NULL,'Oberta',	NULL,	NULL),
(12,	15,	'Pantalla sala reunions sense senyal',	'2026-05-04',	NULL,	'Alta',	'En Curs',	7,	8),
(1,	11,	'Error arrencada Linux aula 5',	'2026-05-05',	NULL,	'Alta',	'En Curs',	1,	2),
(1,	12,	'Cable xarxa malmès aula 2',	'2026-05-05',	NULL,	NULL,'Oberta',	NULL,	NULL),
(2,	13,	'Auriculars aula idiomes no funcionen',	'2026-05-05',	NULL,	'Mitja',	'En Curs',	7,	8),
(2,	12,	'Problema accés plataforma online',	'2026-05-06',	NULL,	'Alta',	'En Curs',	2,	5),
(3,	14,	'Aula sense il·luminació parcial',	'2026-05-05',	NULL,	NULL,'Oberta',	NULL,	NULL),
(3,	15,	'Finestra biblioteca no tanca bé',	'2026-05-06',	NULL,	'Baixa',	'En Curs',	5,	10),
(4,	16,	'Ordinador aula 4 bloquejat',	'2026-05-05',	NULL,	'Mitja',	'En Curs',	3,	1),
(4,	17,	'Error connexió impressora departament',	'2026-05-06',	NULL,	'Alta',	'En Curs',	4,	4),
(5,	17,	'Problema connexió projector laboratori',	'2026-05-05',	NULL,	NULL,'Oberta',	NULL,	NULL),
(5,	18,	'Tauleta professor no sincronitza',	'2026-05-06',	NULL,	'Baixa',	'En Curs',	3,	2),
(6,	18,	'Sistema sensors laboratori lent',	'2026-05-05',	NULL,	'Alta',	'En Curs',	6,	1),
(6,	19,	'Error actualització software química',	'2026-05-06',	NULL,	NULL,'Oberta',	NULL,	NULL),
(7,	19,	'Micròfon portàtil sense bateria',	'2026-05-05',	NULL,	'Baixa',	'En Curs',	7,	8),
(8,	20,	'PC recepció no detecta impressora',	'2026-05-05',	NULL,	'Mitja',	'En Curs',	4,	4),
(8,	20,	'Error aplicació comptabilitat',	'2026-05-06',	NULL,	'Alta',	'En Curs',	2,	2),
(9,	11,	'Connexió VPN orientació fallida',	'2026-05-05',	NULL,	'Alta',	'En Curs',	1,	9),
(10,	12,	'Ordinador consulta usuaris molt lent',	'2026-05-05',	NULL,	NULL,'Oberta',	NULL,	NULL),
(11,	13,	'Sistema signatures digitals no respon',	'2026-05-05',	NULL,	'Alta',	'En Curs',	4,	5),
(12,	14,	'Projector sala reunions desenfocat',	'2026-05-05',	NULL,	'Baixa',	'En Curs',	7,	8),
(12,	15,	'Error sincronització calendari compartit',	'2026-05-06',	NULL,	NULL,'Oberta',	NULL,	NULL),
(5,	18,	'Se ha roto el ordenador y no puedo enseñar la presentación sobre los enlaces covalentes',	'2026-05-13',	'2026-05-13',	'Alta',	'Finalitzada',	8,	8),
(10,	13,	'No funciona la impresero de la biblioteca',	'2026-05-13',	'2026-05-13',	'Mitja',	'Finalitzada',	8,	4),
(1,	11,	'Servidor còpies de seguretat sense espai disponible',	'2026-05-07',	NULL,	'Alta',	'En Curs',	6,	6),
(1,	12,	'Escàner aula informàtica no detectat pel sistema',	'2026-05-07',	NULL,	NULL,'Oberta',	NULL,	NULL),
(2,	13,	'Pantalla interactiva no respon al tacte',	'2026-05-07',	NULL,	'Mitja',	'En Curs',	7,	8),
(3,	14,	'Humitats detectades sostre passadís principal',	'2026-05-07',	NULL,	'Alta',	'En Curs',	5,	10),
(3,	15,	'Soroll estrany sistema ventilació aula 6',	'2026-05-08',	NULL,	NULL,'Oberta',	NULL,	NULL),
(4,	16,	'Llicència programari estadística caducada',	'2026-05-07',	NULL,	'Alta',	'En Curs',	2,	2),
(4,	17,	'Pissarra digital amb retard d’escriptura',	'2026-05-08',	NULL,	'Baixa',	'En Curs',	3,	8),
(5,	17,	'Impressora etiquetes laboratori no imprimeix',	'2026-05-07',	NULL,	NULL,'Oberta',	NULL,	NULL),
(5,	18,	'Error sincronització dades estació meteorològica',	'2026-05-08',	NULL,	'Alta',	'En Curs',	6,	2),
(6,	18,	'Tall elèctric intermitent laboratori química',	'2026-05-07',	NULL,	'Alta',	'En Curs',	5,	10),
(7,	19,	'Cronòmetre electrònic no guarda registres',	'2026-05-07',	NULL,	NULL,'Oberta',	NULL,	NULL),
(8,	20,	'No es poden generar informes PDF',	'2026-05-07',	NULL,	'Mitja',	'En Curs',	4,	2),
(10,	12,	'Terminal préstecs bloquejat en iniciar sessió',	'2026-05-08',	NULL,	'Alta',	'En Curs',	1,	5),
(11,	13,	'Error enviament massiu correus famílies',	'2026-05-08',	NULL,	'Alta',	'En Curs',	2,	5),
(12,	15,	'Sistema videovigilància sense connexió remota',	'2026-05-08',	NULL,	'Alta',	'En Curs',	6,	7);

-- ACTUACIONS

INSERT INTO actuacio (
    incidencia_id,
    tecnic_id,
    temps,
    data_actuacio,
    descripcio_actuacio,
    visible
) VALUES
(10, 5, '30', '2026-04-28', 'Revisió aire condicionat', 1),
(10, 5, '45', '2026-04-29', 'Reparació aire condicionat', 1),
(11, 5, '60', '2026-05-02', 'Reparació filtre aire condicionat', 1),
(19, 4, '45', '2026-04-26', 'Desbloqueig impressora secretaria', 1),
(20, 4, '20', '2026-04-26', 'Desbloqueig de la impresora', 1),
(23, 4, '60', '2026-04-25', 'Correcció errors sistema matrícula', 1),
(23, 4, '40', '2026-04-25', 'Validació final del sistema', 1),
(24, 4, '30', '2026-04-25', 'Solucionar el sistema de màtricules', 1),
(27, 1, '70', '2026-05-01', 'Diagnòstic router aula 2', 1),
(27, 1, '50', '2026-05-01', 'Reinici i configuració inicial', 1),
(29, 2, '50', '2026-05-02', 'Reinstal·lació antivirus', 1),
(30, 3, '80', '2026-05-03', 'Substitució switch defectuós', 1),
(32, 7, '35', '2026-05-02', 'Revisió micròfon aula idiomes', 1),
(33, 1, '60', '2026-05-03', 'Desbloqueig accés Moodle', 1),
(33, 1, '30', '2026-05-03', 'Verificació permisos usuaris', 1),
(35, 5, '130', '2026-05-02', 'Reparació fuita lavabo professors', 1),
(36, 5, '55', '2026-05-03', 'Revisió instal·lació elèctrica', 1),
(38, 3, '75', '2026-05-01', 'Optimització ordinador aula', 1),
(40, 1, '30', '2026-05-03', 'Diagnòstic tall internet', 1),
(41, 4, '45', '2026-05-04', 'Configuració impressora exercicis', 1),
(43, 3, '65', '2026-05-02', 'Actualització software microscopia', 1),
(44, 6, '90', '2026-05-03', 'Diagnòstic servidor laboratori', 1),
(44, 6, '60', '2026-05-03', 'Recuperació serveis i dades', 1),
(46, 2, '50', '2026-05-03', 'Anàlisi reinicis PC simulacions', 1),
(47, 6, '85', '2026-05-03', 'Sincronització sensor pressió', 1),
(49, 7, '40', '2026-05-02', 'Reparació marcador electrònic', 1),
(50, 7, '35', '2026-05-03', 'Reconnexió altaveu exterior', 1),
(53, 1, '70', '2026-05-03', 'Configuració impressora xarxa', 1),
(54, 2, '95', '2026-05-04', 'Desbloqueig aplicació nòmines', 1),
(56, 1, '55', '2026-05-02', 'Recuperació accés correu', 1),
(59, 7, '80', '2026-05-02', 'Optimització catàleg online', 1),
(61, 4, '70', '2026-05-01', 'Revisió autenticació usuaris', 1),
(62, 2, '50', '2026-05-02', 'Actualització aplicació administrativa', 1),
(65, 2, '60', '2026-05-02', 'Permisos carpetes compartides', 1),
(68, 1, '75', '2026-05-05', 'Reparació arrencada Linux', 1),
(71, 2, '65', '2026-05-06', 'Revisió plataforma online', 1),
(74, 3, '60', '2026-05-05', 'Desbloqueig ordinador aula 4', 1),
(78, 6, '90', '2026-05-05', 'Optimització sistema sensors', 1),
(82, 2, '85', '2026-05-06', 'Correcció aplicació comptabilitat', 1),
(83, 1, '75', '2026-05-05', 'Reconfiguració VPN orientació', 1),
(88, 8, '60', '2026-05-13', 'Substitució hardware defectuós', 1),
(88, 8, '45', '2026-05-13', 'Proves i validació final', 1),
(89, 8, '15', '2026-05-13', 'Tancament incidència', 1),
(90, 6, '120', '2026-05-07', 'Alliberament espai còpies seguretat', 1),
(92, 7, '50', '2026-05-07', 'Calibració pantalla interactiva', 1),
(93, 5, '100', '2026-05-07', 'Revisió humitats sostre', 1),
(95, 2, '35', '2026-05-07', 'Renovació llicència programari', 1),
(96, 3, '40', '2026-05-08', 'Ajust pissarra digital', 1),
(98, 6, '70', '2026-05-08', 'Sincronització estació meteorològica', 1),
(99, 5, '140', '2026-05-07', 'Revisió sistema elèctric laboratori', 1),
(101, 4, '55', '2026-05-07', 'Configuració generació PDFs', 1),
(102, 1, '60', '2026-05-08', 'Desbloqueig terminal préstecs', 1),
(103, 2, '75', '2026-05-08', 'Correcció enviament massiu correus', 1),
(104, 6, '130', '2026-05-08', 'Restabliment videovigilància remota', 1);
