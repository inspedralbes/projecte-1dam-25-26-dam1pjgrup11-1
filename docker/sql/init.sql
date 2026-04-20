-- Script d'inicialització de la base de dades
-- S'executa automàticament quan MySQL s'inicia per primera vegada

USE inventari;

-- Taula d'ordinadors
CREATE TABLE IF NOT EXISTS ordinadors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_insercio DATETIME NOT NULL,
    ram_gb INT NOT NULL,
    tipus_cpu VARCHAR(100) NOT NULL,
    nom_persona VARCHAR(100) NOT NULL
);

-- Dades de prova
INSERT INTO ordinadors (data_insercio, ram_gb, tipus_cpu, nom_persona) VALUES
(NOW(), 16, 'Intel Core i5-12400', 'Maria García'),
(NOW(), 32, 'AMD Ryzen 7 5800X', 'Joan Martínez'),
(NOW(), 8, 'Intel Core i3-10100', 'Laura Sánchez');
