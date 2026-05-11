<?php include_once "header.php"; ?>
<?php
require_once 'connexio.php';

$usuari_id = isset($_GET['usuari_id']) ? intval($_GET['usuari_id']) : 0;

if ($usuari_id == 0) {
    echo '<div class="alert alert-danger">No hi ha incidències per aquest usuari.</div>';
    exit;
}

$sql = "SELECT
            i.incidencia_id,
            i.descripcio_incidencia,
            i.data_incidencia,
            t.nom AS tipologia_nom,
            d.nom AS departament_nom,
            i.estat
        FROM incidencia i
        LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
        LEFT JOIN usuari us ON i.usuari_id = us.usuari_id
        LEFT JOIN departament d ON i.departament_id = d.departament_id
        WHERE i.usuari_id = ?
        ORDER BY i.incidencia_id";

$stmnt = $conn->prepare($sql);
$stmnt->bind_param("i", $usuari_id);
$stmnt->execute();
$result = $stmnt->get_result();
?>

<body>

<div class="container mt-4">

    <h1 class="mb-5">Llistat d'incidències</h1>

    <?php if ($result->num_rows > 0): ?>

        <table class="table table-hover table-bordered align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Descripció</th>
                    <th>Tipologia</th>
                    <th>Data d'incidència</th>
                    <th>Departament</th>
                    <th>Estat</th>
                    <th>Accions</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>

                <tr>
                    <td class="text-center fw-bold"><?= $row['incidencia_id'] ?></td>

                    <td><?= htmlspecialchars($row['descripcio_incidencia'] ?? '—') ?></td>

                    <td class="text-center"><?= htmlspecialchars($row['tipologia_nom'] ?? '—') ?></td>

                    <td class="text-center"><?= $row['data_incidencia'] ?? '—' ?></td>

                    <td class="text-center"><?= htmlspecialchars($row['departament_nom'] ?? '—') ?></td>

                    <td class="text-center"><?= htmlspecialchars($row['estat'] ?? '—') ?></td>

                    <td class="text-center">
                        <a class="btn btn-sm btn-outline-primary"
                           href="buscar_id.php?incidencia_id=<?= $row['incidencia_id'] ?>">
                            Veure
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="alert alert-info">
            No hi ha incidències per aquest usuari.
        </div>

    <?php endif; ?>

    <?php
    $stmnt->close();
    $conn->close();
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once "footer.php"; ?>

</body>
</html>