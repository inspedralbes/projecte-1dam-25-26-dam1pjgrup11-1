<?php
require_once 'connexio.php';

$id = $_POST["id"];
$prioritat = $_POST["prioritat"];
$tecnic_id = $_POST["tecnic_id"];
$tipologia_id = $_POST["tipologia_id"];
$estat = $_POST["estat"];

$sentencia = $conn->prepare("
    UPDATE incidencia
    SET prioritat = ?, tecnic_id = ?, tipologia_id = ?, estat = ?
    WHERE incidencia_id = ?
");

$sentencia->bind_param("siisi", $prioritat, $tecnic_id, $tipologia_id, $estat, $id);

$sentencia->execute();

header("Location: buscar_id.php?incidencia_id=" . $id);
exit;
?>