<?php
require_once 'connexio.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
if(($_SESSION['rol'] !== 'admin')){
    if(($_SESSION['rol'] == 'professor')){
        header("Location: professor.php");
        exit;
    }elseif(($_SESSION['rol'] == 'tecnic')){
        header("Location: tecnic.php");
        exit;
    }else{
        header("Location: index.php");
        exit;
    }

}

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