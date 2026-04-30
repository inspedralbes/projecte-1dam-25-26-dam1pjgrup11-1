<?php

require_once 'connexio.php';
require_once 'header.php';
/**
 * Crear una actuacio
 */
function crear_actuacio($conn)
{
    $departament_id = $_POST['departament_id'];
    $descripcio = $_POST['descripcio_incidencia'];


    if (empty($departament_id) || empty($descripcio)) {
        echo "<p class='error'>Tots els camps són obligatoris.</p>";
        return;
    }

    $sql = "INSERT INTO incidencia (departament_id, descripcio_incidencia, data_incidencia)
            VALUES (?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("is", $departament_id, $descripcio);

    if ($stmt->execute()) {
    $last_incidencia_id = $conn->insert_id;

    echo "<p class='info'>Incidència creada amb èxit!</p>";
    echo "<p class='info'>El teu número d'incidència és <strong>$last_incidencia_id</strong></p>";
    ?>
    <form method="GET" action="buscar_id.php">
        <input type="hidden" name="incidencia_id" value="<?php echo $last_incidencia_id; ?>">
        <fieldset>
            <button type="submit" class="btn btn-primary mt-3">Veure la teva incidència</button>
        </fieldset>
    </form>
    <?php
    } else {
        echo "<p class='error'>Error al crear la incidència: " . htmlspecialchars($stmt->error) . "</p>";
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

$old_departament = $_POST['departament_id'] ?? '';
$old_descripcio = $_POST['descripcio_incidencia'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    crear_actuacio($conn);

} else {

    $sql = "SELECT departament_id, nom FROM departament";
    $departaments = $conn->query($sql);

    ?>

    <form method="POST" action="actuacio.php">
        <fieldset>
            <legend>Data Actuació</legend>
              <label for="birthday">Birthday:</label>
              <input type="date" id="birthday" name="birthday">
              <br><br>

            <legend>Descripció Actuació</legend>

            <label for="descripcio"></label>
            <br>
            <textarea placeholder="Posa una descripció que expliqui la teva actuació" id="descripcio" name="descripcio_incidencia" rows="5" cols="40"><?= htmlspecialchars($old_descripcio) ?></textarea>

            <br><br>

            <legend>Visible la Descripció de la Actuació</legend>
            <input type="checkbox" style="width: 30px; height: 30px;">

            <br><br>

            <legend>Temps Invertit (min)</legend>

            <label for="descripcio"></label>
            <br>
            <textarea placeholder="Posa quant temps en minuts (m) has dedicat en aquesta actuació" id="descripcio" name="descripcio_incidencia" rows="5" cols="40"><?= htmlspecialchars($old_descripcio) ?></textarea>

            <br><br>


            <legend>Actuació Finalitzada</legend>
            <input type="checkbox" style="width: 30px; height: 30px;">

            <br><br>


            <input type="submit" value="Crear">
        </fieldset>
    </form>

    <?php
}
?>

<div id="menu">
    <br>
    <p><a class='btn btn-secondary' href="index.php">Portada</a></p>
</div>

<?php require_once 'footer.php'?>