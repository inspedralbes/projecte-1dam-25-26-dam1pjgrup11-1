<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://root:example@mongo:27017");
date_default_timezone_set('Europe/Madrid');
$collection = $client->demo->users;

$start = microtime(true);

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$url = $_SERVER['REQUEST_URI'] ?? 'unknown';
$navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$metode = $_SERVER['REQUEST_METHOD'] ?? 'unknown';

$temps_resposta_ms = round((microtime(true) - $start) * 1000, 2);

$usuari_id = $_SESSION['user_id'] ?? null;
$usuari_email = $_SESSION['user'] ?? 'Anònim';
$rol = $_SESSION['rol'] ?? 'desconegut';

$collection->insertOne([
    'usuari_id' => $usuari_id,
    'usuari_email' => $usuari_email,
    'rol' => $rol,
    'ip_origin' => $ip,
    'date' => date("Y-m-d H:i:s"),
    'url' => $url,
    'navegador' => $navegador,
    'temps_resposta_ms' => $temps_resposta_ms,
    'metode' => $metode
]);

$documents = $collection->find();