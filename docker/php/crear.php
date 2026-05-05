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
    <title>Crear incidencia</title>
</head>

<body>

<h1 class="fw-bold">Crear una incidencia</h1>

<?php

$old_departament = $_POST['departament_id'] ?? '';
$old_descripcio = $_POST['descripcio_incidencia'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    crear_incidencia($conn);

} else {

    $sql = "SELECT departament_id, nom FROM departament";
    $departaments = $conn->query($sql);

    ?>

    <form method="POST" action="crear.php">
        <div class="d-flex align-items-center justify-content-center" style="height: 500px;">
        <fieldset class=" border d-inline-block p-3 mb-4" style="background-color: white">
            <legend class="mb-3 azm-color-444">INCIDENCIA</legend>

            <label for="departament" class="form-label azm-color-666">Departament:</label>
            <select name="departament_id" id="departament" class="form-select mb-3" style="background-color: #F5F7F8; color:#495E57" required>
                <option value="" required> Selecciona </option>

                <?php while ($dep = $departaments->fetch_assoc()) { ?>
                    <option value="<?= $dep['departament_id'] ?>"
                        <?= ($old_departament == $dep['departament_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dep['nom']) ?>
                    </option>
                <?php } ?>

            </select>

            <br><br>
            <label for="descripcio" >Descripció del problema:</label>
            <br>
            <textarea class="form-control" id="descripcio" name="descripcio_incidencia" rows="5" cols="40" style="background-color: #F5F7F8; color:#495E57" required><?= htmlspecialchars($old_descripcio) ?></textarea>

            <br><br>

            <input type="submit" class="btn btn-success" value="Crear">
        </fieldset>
    </div>
    </form>

    <?php
}
?>

<div id="menu">
    <br>
    <p><a class='btn btn-secondary' href="index.php">Portada</a></p>
</div>

<?php require_once 'footer.php'?>