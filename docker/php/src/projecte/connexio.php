<?php
$host = "db";
$user = "a25eduheryag_usuari";
$password = getenv('DB_PASSWORD') ?: '';
$db_name = "a25eduheryag_DAM1PJgrup11";
$mysqli = new mysqli($host, $user, $password, $db_name);
if ($mysqli->connect_errno) {
    echo "Error a la conexió a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
return $mysqli;