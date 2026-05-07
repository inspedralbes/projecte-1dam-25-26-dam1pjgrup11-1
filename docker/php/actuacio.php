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


function crear_actuacio($conn)
{
    $incidencia_id = $_POST['incidencia_id'] ?? null;
    $tecnic_id = $_POST['tecnic_id'] ?? null;

    $descripcio = $_POST['descripcio_actuacio'] ?? '';
    $visible = isset($_POST['visible']) ? 1 : 0;
    $finalitzada = isset($_POST['finalitzada']) ? 1 : 0;
    $temps = $_POST['temps'] ?? 0;
    $data_actuacio = $_POST['data_actuacio'] ?? date('Y-m-d');

    if (empty($descripcio)) return "<p class='alert alert-danger'>La descripció és obligatòria.</p>";
    if (empty($data_actuacio)) return "<p class='alert alert-danger'>La data és obligatòria.</p>";
    if (empty($temps)) return "<p class='alert alert-danger'>El temps és obligatori.</p>";
    if (strlen($descripcio) < 20) return "<p class='alert alert-danger'>Mínim 20 caràcters.</p>";

    if ($finalitzada == 1) {
        $sql_update = "UPDATE incidencia SET data_final = NOW() WHERE incidencia_id = ?";
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
        $last_id = $conn->insert_id;

        $output = "<div class='alert alert-success'>";
        $output .= "<p class='mb-1'>Actuació creada amb èxit!</p>";
        $output .= "<p>Número d'actuació: <strong>$last_id</strong></p>";
        $output .= "</div>";

        $output .= "
        <form method='GET' action='buscar_id.php'>
            <input type='hidden' name='incidencia_id' value='$incidencia_id'>
            <button type='submit' class='btn btn-primary'>Veure actuacions</button>
        </form>";

        return $output;
    }

    return "<p class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</p>";
}
?>

<?php
$descripcio_incidencia = '';

if ($incidencia_id) {
    $sql = "SELECT descripcio_incidencia FROM incidencia WHERE incidencia_id = ?";
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

<div class="container mt-5">

    <h1 class="fw-bold mb-2 text-center">Actuació realitzada</h1>
    <h5 class="text-center mb-4">Incidència #<?= htmlspecialchars($incidencia_id) ?></h5>
    <p class="text-center text-muted mb-4"><?= htmlspecialchars($descripcio_incidencia) ?></p>

    <?php
    $old_descripcio = $_POST['descripcio_actuacio'] ?? '';
    $creada = false;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo crear_actuacio($conn);
        $creada = true;
    }
    ?>

    <?php if (!$creada): ?>
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">

            <form method="POST" action="actuacio.php">

                <input type="hidden" name="incidencia_id" value="<?= htmlspecialchars($incidencia_id) ?>">
                <input type="hidden" name="tecnic_id" value="<?= htmlspecialchars($tecnic_id) ?>">

                <div class="mb-3">
                    <label class="form-label">Data actuació</label>
                    <input style="background-color: #F5F7F8; color:#495E57" type="date" name="data_actuacio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripció actuació</label>
                    <textarea style="background-color: #F5F7F8; color:#495E57" class="form-control" name="descripcio_actuacio" rows="5" minlength="20" placeholder="Escriu informació sobre la teva actuació" required><?= htmlspecialchars($old_descripcio) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Temps invertit (minuts)</label>
                    <input style="background-color: #F5F7F8; color:#495E57" type="number" name="temps" class="form-control" min="0" required>
                </div>

                <div class="form-check mb-2">
                    <input style="background-color: #b5c3c9; color:#495E57" class="form-check-input" type="checkbox" name="visible" id="visible">
                    <label class="form-check-label" for="visible">Visible</label>
                </div>

                <div class="form-check mb-3">
                    <input style=" accent-color: #2ECC71; background-color: #b5c3c9; color:#495E57" class="form-check-input" type="checkbox" name="finalitzada" id="finalitzada">
                    <label class="form-check-label" for="finalitzada">Finalitzada</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Crear actuació</button>
                </div>

            </form>

        </div>
    </div>
    <?php endif; ?>

</div>

<?php require_once 'footer.php'; ?>