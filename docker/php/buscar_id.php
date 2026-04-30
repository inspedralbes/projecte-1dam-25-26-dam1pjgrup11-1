<?php require_once 'connexio.php'; ?>
<?php require_once 'header.php'; ?>

<a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">Tornar</a>

<div class="container py-5">

<?php
if (isset($_GET['incidencia_id']) && !empty($_GET['incidencia_id'])) {

    $incidencia_id = (int) $_GET['incidencia_id'];

    //info incidencia
    $sql_incidencia = "SELECT 
        i.incidencia_id,
        i.descripcio_incidencia,
        de.nom AS departament_nom,
        te.nom AS tecnic_nom,
        i.data_incidencia,
        i.data_final
    FROM incidencia i
    LEFT JOIN departament de ON i.departament_id = de.departament_id
    LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
    WHERE i.incidencia_id = ?";

    $stmt = $conn->prepare($sql_incidencia);
    $stmt->bind_param("i", $incidencia_id);
    $stmt->execute();
    $result_incidencia = $stmt->get_result();

    // temps total
    $sql_temps = "SELECT 
        SUM(a.temps) AS temps_total
    FROM actuacio a
    WHERE a.incidencia_id = ?";

    $stmt2 = $conn->prepare($sql_temps);
    $stmt2->bind_param("i", $incidencia_id);
    $stmt2->execute();
    $result_temps = $stmt2->get_result();
    $row2 = $result_temps->fetch_assoc();

    if ($result_incidencia->num_rows > 0) {
        $row = $result_incidencia->fetch_assoc();
?>

        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Resultat de la incidència</h5>

            <p><strong>ID:</strong> <?= $row['incidencia_id'] ?></p>
            <p><strong>Descripció:</strong> <?= htmlspecialchars($row['descripcio_incidencia']) ?></p>
            <p><strong>Departament:</strong> <?= htmlspecialchars($row['departament_nom']) ?></p>
            <p><strong>Tècnic:</strong> <?= htmlspecialchars($row['tecnic_nom'] ?? 'Sense tècnic assignat') ?></p>
            <p><strong>Data incidència:</strong> <?= htmlspecialchars($row['data_incidencia']) ?></p>
            <p><strong>Data final:</strong> <?= htmlspecialchars($row['data_final'] ?? 'Encara no ha estat resolta la incidència') ?></p>
            <p><strong>Temps invertit:</strong> <?= htmlspecialchars($row2['temps_total'] ?? 0) ?> min</p>
        </div>

<?php
    } else {
        echo '<div class="alert alert-warning">No s\'ha trobat cap incidència.</div>';
    }
}
?>

<br>

<div class="card shadow-sm p-4">
    <h5 class="mb-3">Descripció de les actuacions</h5>

<?php
// info actuacio
$sql_actuacio = "SELECT 
    a.actuacio_id,
    a.descripcio_actuacio, 
    a.visible, 
    a.data_actuacio, 
    a.temps 
FROM actuacio a 
WHERE a.incidencia_id = ? 
ORDER BY a.data_actuacio DESC, a.actuacio_id DESC"; 

$stmt3 = $conn->prepare($sql_actuacio); 
$stmt3->bind_param("i", $incidencia_id); 
$stmt3->execute(); 
$result_actuacio = $stmt3->get_result();

if ($result_actuacio->num_rows > 0): 
    while ($row = $result_actuacio->fetch_assoc()): 
        if ((int)$row['visible'] === 1): ?>

            <div class="mb-3 p-3 border rounded bg-light">
                <p><strong>Data actuació:</strong> <?= htmlspecialchars(date("d/m/Y H:i", strtotime($row['data_actuacio']))) ?></p>
                <p><strong>Temps invertit:</strong> <?= htmlspecialchars($row['temps']) ?> min</p>
                <p><strong>Descripció:</strong> <?= htmlspecialchars($row['descripcio_actuacio']) ?></p>
            </div>

        <?php endif; 
    endwhile;
else: ?>
    <div class="alert alert-warning">No s'ha trobat cap actuació.</div>
<?php endif; ?>

</div>

</div>

<?php require_once 'footer.php'; ?>