<?php
require_once 'connexio.php';

$id = $_GET['id'];

if (!$id) {
    die("ID no proporcionada");
}

    $sql = "SELECT
                i.incidencia_id,
                i.descripcio_incidencia,
                i.prioritat,
                i.tipologia_id,
                i.tecnic_id,
                t.nom AS tipologia_nom,
                te.nom AS tecnic_nom
            FROM incidencia i
            LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
            LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
            WHERE incidencia_id = ?
            ORDER BY i.incidencia_id";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$incidencia = $result->fetch_assoc();

if (!$incidencia) {
    die("Incidència no trobada");
}

$sql1 = "SELECT tecnic_id, nom AS tecnic_nom, cognom
         FROM tecnic
         ORDER BY nom";

$tecnics = $conn->query($sql1);


$sql2 = "SELECT tipologia_id, nom AS tipologia_nom
         FROM tipologia
         ORDER BY nom";

$tipologies = $conn->query($sql2);

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar incidència</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Modificar incidència #<?php echo $incidencia['incidencia_id']; ?></h2>
<br>
<h5><?php echo $incidencia['descripcio_incidencia']; ?></h5>
<br>



    <form action="actualitzar_incidencia.php" method="POST">

        <input type="hidden" name="id"
               value="<?php echo $incidencia['incidencia_id']; ?>">

        <div class="mb-3">
            <label for="prioritat">Prioritat</label>
            <select name="prioritat" id="prioritat" class="form-control" required>

                <option value="" >Selecciona una prioritat</option>
                <option value="Alta"
                    <?php if($incidencia["prioritat"]=="Alta") echo "selected"; ?>>
                    Alta
                </option>
                <option value="Mitja"
                    <?php if($incidencia["prioritat"]=="Mitja") echo "selected"; ?>>
                    Mitja
                </option>
                <option value="Baixa"
                    <?php if($incidencia["prioritat"]=="Baixa") echo "selected"; ?>>
                    Baixa
                </option>

            </select>
        </div>

        <div class="mb-3">
            <label for="tecnic">Tècnic:</label>
            <select name="tecnic_id" id="tecnic" class="form-control" required>
                <option value=""> Selecciona un tècnic </option>

                <?php while ($tec = $tecnics->fetch_assoc()) { ?>
                    <option value="<?= $tec['tecnic_id'] ?>">
                        <?= htmlspecialchars($tec['tecnic_nom']) ?> <?= htmlspecialchars($tec['cognom']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipologia">Tipologia:</label>
            <select name="tipologia_id" id="tipologia" class="form-control" required>
                <option value=""> Selecciona una tipologia </option>

                <?php while ($tip = $tipologies->fetch_assoc()) { ?>
                    <option value="<?= $tip['tipologia_id'] ?>">
                        <?= htmlspecialchars($tip['tipologia_nom']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            Guardar canvis
        </button>

        <a href="llistar_total.php" class="btn btn-secondary">
            Tornar
        </a>

    </form>

</div>

</body>
</html>