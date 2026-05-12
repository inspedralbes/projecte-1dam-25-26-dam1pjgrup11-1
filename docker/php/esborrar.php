<?php
require_once 'connexio.php';

$id = $_POST["id"];

$sentencia = $conn->prepare("
    DELETE FROM incidencia
    WHERE incidencia_id = ?
");

$sentencia->bind_param("i", $id);

$sentencia->execute();

header("Location: llistar_total.php");
exit;
?>