<?php
require_once 'connexio.php';
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

    <a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">← Tornar</a>
    <h2 class="mb-4">Llistat d'incidències</h2>

    <?php

    $sql = "SELECT 
                i.incidencia_id,
                i.descripcio_incidencia,
                i.prioritat,
                t.nom as tipologia_nom,
                te.nom as tecnic_nom
            FROM incidencia i
            LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
            LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
            ORDER BY i.incidencia_id";

    $stmnt = $conn->prepare($sql);
    $stmnt->execute();
    $result = $stmnt->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="table-dark">';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Descripció</th>';
        echo '<th>Tipologia</th>';
        echo '<th>Prioritat</th>';
        echo '<th>Tècnic</th>';
        echo '<th>Modificació</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            $prioritat_class = '';
            if ($row['prioritat'] == 'Alta') $prioritat_class = 'text-danger fw-bold';
            if ($row['prioritat'] == 'Mitja') $prioritat_class = 'text-warning fw-bold';
            if ($row['prioritat'] == 'Baixa') $prioritat_class = 'text-success fw-bold';

            echo '<tr>';
            echo '<td>' . $row['incidencia_id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['descripcio_incidencia'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['tipologia_nom'] ?? '') . '</td>';
            echo '<td class="' . $prioritat_class . '">' . $row['prioritat'] . '</td>';
            echo '<td>' . htmlspecialchars($row['tecnic_nom'] ?? '') . '</td>';
            echo '<td>';
            echo '<button class="btn btn-sm btn-primary" onclick="actuacio(' . $row['incidencia_id'] . ')">';
            echo 'Modificar';
            echo '</button>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="alert alert-info">No hi ha incidències.</div>';
    }

    $stmnt->close();
    $conn->close();
    ?>

</div>

<script>
    function editar(id) {
        
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once 'footer.php'?>



