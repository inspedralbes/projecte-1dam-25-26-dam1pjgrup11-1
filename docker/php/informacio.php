<?php 
require_once "connexio.php";
require_once "header.php";

// 1. Incidències per departament
$sql_departaments = "SELECT
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
ORDER BY num_incidencies DESC";

$stmnt_dep = $conn->prepare($sql_departaments);
$stmnt_dep->execute();
$result_dep = $stmnt_dep->get_result();

// 2. Incidències per tècnic
$sql_tecnics = "SELECT
    t.tecnic_id,
    t.nom,
    t.cognom,
    COUNT(i.incidencia_id) AS incidencies_totals,
    SUM(CASE WHEN i.data_final IS NOT NULL THEN 1 ELSE 0 END) AS incidencies_resoltes
FROM tecnic t
LEFT JOIN incidencia i ON t.tecnic_id = i.tecnic_id
GROUP BY t.tecnic_id, t.nom, t.cognom";

$stmnt_tec = $conn->prepare($sql_tecnics);
$stmnt_tec->execute();
$result_tec = $stmnt_tec->get_result();

// 3. Historial ultimes 10 incidències resoltes
$sql_historial = "SELECT DISTINCT
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
LIMIT 10";

$stmnt_hist = $conn->prepare($sql_historial);
$stmnt_hist->execute();
$result_hist = $stmnt_hist->get_result();

// 4. Dades per al gràfic de pastis
$sql_grafics = "
SELECT d.nom AS departament_nom
FROM incidencia i
LEFT JOIN departament d ON i.departament_id = d.departament_id";

$result_graf = $conn->query($sql_grafics);
$data_graf = $result_graf->fetch_all(MYSQLI_ASSOC);

$departaments = [];
foreach ($data_graf as $row) {
    $departaments[] = $row["departament_nom"] ?? "Sense departament";
}

$labels = array_keys(array_count_values($departaments));
$values = array_values(array_count_values($departaments));

// 5. Informació general
$sql_total_incidencies = "SELECT COUNT(*) AS total FROM incidencia";
$result_total = $conn->query($sql_total_incidencies);
$total_incidencies = $result_total->fetch_assoc()['total'];

$sql_total_resoltes = "SELECT COUNT(*) AS resoltes FROM incidencia WHERE data_final IS NOT NULL";
$result_resoltes = $conn->query($sql_total_resoltes);
$total_resoltes = $result_resoltes->fetch_assoc()['resoltes'];

$sql_temps_mitja_global = "SELECT ROUND(AVG(temps_total_incidencia), 1) AS mitja_hores 
FROM (
    SELECT i.incidencia_id, COALESCE(SUM(a.temps), 0) AS temps_total_incidencia
    FROM incidencia i
    LEFT JOIN actuacio a ON i.incidencia_id = a.incidencia_id
    WHERE i.data_final IS NOT NULL
    GROUP BY i.incidencia_id
) AS temps_per_incidencia";

$result_temps = $conn->query($sql_temps_mitja_global);
$temps_mitja_global = $result_temps->fetch_assoc()['mitja_hores'] ?? 0;

// 6. Estadístiques MongoDB
$total_accessos = [
    ['$match' => ['url' => '/']],
    ['$group' => ['_id' => null, 'total' => ['$sum' => 1]]]
];
$total = iterator_to_array($collection->aggregate($total_accessos));
$total_accessos_valor = !empty($total) ? $total[0]['total'] : 0;

$pagines_visitades = [
    ['$match' => ['url' => ['$ne' => '/informacio.php']]],
    ['$project' => ['url_neta' => ['$arrayElemAt' => [['$split' => ['$url', '?']], 0]]]],
    ['$group' => ['_id' => '$url_neta', 'total' => ['$sum' => 1]]],
    ['$sort' => ['total' => -1, '_id' => 1]],
    ['$limit' => 5]
];
$resultat_pagines = $collection->aggregate($pagines_visitades);

$usuaris_actius = [
    ['$match' => ['url' => '/']],
    ['$group' => ['_id' => '$ip_origin', 'accessos' => ['$sum' => 1]]],
    ['$sort' => ['accessos' => -1]],
    ['$limit' => 5]
];
$resultat_usuaris = $collection->aggregate($usuaris_actius);

