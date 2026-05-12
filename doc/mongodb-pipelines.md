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

$usuaris_actius = [
    [
        '$group' => [
            '_id' => [
                'id' => '$usuari_id',
                'email' => '$usuari_email'
            ],
            'accessos' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => ['accessos' => -1]
    ],
    [
        '$limit' => 10
    ]
];
$resultat_usuaris = $collection->aggregate($usuaris_actius);

$accessos_rols = [
    [
        '$group' => [
            '_id' => '$rol',
            'accessos' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => ['accessos' => -1]
    ],
    [
        '$limit' => 10
    ]
];
$resultat_rols = $collection->aggregate($accessos_rols);