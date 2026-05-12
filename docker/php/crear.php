<?php
require_once 'connexio.php';
require_once 'header.php';

$usuari_id = $_SESSION['user_id'] ?? 0;

function crear_incidencia($conn, $usuari_id)
{
    $departament_id = $_POST['departament_id'] ?? '';
    $descripcio = trim($_POST['descripcio_incidencia'] ?? '');

    if (empty($departament_id) || empty($descripcio)) {
        return "<div class='alert alert-danger'>Tots els camps són obligatoris.</div>";
    }

    $sql = "INSERT INTO incidencia (usuari_id, departament_id, descripcio_incidencia, data_incidencia, estat)
            VALUES (?, ?, ?, NOW(), 'oberta')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $usuari_id, $departament_id, $descripcio);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        return "<div class='alert alert-success'>
                    <p class='mb-1'>Incidència creada amb èxit!</p>
                    <p>El teu número és <strong>$last_id</strong></p>
                </div>
                <form method='GET' action='buscar_id.php'>
                    <input type='hidden' name='incidencia_id' value='$last_id'>
                    <button type='submit'>Veure la teva incidència</button>
                </form>";
    } else {
        return "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
    }
}
?>

<div class="container mt-5">
    <h1 class="fw-bold mb-4 text-center">Crear una incidència</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo crear_incidencia($conn, $usuari_id);
    }
    ?>
</div>

<br><br><br><br>

<?php require_once 'footer.php'; ?>