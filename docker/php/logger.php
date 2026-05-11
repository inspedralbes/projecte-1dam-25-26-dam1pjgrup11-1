<?php
require 'vendor/autoload.php';  
$client = new MongoDB\Client("mongodb://root:example@mongo:27017");
date_default_timezone_set('Europe/Madrid');

$collection = $client->demo->users;

// Obtenim l'adreça IP origen de la petció.
// Teniu informació sobre l'operador ?? a
// https://phpsensei.es/operadores-en-php-null-coalesce-operator/
// "Si no es pot obtenir, es fa servir 'unknown' com a valor per defecte"
$start = microtime(true);
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$hora = date("Y-m-d H:i:s");
$url = $_SERVER['REQUEST_URI'] ?? 'unknown';
$navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$metode = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
$temps_resposta_ms = round((microtime(true) - $start) * 1000, 2);
//Usuari id

$collection->insertOne([
    'ip_origin' => $ip,
    'date' => date("Y-m-d H:i:s"),
    'url' => $url,
    'navegador' => $navegador,
    'temps_resposta_ms' => $temps_resposta_ms,
    'metode' => $metode
]);



// Obtenir tots els documents de la col·lecció users de la BBDD demo
// $collection = $client->demo->users; #no cal, ja que ho hem fet abans
$documents = $collection->find();
