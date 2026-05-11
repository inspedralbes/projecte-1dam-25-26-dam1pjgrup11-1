<?php include_once "header.php";
require_once 'connexio.php';

//Total d'accessos (fet)

$total_accessos = [
    [
        '$match' => [
            'url' => '/'
        ]
    ],
    [
        '$group' => [
            '_id' => null,
            'total' => ['$sum' => 1]
        ]
    ]
];

$total = iterator_to_array($collection->aggregate($total_accessos));

//Pàgines més visitades

$pagines_visitades = [
    [
        '$match' => [
            'url' => [
                '$ne' => '/pantalla_info.php'
            ]
        ]
    ],
    [
        '$project' => [
            'url_neta' => [
                '$arrayElemAt' => [
                    [
                        '$split' => ['$url', '?']
                    ],
                    0
                ]
            ]
        ]
    ],
    [
        '$group' => [
            '_id' => '$url_neta',
            'total' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => [
            'total' => -1,
            '_id' => 1
        ]
    ]
];

$resultat_pagines = $collection->aggregate($pagines_visitades);

//Usuaris més actius (fet)

$usuaris_actius = [
    [
        '$match' => [
            'url' => '/'
        ]
    ],  
    [
        '$group' => [
            '_id' => '$ip_origin',
            'accessos' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => [
            'accessos' => -1
        ]
    ],
    [
        '$limit' => 5
    ]

];

$resultat_usuaris = $collection->aggregate($usuaris_actius);

//Accessos agrupats per dia (fet)

$accessos_per_dia = [
    [ 
        '$match' => [ 
            'url' => '/' 
        ] 
    ],
    [
        '$group' => [
            '_id' => [
                '$dateToString' => [
                    'format' => '%Y-%m-%d',
                    'date' => [
                        '$dateFromString' => [
                            'dateString' => '$date',
                            'timezone' => 'Europe/Madrid'
                        ]
                    ]
                ]
            ],
            'total' => [
                '$sum' => 1
            ]
        ]
    ]
];

$resultat_dies = $collection->aggregate($accessos_per_dia);

//Filtres per data, usuari i pàgina


?>

<body>
<div class="container mt-4">

    <h1 class="mb-5">Estadístiques d'accés</h1>

<div style="background-color: white" class="container mt-5">
    <h2>Total accessos</h2>
    <?php
    foreach ($total as $doc) {
        echo "Visites: " . $doc['total'] . "<br>";
    }
    ?>
    <h2>Pàgines més visitades</h2>
    <?php
    foreach ($resultat_pagines as $doc) {
        echo "Pàgina: " . htmlspecialchars($doc['_id'] ?? "x");
        echo " - Total: " . $doc['total'];
        echo "<br>";
    }
    ?>
    <h2>Usuaris més actius</h2>
    <?php
    foreach ($resultat_usuaris as $doc) {
        echo "Usuari IP: " . htmlspecialchars($doc['_id'] ?? "x");
        echo " - Accessos: " . $doc['accessos'];
        echo "<br>";
    }
    ?>
    <h2>Accessos per dia</h2>
    <?php
    foreach ($resultat_dies as $doc) {
        echo "Data: " . $doc['_id'] . " - Accessos: " . $doc['total'] . "<br>";
    }
    ?>
</div>

<?php include_once 'footer.php'; ?>