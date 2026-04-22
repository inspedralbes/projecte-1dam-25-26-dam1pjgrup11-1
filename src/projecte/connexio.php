<?php
$host = "localhost";
$usuario = "a25eduheryag_usuari";
$contrasenia = getenv('DB_PASSWORD') ?: '';
$base_de_datos = "a25eduheryag_DAM1PJgrup11";
$mysqli = new mysqli($host, $usuario, $contrasenia, $base_de_datos);
if ($mysqli->connect_errno) {
    echo "Error a la conexió a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
return $mysqli;