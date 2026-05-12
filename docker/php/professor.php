<?php
require_once 'connexio.php';
require_once 'header.php';

$usuari_id = $_SESSION['user_id'] ?? 0;

// LLISTAT INCIDÈNCIES
$sql = "SELECT 
            i.incidencia_id,
            i.descripcio_incidencia,
            i.estat,
            i.data_incidencia,
            t.nom AS tipologia_nom,
            d.nom AS departament_nom
        FROM incidencia i
        LEFT JOIN tipologia t
            ON i.tipologia_id = t.tipologia_id
        LEFT JOIN departament d
            ON i.departament_id = d.departament_id
        WHERE i.usuari_id = ?
        ORDER BY 
            i.data_incidencia DESC,
            i.incidencia_id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuari_id);
$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT 
            departament_id,
            nom
        FROM departament
        ORDER BY nom ASC";

$departaments = $conn->query($sql);
?>

<body style="background-color:#f5f7fa;">

<div class="container-fluid">
    <div class="row">

        <!-- Barra lateral incidencies -->
        <div class="col-md-3 p-3">
            <div style="background: white; color:black; padding:20px; border-radius:10px; position:sticky; top:20px; height:92vh; overflow-y:auto;">
                <h4 style="margin-bottom:20px;">
                    Les teves incidències
                </h4>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $estat = strtolower($row['estat']);
                        $color = '#6c757d';
                        if ($estat == 'oberta') $color = '#dc3545';
                        if ($estat == 'en curs') $color = '#ffc107';
                        if ($estat == 'finalitzada') $color = '#198754';
                        ?>
                        <a href="buscar_id.php?incidencia_id=<?= $row['incidencia_id'] ?>"
                           style="text-decoration:none; color:black;">
                            <div style="background: lightgray; border-radius:8px; padding:12px; margin-bottom:12px;">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                    <div>
                                        <div style="font-weight:bold; margin-bottom:5px;">
                                            <?= $row['incidencia_id'] ?>
                                        </div>
                                        <div style="font-size:14px; color:black;">
                                            <?= htmlspecialchars($row['descripcio_incidencia']) ?>
                                        </div>
                                    </div>
                                    <span style="background:<?= $color ?>; color:white; padding:4px 8px; border-radius:5px; font-size:12px;">
                                        <?= htmlspecialchars($row['estat']) ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="background:lightgray; color:black; padding:15px; border-radius:8px;">
                        No hi ha incidències.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Crear incidencia -->
        <div class="col-md-9 p-5">
            <h1 class="fw-bold mb-4 text-center">
                Crear una incidència
            </h1>
            <div class="card shadow-sm mx-auto" style="max-width: 600px;" id="formulari_incidencia">
                <div class="card-body">
                    <form method="POST" action="crear.php" name="guardar_incidencia">
                        <div class="mb-3">
                            <label for="departament" class="form-label">Departament</label>
                            <select name="departament_id" id="departament" class="form-select" style="background-color: #F5F7F8; color:#495E57" required>
                                <option value="">Selecciona</option>
                                <?php while ($dep = $departaments->fetch_assoc()) { ?>
                                    <option value="<?= $dep['departament_id'] ?>">
                                        <?= htmlspecialchars($dep['nom']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcio" class="form-label">Descripció del problema</label>
                            <textarea style="background-color: #F5F7F8; color:#495E57" class="form-control" id="descripcio" name="descripcio_incidencia" rows="5" placeholder="Explica el problema amb el màxim detall possible" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Crear incidència</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
require_once 'footer.php';
?>

</body>
</html>