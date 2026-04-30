<?php
require_once 'connexio.php';

$tecnic_id = isset($_GET['tecnic_id']) ? intval($_GET['tecnic_id']) : 0;

if ($tecnic_id == 0) {
?>
    <div class="alert alert-danger">Selecciona un tècnic.</div>
    <a href="tecnic.php" class="btn btn-primary">Tornar</a>
<?php
    exit;
}

$sql = "SELECT
            i.incidencia_id,
            i.descripcio_incidencia,
            i.prioritat,
            t.nom as tipologia_nom,
            te.nom as tecnic_nom
        FROM incidencia i
        LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
        LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
        WHERE i.tecnic_id = ?
        ORDER BY i.incidencia_id";

$stmnt = $conn->prepare($sql);
$stmnt->bind_param("i", $tecnic_id);
$stmnt->execute();
$result = $stmnt->get_result();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat d'incidències</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
    <h2 class="mb-4">Llistat d'incidències</h2>

<?php if ($result->num_rows > 0) { ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Descripció</th>
                <th>Tipologia</th>
                <th>Prioritat</th>
                <th>Tècnic</th>
                <th>Actuació</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($row = $result->fetch_assoc()) {
            $prioritat_class = '';
            if ($row['prioritat'] == 'Alta') $prioritat_class = 'text-danger fw-bold';
            if ($row['prioritat'] == 'Mitja') $prioritat_class = 'text-warning fw-bold';
            if ($row['prioritat'] == 'Baixa') $prioritat_class = 'text-success fw-bold';
        ?>

            <tr>
                <td><?= $row['incidencia_id'] ?></td>
                <td><?= htmlspecialchars($row['descripcio_incidencia']) ?></td>
                <td><?= htmlspecialchars($row['tipologia_nom']) ?></td>
                <td class="<?= $prioritat_class ?>">
                    <?= $row['prioritat'] ?>
                </td>
                <td><?= htmlspecialchars($row['tecnic_nom']) ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="actuacio.php?id=<?= $row['incidencia_id'] ?>">
                        Actuació
                    </a>
                </td>
            </tr>

        <?php } ?>

        </tbody>
    </table>

<?php } else { ?>

    <div class="alert alert-info">
        No hi ha incidències per aquest tècnic.
    </div>

<?php } ?>

<?php
$stmnt->close();
$conn->close();
?>

    <a href="tecnic.php" class="btn btn-secondary mt-3">Tornar</a>
</div>

<script>
function actuacio(id) {
    // aquí puedes redirigir si quieres
    // window.location.href = "actuacio.php?id=" + id;
}
</script>

</body>
</html>