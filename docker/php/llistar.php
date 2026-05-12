<?php include_once "header.php"?>

<?php
require_once 'connexio.php';

$tecnic_id = isset($_GET['tecnic_id']) ? intval($_GET['tecnic_id']) : 0;

if ($tecnic_id == 0) {
?>
    <div class="alert alert-danger">Selecciona un tècnic.</div>
<?php
    exit;
}

$sql = "SELECT
            i.incidencia_id,
            i.descripcio_incidencia,
            i.prioritat,
            t.nom as tipologia_nom,
            te.nom as tecnic_nom,
            i.estat
        FROM incidencia i
        LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
        LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
        WHERE i.tecnic_id = ? and i.estat != 'Finalitzada'
        ORDER BY i.incidencia_id";

$stmnt = $conn->prepare($sql);
$stmnt->bind_param("i", $tecnic_id);
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
                    <th>Prioritat</th>
                    <th>Tècnic</th>
                    <th>Estat</th>
                    <th>Accions</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>

                <?php
                    $prioritat_class = '';

                    if ($row['prioritat'] == 'Alta') {
                        $prioritat_class = 'text-danger fw-semibold';
                    } elseif ($row['prioritat'] == 'Mitja') {
                        $prioritat_class = 'text-warning fw-semibold';
                    } elseif ($row['prioritat'] == 'Baixa') {
                        $prioritat_class = 'text-success fw-semibold';
                    }
                ?>

                <tr>
                    <td class="text-center fw-bold">
                        <?= $row['incidencia_id'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['descripcio_incidencia'] ?? '—') ?>
                    </td>

                    <td class="text-center">
                        <?= htmlspecialchars($row['tipologia_nom'] ?? '—') ?>
                    </td>

                    <td class="text-center <?= $prioritat_class ?>">
                        <?= $row['prioritat'] ?? '—' ?>
                    </td>

                    <td class="text-center">
                        <?= htmlspecialchars($row['tecnic_nom'] ?? '—') ?>
                    </td>

                    <td class="text-center">
                        <?= htmlspecialchars($row['estat'] ?? '—') ?>
                    </td>

                    <td class="text-center">
                        <a class="btn btn-sm btn-outline-primary"
                           href="actuacio.php?incidencia_id=<?= $row['incidencia_id'] ?>&tecnic_id=<?= $tecnic_id ?>">
                            Obrir
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>

            </tbody>
        </table>

    <?php else: ?>

        <div class="alert alert-info">
            No hi ha incidències per aquest tècnic.
        </div>

    <?php endif; ?>

    <?php
    $stmnt->close();
    $conn->close();
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once "footer.php"?>

</body>
</html>