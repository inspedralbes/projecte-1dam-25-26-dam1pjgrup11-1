<?php 
require_once "connexio.php";
require_once "header.php";

$sql = "SELECT
    i.departament_id,
    d.nom AS nom,
    COUNT(DISTINCT i.incidencia_id) AS num_incidencies,
    COALESCE(SUM(a.temps), 0) AS temps_total,
    ROUND(AVG(a.temps), 1) AS temps_mitja
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

<div class="container mt-4">

    <h1 class="mb-4 text-center">Incidències per departament</h1>

    <?php if ($result->num_rows > 0): ?>

        <table class="table table-hover table-bordered align-middle shadow-sm">

            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Departament</th>
                    <th>Incidències</th>
                    <th>Temps total</th>
                    <th>Temps mitjà</th>
                </tr>
            </thead>

            <tbody>

                <?php while($row = $result->fetch_assoc()): ?>

                    <tr>
                        <td class="text-center fw-bold">
                            <?= $row['departament_id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nom']) ?>
                        </td>

                        <td class="text-center fw-semibold">
                            <?= $row['num_incidencies'] ?>
                        </td>

                        <td class="text-center">
                            <?= $row['temps_total'] ?> min
                        </td>

                        <td class="text-center">
                            <?= $row['temps_mitja'] ?> min
                        </td>
                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="alert alert-info text-center">
            No hi ha dades disponibles.
        </div>

    <?php endif; ?>

</div>

</body>
</html>