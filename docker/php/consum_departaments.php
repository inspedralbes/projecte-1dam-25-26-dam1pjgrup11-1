<?php 
    require_once "connexio.php";
    require_once "header.php";

$sql = "SELECT 
    i.departament_id,
    d.nom AS nom,
    COUNT(DISTINCT i.incidencia_id) AS num_incidencies,
    COALESCE(SUM(a.temps), 0) AS temps_total
FROM incidencia i
LEFT JOIN departament d ON i.departament_id = d.departament_id
LEFT JOIN actuacio a ON i.incidencia_id = a.incidencia_id
WHERE i.data_final IS NOT NULL
GROUP BY i.departament_id, d.nom
ORDER BY num_incidencies DESC;";

$stmnt = $conn->prepare($sql);
$stmnt->execute();
$result = $stmnt->get_result();
?>

<h2 style="text-align:center;">Incidències per departament</h2>

<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th>ID</th>
            <th>Departament</th>
            <th>Incidències</th>
            <th>Temps</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['departament_id']}</td>
                        <td>{$row['nom']}</td>
                        <td>{$row['num_incidencies']}</td>
                        <td>{$row['temps_total']} min</td>
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