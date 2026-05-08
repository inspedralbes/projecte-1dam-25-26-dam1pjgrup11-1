<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://root:example@mongo:27017");

$collection = $client->demo->users;

// Obtenim l'adreça IP origen de la petció.
// Teniu informació sobre l'operador ?? a
// https://phpsensei.es/operadores-en-php-null-coalesce-operator/
// "Si no es pot obtenir, es fa servir 'unknown' com a valor per defecte"

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$hora = date("H:i:s");
$url = $_SERVER['REQUEST_URI'] ?? 'unknown';
$navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$metode = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
//Usuari id
//temps resposta

$collection->insertOne([
    'ip_origin' => $ip,
    'date' => date("Y-m-d H:i:s"),
    'url' => $url,
    'navegador' => $navegador,
    'metode' => $metode
]);

echo "Dades inserides a demo .\n";


// Obtenir tots els documents de la col·lecció users de la BBDD demo
// $collection = $client->demo->users; #no cal, ja que ho hem fet abans
$documents = $collection->find();

foreach ($documents as $document) {
    echo "<p>";
    echo "<strong>Fecha:</strong> "
        . htmlspecialchars($document['date'] ?? "x");
    echo "<br>";
    echo "<strong>IP:</strong> "
        . htmlspecialchars($document['ip_origin'] ?? "x");
    echo "<br>";
    echo "<strong>URL:</strong> "
        . htmlspecialchars($document['url'] ?? "x");
    echo "<br>";
    echo "<strong>Método:</strong> "
        . htmlspecialchars($document['metode'] ?? "x");
    echo "<br>";
    echo "<strong>Navegador:</strong> "
        . htmlspecialchars($document['navegador'] ?? "x");
    echo "</p><hr>";
}