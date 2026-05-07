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

<div class="container mt-4">

    <h2 class="mb-4 text-center">Historial d'incidències resoltes</h2>

    <?php if ($result->num_rows > 0): ?>

        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle shadow-sm">

                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Descripció</th>
                        <th>Data inici</th>
                        <th>Data final</th>
                        <th>Tipologia</th>
                        <th>Departament</th>
                        <th>Tècnic</th>
                        <th>Temps</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while($row = $result->fetch_assoc()): ?>

                        <?php
                            $temps_total = strtotime($row['data_final']) - strtotime($row['data_incidencia']);
                            $temps_total = $temps_total / 86400; // dies
                        ?>

                        <tr>
                            <td class="text-center fw-bold">
                                <?= $row['incidencia_id'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['descripcio_incidencia']) ?>
                            </td>

                            <td class="text-center">
                                <?= $row['data_incidencia'] ?>
                            </td>

                            <td class="text-center">
                                <?= $row['data_final'] ?>
                            </td>

                            <td class="text-center">
                                <?= htmlspecialchars($row['tipologia_nom']) ?>
                            </td>

                            <td class="text-center">
                                <?= htmlspecialchars($row['departament_nom']) ?>
                            </td>

                            <td class="text-center">
                                <?= htmlspecialchars($row['tecnic_nom']) ?>
                                <?= htmlspecialchars($row['tecnic_cognom']) ?>
                            </td>

                            <td class="text-center fw-semibold">
                                <?= round($temps_total, 2) ?> dies
                            </td>
                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="alert alert-info text-center">
            No hi ha dades disponibles.
        </div>

    <?php endif; ?>

</div>

</body>
</html>