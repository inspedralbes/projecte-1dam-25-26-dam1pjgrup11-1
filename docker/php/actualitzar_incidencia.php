<?php
require_once 'connexio.php';

$id = $_POST["id"] ?? null;
$prioritat = $_POST["prioritat"] ?? null;
$tecnic_id = $_POST["tecnic_id"] ?? null;
$tipologia_id = $_POST["tipologia_id"] ?? null;
$estat = $_POST["estat"] ?? null;

$sentencia = $conn->prepare(
    "UPDATE incidencia
    SET prioritat = ?, tecnic_id = ?, tipologia_id = ?, estat = ?
    WHERE incidencia_id = ?"
);

$sentencia->bind_param("siisi", $prioritat, $tecnic_id, $tipologia_id, $estat, $id);

$sentencia->execute();

header("Location: buscar_id.php?incidencia_id=" . $id);
exit;
?>