<?php
require_once 'connexio.php';
require_once 'header.php';

function crear_incidencia($conn)
{
    $departament_id = $_POST['departament_id'] ?? '';
    $descripcio = trim($_POST['descripcio_incidencia'] ?? '');

    if (empty($departament_id) || empty($descripcio)) {
        return "<p class='alert alert-danger'>Tots els camps són obligatoris.</p>";
    }

    $sql = "INSERT INTO incidencia (departament_id, descripcio_incidencia, data_incidencia)
            VALUES (?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $departament_id, $descripcio);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;

        $output = "<div class='alert alert-success'>";
        $output .= "<p class='mb-1'>Incidència creada amb èxit!</p>";
        $output .= "<p>El teu número és <strong>$last_id</strong></p>";
        $output .= "</div>";

        $output .= "
        <form method='GET' action='buscar_id.php'>
            <input type='hidden' name='incidencia_id' value='$last_id'>
            <button type='submit' class='btn btn-primary'>Veure la teva incidència</button>
        </form>
        ";
        return $output;

    } else {
        return "<p class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
}
?>

<div class="container mt-5">

    <h1 class="fw-bold mb-4 text-center">Crear una incidència</h1>

    <?php
    $old_departament = $_POST['departament_id'] ?? '';
    $old_descripcio = $_POST['descripcio_incidencia'] ?? '';

    $incidencia_creada = false;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo crear_incidencia($conn);
        $incidencia_creada = true;
    }

    $sql = "SELECT departament_id, nom FROM departament";
    $departaments = $conn->query($sql);
    ?>
    
    <?php if (!$incidencia_creada): ?>
    <div class="card shadow-sm mx-auto" style="max-width: 600px;" id="formulari_incidencia">
        <div class="card-body">

            <form method="POST" action="crear.php" name="guardar_incidencia">

                <div class="mb-3">
                    <label for="departament" class="form-label">Departament</label>
                    <select name="departament_id" id="departament" class="form-select" style="background-color: #F5F7F8; color:#495E57" required>
                        <option value="">Selecciona</option>
                        <?php while ($dep = $departaments->fetch_assoc()) { ?>
                            <option value="<?= $dep['departament_id'] ?>"
                                <?= ($old_departament == $dep['departament_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dep['nom']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descripcio" class="form-label">Descripció del problema</label>
                    <textarea style="background-color: #F5F7F8; color:#495E57" class="form-control" id="descripcio" name="descripcio_incidencia"rows="5"placeholder="Explica el problema amb el màxim detall possible" required><?= htmlspecialchars($old_descripcio) ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Crear incidència</button>
                </div>

            </form>

        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>