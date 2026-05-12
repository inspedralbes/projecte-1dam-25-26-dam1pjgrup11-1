# Documentació Pipelines d'Agregació MongoDB

## 1. `$total_accessos` — Contar documents amb `url = '/'`

**Pipeline:** `$match → $group (_id: null, $sum: 1)`

- **`$match`**: filtra documents on `url === '/'`. Redueix el conjunt abans del `$group` (eficient si hi ha índex sobre `url`).
- **`$group`** amb `_id: null`: col·lapsa tots els documents en un sol, acumulant `$sum: 1` a `total`.
- `iterator_to_array()` materialitza el cursor; el ternari gestiona el cas de col·lecció buida (`$total[0]['total']`).

```php
$total_accessos = [
    ['$match' => ['url' => '/']],
    ['$group' => ['_id' => null, 'total' => ['$sum' => 1]]]
];
$total = iterator_to_array($collection->aggregate($total_accessos));
$total_accessos_valor = !empty($total) ? $total[0]['total'] : 0;
```

---

## 2. `$pagines_visitades` — Top 5 URLs normalitzades

**Pipeline:** `$match → $project ($split + $arrayElemAt) → $group → $sort → $limit`

- **`$match`**: exclou `url = '/informacio.php'` amb `$ne`.
- **`$project`**: construeix `url_neta` amb una expressió encadenada:
  - `$split: ['$url', '?']` → genera un array `['path', 'querystring']`
  - `$arrayElemAt: [..., 0]` → extreu l'element `[0]`, descartant els query params
- **`$group`** per `url_neta`: agrega visites per URL neta (`$sum: 1`).
- **`$sort`** compost: primer per `total: -1`, desempat per `_id: 1` (ordre alfabètic).
- **`$limit: 5`**: talla el resultat.

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

## 3. `$usuaris_actius` v1 — Top 5 IPs a `'/'`

**Pipeline:** `$match → $group ($ip_origin) → $sort → $limit`

- **`$match`**: filtra `url = '/'`.
- **`$group`** per `ip_origin`: cada IP única és un `_id`, `accessos: $sum: 1` compta les seves peticions.
- **`$sort` + `$limit`**: retorna les 5 IPs amb més hit count.

```php
$usuaris_actius = [
    ['$match' => ['url' => '/']],
    ['$group' => ['_id' => '$ip_origin', 'accessos' => ['$sum' => 1]]],
    ['$sort' => ['accessos' => -1]],
    ['$limit' => 5]
];
```

---

## 4. `$accessos_per_dia` — Sèrie temporal dels últims 7 dies a `'/'`

**Pipeline:** `$match → $group ($dateToString ∘ $dateFromString) → $sort → $limit`

- **`$match`**: filtra `url = '/'`.
- **`$group`** amb expressió de data encadenada:
  - **`$dateFromString`**: parseja el camp `date` (string) a tipus `Date` BSON aplicant `timezone: 'Europe/Madrid'` → gestiona correctament DST.
  - **`$dateToString`** amb `format: '%Y-%m-%d'`: trunca a granularitat de dia.
  - El resultat és un `_id` de tipus string `"YYYY-MM-DD"`.
- **`$sort: {_id: -1}`**: ordre descendent cronològic.
- **`$limit: 7`**: finestra dels últims 7 dies amb activitat (no necessàriament consecutius).

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

## 5. `$usuaris_actius` v2 — Top 10 usuaris autenticats

**Pipeline:** `$group (_id composta) → $sort → $limit`

- **Sense `$match` previ**: opera sobre tota la col·lecció.
- **`$group`** amb `_id` composta `{id, email}`: clau única per usuari autenticat.
- Permet correlacionar `usuari_id` ↔ `usuari_email` en el resultat sense `$lookup` addicional.
- **`$sort` + `$limit`**: top 10 per volum d'accessos.
- ⚠️ **Sobreescriu** la variable `$usuaris_actius` definida al pas 3.

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

## 6. `$accessos_rols` — Distribució d'accessos per rol

**Pipeline:** `$group ($rol) → $sort → $limit`

- **Sense `$match` previ**: opera sobre tota la col·lecció.
- **`$group`** per `$rol`: cardinalitat baixa esperada (admin, editor, usuari...).
- Útil per a anàlisi de patrons d'ús per perfil.
- **`$sort` + `$limit`**: retorna els 10 rols amb més activitat (en pràctica retorna tots si n'hi ha menys de 10).

```php
$accessos_rols = [
    ['$group' => ['_id' => '$rol', 'accessos' => ['$sum' => 1]]],
    ['$sort' => ['accessos' => -1]],
    ['$limit' => 10]
];
```

---

## Resum comparatiu

| Pipeline | `$match` inicial | Camp d'agrupació | Limit |
|---|---|---|---|
| `$total_accessos` | `url = '/'` | `null` (tot) | — |
| `$pagines_visitades` | `url ≠ '/informacio.php'` | `url_neta` (normalitzada) | 5 |
| `$usuaris_actius` v1 | `url = '/'` | `ip_origin` | 5 |
| `$accessos_per_dia` | `url = '/'` | data truncada a dia | 7 |
| `$usuaris_actius` v2 | cap | `{usuari_id, usuari_email}` | 10 |
| `$accessos_rols` | cap | `rol` | 10 |