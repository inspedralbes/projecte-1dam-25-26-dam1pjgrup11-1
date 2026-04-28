<?php

require_once 'connexio.php';
require_once 'header.php';
/**
 * Crear una incidencia
 */
function crear_incidencia($conn)
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
        echo "<p class= 'info'>El teu numero de incidència es </p><strong>" . $last_incidencia_id . "</strong>";
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
    <title>Crear incidencia</title>
</head>

<body>

<a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">← Tornar</a>

<h1>Crear una incidencia</h1>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    crear_incidencia($conn);

} else {

    $sql = "SELECT departament_id, nom FROM departament";
    $departaments = $conn->query($sql);

    ?>

    <form method="POST" action="crear.php">
        <fieldset>
            <legend>INCIDENCIA</legend>

            <label for="departament">Departament:</label>
            <select name="departament_id" id="departament">
                <option value=""> Selecciona </option>

                <?php while ($dep = $departaments->fetch_assoc()) { ?>
                    <option value="<?= $dep['departament_id'] ?>">
                        <?= htmlspecialchars($dep['nom']) ?>
                    </option>
                <?php } ?>

            </select>

            <br><br>
            <label for="descripcio">Descripció del problema:</label>
            <br>
            <textarea id="descripcio" name="descripcio_incidencia" rows="5" cols="40"></textarea>

            <br><br>

            <input type="submit" value="Crear">
        </fieldset>
    </form>

    <?php
}
?>

<div id="menu">
    <hr>
    <p><a href="buscar_id.php">Buscar la meva incidencia</a></p>
    <p><a href="index.php">Portada</a></p>
    <p><a href="crear.php">Crear incidència</a></p>
</div>

<?php require_once 'footer.php'?>