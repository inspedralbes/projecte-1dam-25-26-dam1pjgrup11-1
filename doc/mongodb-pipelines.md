# Pipelines d'Agregació MongoDB

---

### 1. `$total_accessos`

Compta tots els accessos a `url = '/'`.

- `$match` filtra per `url = '/'`
- `$group` amb `_id: null` col·lapsa tot en un sol resultat i suma amb `$sum: 1`

```php
$total_accessos = [
    ['$match' => ['url' => '/']],
    ['$group' => ['_id' => null, 'total' => ['$sum' => 1]]]
];
```

---

### 2. `$pagines_visitades`

Top 5 pàgines més visitades, excloent `/informacio.php` i netejant els query params.

- `$match` exclou `/informacio.php` amb `$ne`
- `$project` separa la URL pel caràcter `?` amb `$split` i agafa la part esquerra amb `$arrayElemAt`
- `$group` agrupa per URL neta i compta visites
- `$sort` per total descendent, `$limit` a 5

```php
$pagines_visitades = [
    ['$match' => ['url' => ['$ne' => '/informacio.php']]],
    ['$project' => [
        'url_neta' => ['$arrayElemAt' => [['$split' => ['$url', '?']], 0]]
    ]],
    ['$group' => ['_id' => '$url_neta', 'total' => ['$sum' => 1]]],
    ['$sort' => ['total' => -1, '_id' => 1]],
    ['$limit' => 5]
];
```

---

### 3. `$usuaris_actius` v1 — per IP

Top 5 IPs amb més accessos a `'/'`.

- `$match` filtra per `url = '/'`
- `$group` per `ip_origin`, compta accessos per IP
- `$sort` descendent, `$limit` a 5

```php
$usuaris_actius = [
    ['$match' => ['url' => '/']],
    ['$group' => ['_id' => '$ip_origin', 'accessos' => ['$sum' => 1]]],
    ['$sort' => ['accessos' => -1]],
    ['$limit' => 5]
];
```

---

### 4. `$accessos_per_dia`

Accessos a `'/'` agrupats per dia, últims 7 dies.

- `$match` filtra per `url = '/'`
- `$group` converteix el camp `date` (string) a `Date` BSON amb `$dateFromString` (timezone `Europe/Madrid`), i el trunca a `YYYY-MM-DD` amb `$dateToString`
- `$sort` descendent per data, `$limit` a 7

```php
$accessos_per_dia = [
    ['$match' => ['url' => '/']],
    ['$group' => [
        '_id' => [
            '$dateToString' => [
                'format' => '%Y-%m-%d',
                'date' => [
                    '$dateFromString' => ['dateString' => '$date', 'timezone' => 'Europe/Madrid']
                ]
            ]
        ],
        'total' => ['$sum' => 1]
    ]],
    ['$sort' => ['_id' => -1]],
    ['$limit' => 7]
];
```

---

### 5. `$usuaris_actius` v2 — per usuari autenticat

Top 10 usuaris identificats per `id` + `email`. Sobreescriu la variable del pas 3.

- Sense `$match`, opera sobre tota la col·lecció
- `$group` amb `_id` composta `{usuari_id, usuari_email}`
- `$sort` descendent, `$limit` a 10

```php
$usuaris_actius = [
    ['$group' => [
        '_id' => ['id' => '$usuari_id', 'email' => '$usuari_email'],
        'accessos' => ['$sum' => 1]
    ]],
    ['$sort' => ['accessos' => -1]],
    ['$limit' => 10]
];
```

---

### 6. `$accessos_rols`

Accessos agrupats per rol (admin, editor, usuari...).

- Sense `$match`, opera sobre tota la col·lecció
- `$group` per camp `$rol`
- `$sort` descendent, `$limit` a 10

```php
$accessos_rols = [
    ['$group' => ['_id' => '$rol', 'accessos' => ['$sum' => 1]]],
    ['$sort' => ['accessos' => -1]],
    ['$limit' => 10]
];
```