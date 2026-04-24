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
    <h2 class="mb-4">Llistat d'incidències</h2>

    <?php
    // Obtenir nom del tècnic
    $nom_tecnic = isset($_GET['nom_tecnic']) ? trim($_GET['nom_tecnic']) : '';

    if (empty($nom_tecnic)) {
        echo '<div class="alert alert-danger">Escriu el nom del tècnic.</div>';
        echo '<a href="tecnic.php" class="btn btn-primary">Tornar</a>';
        exit;
    }

    // Buscar ID del tècnic
    $sql_tecnic = "SELECT tecnic_id FROM tecnic WHERE nom = ?";
    $statement = $conn->prepare($sql_tecnic);
    $statement->bind_param("s", $nom_tecnic);
    $statement->execute();
    $result_tecnic = $statement->get_result();

    if ($result_tecnic->num_rows == 0) {
        echo '<div class="alert alert-warning">No s\'ha trobat el tècnic: ' . htmlspecialchars($nom_tecnic) . '</div>';
        echo '<a href="tecnic.php" class="btn btn-primary">Tornar</a>';
        $conn->close();
        exit;
    }

    $tecnic = $result_tecnic->fetch_assoc();
    $tecnic_id = $tecnic['tecnic_id'];

    // Consulta per obtenir les incidències del tècnic
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

    $statement = $conn->prepare($sql);
    $statement->bind_param("i", $tecnic_id);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="table-dark">';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Descripció</th>';
        echo '<th>Tipologia</th>';
        echo '<th>Prioritat</th>';
        echo '<th>Tècnic</th>';
        echo '<th>Actuació</th>';
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
            echo '<td>' . htmlspecialchars($row['descripcio_incidencia']) . '</td>';
            echo '<td>' . htmlspecialchars($row['tipologia_nom']) . '</td>';
            echo '<td class="' . $prioritat_class . '">' . $row['prioritat'] . '</td>';
            echo '<td>' . htmlspecialchars($row['tecnic_nom']) . '</td>';
            echo '<td>';
            echo '<button class="btn btn-sm btn-primary" onclick="actuacio(' . $row['incidencia_id'] . ')">';
            echo 'Actuació';
            echo '</button>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="alert alert-info">No hi ha incidències per aquest tècnic.</div>';
    }

    $statement->close();
    $conn->close();
    ?>

    <a href="tecnic.php" class="btn btn-secondary mt-3">← Tornar</a>
</div>

<script>
    function actuacio(id) {
        
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once 'footer.php'?>