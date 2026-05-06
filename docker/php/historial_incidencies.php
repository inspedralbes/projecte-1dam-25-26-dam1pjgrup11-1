<?php 
    require_once "connexio.php";
    require_once "header.php";

$sql = "SELECT DISTINCT
            i.incidencia_id,
            d.nom AS departament_nom,
            t.nom AS tecnic_nom,
            t.cognom AS tecnic_cognom,
            i.data_incidencia,
            i.data_final,
            tp.nom AS tipologia_nom,
            i.descripcio_incidencia
        FROM incidencia i
        LEFT JOIN tecnic t ON i.tecnic_id = t.tecnic_id
        LEFT JOIN departament d ON i.departament_id = d.departament_id
        LEFT JOIN tipologia tp ON i.tipologia_id = tp.tipologia_id
        LEFT JOIN actuacio a ON i.incidencia_id = a.incidencia_id
        WHERE i.data_final IS NOT NULL
        GROUP BY 
            i.incidencia_id,
            d.nom,
            t.nom,
            t.cognom,
            i.data_incidencia,
            i.data_final,
            tp.nom,
            i.descripcio_incidencia
        ORDER BY i.data_final DESC
        LIMIT 10;";

$stmnt = $conn->prepare($sql);
$stmnt->execute();
$result = $stmnt->get_result();
?>

<h2 style="text-align:center;">Historial d'incidencies resoltes</h2>

<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descripció</th>
            <th>Data inici</th>
            <th>Data final</th>
            <th>Tipologia</th>
            <th>Departament</th>
            <th>Tecnic</th>
            <th>Temps</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $temps_total = strtotime($row['data_final']) - strtotime($row['data_incidencia']);
                $temps_total = $temps_total / 86400; // segundos a días
            echo "<tr>
                        <td>{$row['incidencia_id']}</td>
                        <td>{$row['descripcio_incidencia']}</td>
                        <td>{$row['data_incidencia']}</td>
                        <td>{$row['data_final']}</td>
                        <td>{$row['tipologia_nom']}</td>
                        <td>{$row['departament_nom']}</td>
                        <td>{$row['tecnic_nom']} {$row['tecnic_cognom']}</td>
                        <td>$temps_total dies</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hi ha dades</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>

<?php include_once "footer.php"?>