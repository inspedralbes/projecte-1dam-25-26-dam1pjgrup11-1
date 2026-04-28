<?php
require_once 'connexio.php';

$filtre = $_GET['filtre'] ?? 'total';
$result = null;
$stmt = null;
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat d'incidències</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-4">

    <a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">
        Tornar
    </a>

    <h2 class="mb-4">Llistat d'incidències</h2>

    <form id="formFiltre" action="llistar_total.php" method="GET">
    <select name="filtre" id="filtre" class="form-select mb-3" onchange="this.form.submit()">

    <option value="total" <?= $filtre == 'total' ? 'selected' : '' ?>>
        Total
    </option>

    <option value="sense_assignar" <?= $filtre == 'sense_assignar' ? 'selected' : '' ?>>
        Sense assignar
    </option>

    <option value="assignades" <?= $filtre == 'assignades' ? 'selected' : '' ?>>
        Assignades
    </option>

</select>
</form>

    <?php

    if ($filtre == 'total') {
        $sql = "SELECT
                i.incidencia_id,
                i.descripcio_incidencia,
                i.prioritat,
                t.nom AS tipologia_nom,
                te.nom AS tecnic_nom
            FROM incidencia i
            LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
            LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
            ORDER BY i.prioritat";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    }else if ($filtre == 'sense_assignar'){
        $sql = "SELECT
                i.incidencia_id,
                i.descripcio_incidencia,
                i.prioritat,
                t.nom AS tipologia_nom,
                te.nom AS tecnic_nom
            FROM incidencia i
            LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
            LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
            WHERE i.tecnic_id IS NULL";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

    }else if ($filtre == 'assignades'){
        $sql = "SELECT
                    i.incidencia_id,
                    i.descripcio_incidencia,
                    i.prioritat,
                    t.nom AS tipologia_nom,
                    te.nom AS tecnic_nom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.tecnic_id IS NOT NULL
                ORDER BY i.prioritat";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    ?>

    <?php if ($result->num_rows > 0): ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Descripció</th>
                    <th>Tipologia</th>
                    <th>Prioritat</th>
                    <th>Tècnic</th>
                    <th>Modificació</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>

                    <?php
                        $prioritat_class = '';

                        if ($row['prioritat'] == 'Alta') {
                            $prioritat_class = 'text-danger fw-bold';
                        } elseif ($row['prioritat'] == 'Mitja') {
                            $prioritat_class = 'text-warning fw-bold';
                        } elseif ($row['prioritat'] == 'Baixa') {
                            $prioritat_class = 'text-success fw-bold';
                        }
                    ?>

                    <tr>
                        <td><?= $row['incidencia_id'] ?></td>
                        <td><?= htmlspecialchars($row['descripcio_incidencia'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['tipologia_nom'] ?? '') ?></td>
                        <td class="<?= $prioritat_class ?>">
                            <?= $row['prioritat'] ?>
                        </td>
                        <td><?= htmlspecialchars($row['tecnic_nom'] ?? '') ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="llistar_filtrar.php?id=<?= $row['incidencia_id'] ?>">
                                Modificar
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>
        <div class="alert alert-info">No hi ha incidències.</div>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once 'footer.php'; ?>

</body>
</html>