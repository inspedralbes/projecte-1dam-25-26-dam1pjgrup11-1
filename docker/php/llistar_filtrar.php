<?php
require_once 'connexio.php';

$id = $_GET['id'] ?? null;


if (!$id) {
    header("Location: error_id.php");
    exit;
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
    header("Location: error_incidencia.php");
    exit;
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

<?php include_once "header.php"; ?>


<div class="container mt-4">

    <h1 class="mb-3">Modificar incidència #<?php echo $incidencia['incidencia_id']; ?></h1>
<br>
<h5 style="color: #405c53; font-weight: bold;" class="mb-4"><?php echo $incidencia['descripcio_incidencia']; ?></h4>
<br>

    <form action="actualitzar_incidencia.php" method="POST">

        <input type="hidden" name="id"
               value="<?php echo $incidencia['incidencia_id']; ?>">

        <div class="mb-3">
            <label for="prioritat" class="fs-4" style="color: #396355; font-weight: bold;">Prioritat</label>
            <select name="prioritat" id="prioritat" class="form-control form-select" required>

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
            <label for="tecnic" class="fs-4" style="color: #396355; font-weight: bold;">Tècnic:</label>
            <select name="tecnic_id" id="tecnic" class="form-control form-select" required>
                <option value=""> Selecciona un tècnic </option>

                <?php while ($tec = $tecnics->fetch_assoc()) { ?>
                    <option value="<?= $tec['tecnic_id'] ?>">
                        <?= htmlspecialchars($tec['tecnic_nom']) ?> <?= htmlspecialchars($tec['cognom']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipologia" class="fs-4" style="color: #396355; font-weight: bold;">Tipologia:</label>
            <select name="tipologia_id" id="tipologia" class="form-control form-select" required>
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

    </form>

</div>

<?php include_once "footer.php"; ?>