<?php 
    require_once "connexio.php";
    require_once "header.php";

$sql = "SELECT 
            t.tecnic_id,
            t.nom,
            t.cognom,
            COUNT(i.incidencia_id) AS incidencies_totals,
            SUM(CASE WHEN i.data_final IS NOT NULL THEN 1 ELSE 0 END) AS incidencies_resoltes
        FROM tecnic t
        LEFT JOIN incidencia i ON t.tecnic_id = i.tecnic_id
        GROUP BY t.tecnic_id, t.nom";

$stmnt = $conn->prepare($sql);
$stmnt->execute();
$result = $stmnt->get_result();
?>

<h2 style="text-align:center;">Historial d'incidencies resoltes</h2>

<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Incidències resoltes</th>
            <th>Incidències totals</th>
            <th>Incidències pendents</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            //crear incidencies pendientes  
            $incidencies_pendents = $row['incidencies_totals'] - $row['incidencies_resoltes'];  
            echo "<tr>
                        <td>{$row['tecnic_id']}</td>
                        <td>{$row['nom']} {$row['cognom']}</td>
                        <td>{$row['incidencies_resoltes']}</td>
                        <td>{$row['incidencies_totals']}</td>
                        <td style='background-color:#f8d7da;'>{$incidencies_pendents}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hi ha dades</td></tr>";
        }
        ?>
    </tbody>
</table>
</body>
</html>

    <a href="llistar_total.php" class="btn btn-secondary mt-3">Tornar</a>
</div>

</body>
</html>