$accessos_per_dia = [
    ['$match' => ['url' => '/']],
    ['$group' => [
        '_id' => [
            '$dateToString' => [
                'format' => '%Y-%m-%d',
                'date' => ['$dateFromString' => ['dateString' => '$date', 'timezone' => 'Europe/Madrid']]
            ]
        ],
        'total' => ['$sum' => 1]
    ]],
    ['$sort' => ['_id' => -1]],
    ['$limit' => 7]
];
$resultat_dies = $collection->aggregate($accessos_per_dia);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Dashboard d'Incidències</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        
        .container {
            width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        h1 {
            color: #0066cc;
            text-align: center;
            margin-bottom: 10px;
        }
        
        .targetes {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .targeta {
            width: 23%;
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 15px;
            margin-right: 10px;
            text-align: center;
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        .targeta:hover {
            background-color: #005bb5;
            transform: scale(1.05);
        }
        
        .targeta.numero {
            font-size: 32px;
            font-weight: bold;
        }
        
        .targeta.verda {
            background-color: #28a745;
        }
        .targeta.verda:hover {
            background-color: #218838;
        }
        
        .targeta.blava {
            background-color: #17a2b8;
        }
        .targeta.blava:hover {
            background-color: #117a8b;
        }
        
        .targeta.gris {
            background-color: #343a40;
        }
        .targeta.gris:hover {
            background-color: #292d32;
        }
        
        .col-esquerra {
            width: 48%;
            float: left;
            margin-right: 2%;
        }
        
        .col-dreta {
            width: 48%;
            float: left;
        }
        
        .clearfix {
            clear: both;
        }
        
        .card {
            background-color: white;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .card-body {
            padding: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .text-center {
            text-align: center;
        }
        
        .fw-bold {
            font-weight: bold;
        }
        
        .fw-semibold {
            font-weight: 600;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .text-danger {
            color: #dc3545;
        }
        
        .small {
            font-size: 12px;
        }
        
        .text-nowrap {
            white-space: nowrap;
        }
        
        .chart-container {
            width: 100%;
            height: 500px;
            padding: 20px;
        }

        canvas {
            max-width: 100%;
            height: 100% !important;
        }
        
        code {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 2px 4px;
        }
        
        .taula-scroll {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .sticky-top {
            position: sticky;
            top: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    
    <div class="titol">
        <h1>Dashboard d'Incidències</h1>
        <p style="text-align: center; color: #666;">Panell de control complet amb informació, taules i gràfics</p>
        <hr>
    </div>
    
    <div class="targetes">
        <div class="targeta">
            <div>Total incidències</div>
            <div class="numero"><?= $total_incidencies ?></div>
        </div>
        <div class="targeta verda">
            <div>Incidències resoltes</div>
            <div class="numero"><?= $total_resoltes ?></div>
        </div>
        <div class="targeta blava">
            <div>Temps mitjà resolució</div>
            <div class="numero"><?= $temps_mitja_global ?> <span style="font-size: 14px;">min</span></div>
        </div>
        <div class="targeta gris">
            <div>Total accessos web</div>
            <div class="numero"><?= $total_accessos_valor ?></div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    
    <!-- COLUMNA ESQUERRA -->
    <div class="col-esquerra">
        
        <!-- Grafic quesito -->
        <div class="card">
            <div class="card-header">Distribució d'incidències per departament</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="peuChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Taula departaments -->
        <div class="card">
            <div class="card-header">Incidències per departament</div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Departament</th>
                            <th>Incidències</th>
                            <th>Temps total</th>
                            <th>Temps mitjà</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_dep->num_rows > 0): ?>
                            <?php while($row = $result_dep->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center"><?= $row['departament_id'] ?></td>
                                    <td><?= htmlspecialchars($row['nom']) ?></td>
                                    <td class="text-center"><?= $row['num_incidencies'] ?></td>
                                    <td class="text-center"><?= $row['temps_total'] ?> min</td>
                                    <td class="text-center"><?= $row['temps_mitja'] ?> min</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No hi ha dades</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- COLUMNA DRETA -->
    <div class="col-dreta">
        
        <!-- Taula tecnics -->
        <div class="card">
            <div class="card-header">Incidències per tècnic</div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Resoltes</th>
                            <th>Totals</th>
                            <th>Pendents</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_tec->num_rows > 0): ?>
                            <?php while($row = $result_tec->fetch_assoc()): ?>
                                <?php $pendents = $row['incidencies_totals'] - $row['incidencies_resoltes']; ?>
                                <tr>
                                    <td class="text-center"><?= $row['tecnic_id'] ?></td>
                                    <td><?= htmlspecialchars($row['nom'] . ' ' . $row['cognom']) ?></td>
                                    <td class="text-center"><?= $row['incidencies_resoltes'] ?></td>
                                    <td class="text-center"><?= $row['incidencies_totals'] ?></td>
                                    <td class="text-center text-danger"><?= $pendents ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No hi ha dades</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Historial -->
        <div class="card">
            <div class="card-header">Historial d'incidències resoltes (últimes 10)</div>
            <div class="card-body">
                <div class="taula-scroll">
                    <table>
                        <thead class="sticky-top">
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
                            <?php if ($result_hist->num_rows > 0): ?>
                                <?php while($row = $result_hist->fetch_assoc()): ?>
                                    <?php
                                        $temps_dies = (strtotime($row['data_final']) - strtotime($row['data_incidencia'])) / 86400;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $row['incidencia_id'] ?></td>
                                        <td><?= htmlspecialchars(substr($row['descripcio_incidencia'], 0, 45)) ?>...</td>
                                        <td class="small"><?= $row['data_incidencia'] ?></td>
                                        <td class="small"><?= $row['data_final'] ?></td>
                                        <td><?= htmlspecialchars($row['tipologia_nom'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['departament_nom'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars(($row['tecnic_nom'] ?? '') . ' ' . ($row['tecnic_cognom'] ?? '')) ?></td>
                                        <td class="text-center"><?= round($temps_dies, 2) ?> dies</td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">No hi ha dades</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    
    <!-- Estadistiques MongoDB -->
    
    <!-- Pagines mes visitades -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">Pàgines més visitades</div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Pàgina</th>
                        <th width="120" class="text-center">Accessos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $has_pagines = false;
                    foreach ($resultat_pagines as $doc): 
                        $has_pagines = true;
                    ?>
                        <tr>
                        <td><code>
                        <?php
                        $url = $doc['_id'] ?? '/';
                        if ($url == '/') {
                            $url = '/index.php';
                        }
                        echo htmlspecialchars($url);
                        ?>
                        </code></td>
                            <td class="text-center"><?= $doc['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$has_pagines): ?>
                        <tr><td colspan="2" class="text-center">No hi ha dades</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Usuaris mes actius -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">Usuaris més actius</div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Adreça IP</th>
                        <th width="100" class="text-center">Accessos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $has_usuaris = false;
                    foreach ($resultat_usuaris as $doc): 
                        $has_usuaris = true;
                    ?>
                        <tr>
                            <td><code><?= htmlspecialchars($doc['_id'] ?? 'Desconegut') ?></code></td>
                            <td class="text-center"><?= $doc['accessos'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$has_usuaris): ?>
                        <tr><td colspan="2" class="text-center">No hi ha dades</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Accessos per dia -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">Accessos per dia</div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th width="120" class="text-center">Accessos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $has_dies = false;
                    foreach ($resultat_dies as $doc): 
                        $has_dies = true;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['_id'] ?? 'Desconegut') ?></td>
                            <td class="text-center"><?= $doc['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$has_dies): ?>
                        <tr><td colspan="2" class="text-center">No hi ha dades</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<script>
// Gràfic de pastís
const ctx = document.getElementById('peuChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($values) ?>,

            backgroundColor: [
                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1',
                '#fd7e14', '#20c997', '#d63384', '#0dcaf0', '#adb5bd'
            ]
        }]
    },
    options: {
        responsive: true,
    }
});
</script>

</body>
</html>