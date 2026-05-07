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
        GROUP BY t.tecnic_id, t.nom, t.cognom";

$stmnt = $conn->prepare($sql);
$stmnt->execute();
$result = $stmnt->get_result();
?>

<div class="container mt-4">

    <h1 class="mb-4 text-center">Incidències per tècnic</h1>

    <?php if ($result->num_rows > 0): ?>

        <table class="table table-hover table-bordered align-middle shadow-sm">

            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Incidències resoltes</th>
                    <th>Incidències totals</th>
                    <th>Pendents</th>
                </tr>
            </thead>

            <tbody>

                <?php while($row = $result->fetch_assoc()): ?>

                    <?php
                        $incidencies_pendents = $row['incidencies_totals'] - $row['incidencies_resoltes'];
                    ?>

                    <tr>
                        <td class="text-center fw-bold">
                            <?= $row['tecnic_id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nom']) ?>
                            <?= htmlspecialchars($row['cognom']) ?>
                        </td>

                        <td class="text-center">
                            <?= $row['incidencies_resoltes'] ?>
                        </td>

                        <td class="text-center">
                            <?= $row['incidencies_totals'] ?>
                        </td>

                        <td class="text-center text-danger fw-semibold">
                            <?= $incidencies_pendents ?>
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