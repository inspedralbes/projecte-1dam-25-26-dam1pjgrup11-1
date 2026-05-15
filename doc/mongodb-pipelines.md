# Pipelines d'Agregació MongoDB

---

### Filtre loguejat

Filtre base per només mostrar usuaris loguejats que utilitzo per controlar el accessos

```
$filtre_loguejat = [
    'usuari_email' => ['$ne' => 'unknown'],
    'usuari_id'    => ['$ne' => null],
    'rol'          => ['$ne' => 'unknown'],
];
```

### 1. $total_accessos

Compta tots els accessos a url = '/'.

- $match array_merge combina el filtres, filtra per url = '/login.php' i '/index.php' per comptar com acces quan es logueja i mentre navega per la web, i si esta loguejat
- $group amb _id: null col·lapsa tot en un sol resultat i suma amb $sum: 1

```
$total_accessos = [
    ['$match' => array_merge($filtre_loguejat, ['url' => ['$in' => ['/login.php', '/index.php']]])],
    ['$group' => ['_id' => null, 'total' => ['$sum' => 1]]]
];
```

---

### 2. $pagines_visitades

Top 5 pàgines més visitades, excloent /informacio.php i netejant els parametres d'entrada.

- $match array_merge combina el filtres, filtra per url = '/informacio.php', '/login.php' i '/index.php' per comptar com acces quan es logueja i mentre navega per la web, i si esta loguejat
- $project separa la URL pel caràcter ? amb $split i agafa la part esquerra amb $arrayElemAt
- $group agrupa per URL neta i compta visites
- $sort per total descendent, $limit a 5

```
$pagines_visitades = [
    ['$match' => array_merge($filtre_loguejat,['url' => ['$nin' => ['/informacio.php', '/login.php', '/index.php' ]]])],
    ['$project' => [
        'url_neta' => ['$arrayElemAt' => [['$split' => ['$url', '?']], 0]]
    ]],
    ['$group' => ['_id' => '$url_neta', 'total' => ['$sum' => 1]]],
    ['$sort'  => ['total' => -1, '_id' => 1]],
    ['$limit' => 5]
];
```

---

### 3. $accessos_per_dia

Accessos a '/' agrupats per dia, últims 7 dies.

- $match array_merge combina el filtres, filtra per url = '/login.php' i '/index.php' per comptar com acces quan es logueja i mentre navega per la web, i si esta loguejat
- $group converteix el camp date (string) a Date BSON amb $dateFromString (timezone Europe/Madrid), i el trunca a YYYY-MM-DD amb $dateToString
- $sort descendent per data, $limit a 7

```
$accessos_per_dia = [
    ['$match' => array_merge($filtre_loguejat, ['url' => ['$in' => ['/login.php', '/index.php']]])],
    ['$group' => [
        '_id' => ['$dateToString' => [
            'format' => '%Y-%m-%d',
            'date'   => ['$dateFromString' => [
                'dateString' => '$date',
                'timezone'   => 'Europe/Madrid'
            ]]
        ]],
        'total' => ['$sum' => 1]
    ]],
    ['$sort'  => ['_id' => -1]],
    ['$limit' => 7]
];
```

---

### 4. $usuaris_actius

Top 10 usuaris identificats per id + email.

- $match array_merge combina el filtres, filtra per url = '/login.php' i '/index.php' per comptar com acces quan es logueja i mentre navega per la web, i si esta loguejat
- $group amb _id composta {usuari_id, usuari_email}
- $sort descendent, $limit a 10

```
$usuaris_actius = [
    ['$match' => array_merge($filtre_loguejat, ['url' => ['$in' => ['/login.php', '/index.php']]])],
    ['$group' => [
        '_id'     => ['id' => '$usuari_id', 'email' => '$usuari_email'],
        'accessos' => ['$sum' => 1]
    ]],
    ['$sort'  => ['accessos' => -1]],
    ['$limit' => 10]
];
```

---

### 5. $accessos_rols

Accessos agrupats per rol (admin, tecnic, professor).

- $match array_merge combina el filtres, filtra per url = '/login.php' i '/index.php' per comptar com acces quan es logueja i mentre navega per la web, i si esta loguejat
- $group per camp $rol
- $sort descendent, $limit a 10

```
$accessos_rols = [
    ['$match' => array_merge($filtre_loguejat, ['url' => ['$in' => ['/login.php', '/index.php']]])],
    ['$group' => ['_id' => '$rol', 'accessos' => ['$sum' => 1]]],
    ['$sort'  => ['accessos' => -1]],
    ['$limit' => 10]
];
```