<?php

require_once 'connexio.php';
require_once 'header.php';

$incidencia_id = $_GET['incidencia_id'] ?? $_POST['incidencia_id'] ?? null;
$tecnic_id = $_GET['tecnic_id'] ?? $_POST['tecnic_id'] ?? null;

/**
 * Crear una actuació
 */
function crear_actuacio($conn)
{
    $incidencia_id = $_POST['incidencia_id'] ?? null;
    $tecnic_id = $_POST['tecnic_id'] ?? null;

    $descripcio = $_POST['descripcio_actuacio'] ?? '';
    $visible = isset($_POST['visible']) ? 1 : 0;
    $finalitzada = isset($_POST['finalitzada']) ? 1 : 0;
    $temps = $_POST['temps'] ?? 0;
    $data_actuacio = $_POST['data_actuacio'] ?? date('Y-m-d');

    if (empty($descripcio)) {
        echo "<p class='error'>La descripció és obligatòria.</p>";
        return;
    }

    if ($finalitzada == 1) {

        $sql_update = "UPDATE incidencia
                       SET data_final = NOW()
                       WHERE incidencia_id = ?";

        $stmt_up = $conn->prepare($sql_update);
        $stmt_up->bind_param("i", $incidencia_id);
        $stmt_up->execute();
    }

    $sql = "INSERT INTO actuacio
            (incidencia_id, tecnic_id, temps, data_actuacio, descripcio_actuacio, visible)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssi", $incidencia_id, $tecnic_id, $temps, $data_actuacio, $descripcio, $visible);

    if ($stmt->execute()) {

        $last_actuacio_id = $conn->insert_id;

        echo "<p class='info'>Actuació creada amb èxit!</p>";
        echo "<p class='info'>Número d'actuació: <strong>$last_actuacio_id</strong></p>";

        ?>
        <form method="GET" action="buscar_id.php">
            <input type="hidden" name="actuacio_id" value="<?php echo $last_actuacio_id; ?>">
            <fieldset>
                <button type="submit" class="btn btn-primary mt-3">Veure actuació</button>
            </fieldset>
        </form>
        <?php

    } else {
        echo "<p class='error'>Error al crear l'actuació: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actuació realitzada</title>
</head>

<body>

<a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">Tornar</a>

<h1>Actuació realitzada</h1>

<?php

$old_descripcio = $_POST['descripcio_actuacio'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    crear_actuacio($conn);

} else {
?>

<form method="POST" action="actuacio.php">

    <input type="hidden" name="incidencia_id" value="<?= htmlspecialchars($incidencia_id) ?>">
    <input type="hidden" name="tecnic_id" value="<?= htmlspecialchars($tecnic_id) ?>">

    <fieldset>

        <legend>Data Actuació</legend>
        <input type="date" name="data_actuacio">

        <br><br>

        <legend>Descripció Actuació</legend>
        <textarea name="descripcio_actuacio" rows="5" cols="40"><?= htmlspecialchars($old_descripcio) ?></textarea>

        <br><br>

        <legend>Temps Invertit (min)</legend>
        <input type="number" name="temps" min="0">

        <br><br>

        <legend>Visible</legend>
        <input type="checkbox" name="visible">

        <br><br>

        <legend>Finalitzada</legend>
        <input type="checkbox" name="finalitzada">

        <br><br>

        <input type="submit" value="Crear">
    </fieldset>
</form>

<?php } ?>

<div id="menu">
    <br>
    <p><a class='btn btn-secondary' href="index.php">Portada</a></p>
</div>

<?php require_once 'footer.php'; ?>