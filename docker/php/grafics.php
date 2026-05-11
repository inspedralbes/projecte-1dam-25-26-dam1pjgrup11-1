<?php include_once "header.php";
require_once "connexio.php";

$sql = "
SELECT d.nom AS departament_nom
FROM incidencia i
LEFT JOIN departament d ON i.departament_id = d.departament_id
";

$result = $conn->query($sql);
$data = $result->fetch_all(MYSQLI_ASSOC);

$departaments = [];

foreach ($data as $row) {
    $departaments[] = $row["departament_nom"] ?? "Sense departament";
}

$labels = array_keys(array_count_values($departaments));
$values = array_values(array_count_values($departaments));
?>

<h1>Incidencies per departament</h1>

<div class="mb-3">

<canvas id="myChart" width="200" height="200"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($values) ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
</script>
</div>