<?php

require_once 'connexio.php';

$incidencia_id = $_GET['incidencia_id'] ?? $_POST['incidencia_id'] ?? null;
$tecnic_id = $_GET['tecnic_id'] ?? $_POST['tecnic_id'] ?? null;

$sql = "SELECT 1 FROM tecnic WHERE tecnic_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tecnic_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    header("LOCATION: error_tecnic.php");
}

$sql = "SELECT 1 FROM incidencia WHERE incidencia_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $incidencia_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    header("LOCATION: error_incidencia.php");
}

require_once 'header.php';

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
    }else if (empty($data_actuacio)) {
        echo "<p class='error'>La data de l'actuació és obligatòria.</p>";
        return;
    } else if (empty($temps)) {
        echo "<p class='error'>El temps és obligatori.</p>";
        return;
    }else if(strlen($descripcio) < 20){
        echo "<p class='error'>La descripció ha de tenir almenys 20 caràcters.</p>";
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
        echo "<br><br><br><br>";
        echo "<p class='info'>Actuació creada amb èxit!</p>";
        echo "<p class='info'>Número d'actuació: <strong>$last_actuacio_id</strong></p>";

        ?>
        <form method="GET" action="buscar_id.php">
            <input type="hidden" name="incidencia_id" value="<?php echo $incidencia_id; ?>">
            <fieldset>
                <button type="submit" class="btn btn-primary mt-3">Veure actuacions</button>
            </fieldset>
        </form>
        <?php

    } else {
        echo "<p class='error'>Error al crear l'actuació: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}

?>

<?php

$descripcio_incidencia = '';

if ($incidencia_id) {
    $sql = "SELECT descripcio_incidencia
            FROM incidencia
            WHERE incidencia_id = ?";

    $stmt1 = $conn->prepare($sql);
    $stmt1->bind_param("i", $incidencia_id);
    $stmt1->execute();
    $result = $stmt1->get_result();

    if ($row = $result->fetch_assoc()) {
        $descripcio_incidencia = $row['descripcio_incidencia'];
    }

    $stmt1->close();
}
?>

<h1>Actuació realitzada</h1>
<h4>Incidencia numero #<?php echo $incidencia_id; ?></h5>
<h6><?php echo $descripcio_incidencia; ?></h5>

<?php

$old_descripcio = $_POST['descripcio_actuacio'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    crear_actuacio($conn);
} else {
?>

<form method="POST" action="actuacio.php" name="guardar_actuacio" id="guardar_actuacio">

    <input type="hidden" name="incidencia_id" value="<?= htmlspecialchars($incidencia_id) ?>">
    <input type="hidden" name="tecnic_id" value="<?= htmlspecialchars($tecnic_id) ?>">

    

        <legend>Data Actuació</legend>
        <input type="date" name="data_actuacio" id="data_actuacio" required>

        <br><br>

        <legend>Descripció Actuació</legend>
        <textarea placeholder="Escriu informació sobre la teva actuació" name="descripcio_actuacio" id="descripcio_actuacio" rows="5" cols="40" minlength="20" required><?= htmlspecialchars($old_descripcio) ?></textarea>

        <br><br>

        <legend>Temps Invertit (min)</legend>
        <input type="number" name="temps" id="temps" min="0" required>

        <br><br>

        <legend>Visible</legend>
        <input type="checkbox" name="visible" id="visible">

        <br><br>

        <legend>Finalitzada</legend>
        <input type="checkbox" name="finalitzada">

        <br><br>

        <input type="submit" value="Crear">
    
</form>

<?php } ?>

<br>

<?php require_once 'footer.php'; ?>