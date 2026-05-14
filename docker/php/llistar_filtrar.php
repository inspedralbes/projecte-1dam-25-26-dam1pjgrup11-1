<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
if(($_SESSION['rol'] !== 'admin')){
    if(($_SESSION['rol'] == 'professor')){
        header("Location: professor.php");
        exit;
    }elseif(($_SESSION['rol'] == 'tecnic')){
        header("Location: tecnic.php");
        exit;
    }else{
        header("Location: index.php");
        exit;
    }

}

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
                i.estat,
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

require_once "header.php";

$sql1 = "SELECT tecnic_id, nom AS tecnic_nom, cognom
         FROM tecnic
         ORDER BY nom";

$tecnics = $conn->query($sql1);


$sql2 = "SELECT tipologia_id, nom AS tipologia_nom
         FROM tipologia
         ORDER BY nom";

$tipologies = $conn->query($sql2);

?>


<div class="container mt-4">

    <h1 class="mb-3">Modificar incidència #<?php echo $incidencia['incidencia_id']; ?></h1>
<br>
<h3  class="mb-4 titulo-incidencia"><?php echo $incidencia['descripcio_incidencia']; ?></h3>
<br>
    <form action="actualitzar_incidencia.php" method="POST">
    
        <input type="hidden" name="id"
               value="<?php echo $incidencia['incidencia_id']; ?>">

        <div class="mb-3">
            <label for="prioritat" class="fs-4 titulo-incidencia" style="font-weight: bold;">Prioritat</label>
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
            <label for="tecnic" class="fs-4 titulo-incidencia" style="font-weight: bold;">Tècnic:</label>
            <select name="tecnic_id" id="tecnic" class="form-control form-select" required>
                <option value=""> Selecciona un tècnic </option>
                <?php while ($tecnic = $tecnics->fetch_assoc()) { ?>
                    <option value="<?= $tecnic['tecnic_id'] ?>"
                        <?= ($incidencia['tecnic_id'] == $tecnic['tecnic_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tecnic['tecnic_nom']) ?> <?= htmlspecialchars($tecnic['cognom']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipologia" class="fs-4 titulo-incidencia" style="font-weight: bold;">Tipologia:</label>
            <select name="tipologia_id" id="tipologia" class="form-control form-select" required>
                <option value=""> Selecciona una tipologia </option>
                <?php while ($tipologia = $tipologies->fetch_assoc()) { ?>
                    <option value="<?= $tipologia['tipologia_id'] ?>"
                        <?= ($incidencia['tipologia_id'] == $tipologia['tipologia_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipologia['tipologia_nom']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <?php if ($incidencia['estat'] !== 'Oberta') { ?>
            <div class="mb-3">
                <label for="estat" class="fs-4 titulo-incidencia" style="font-weight: bold;">Estat:</label>
                <select name="estat" id="estat" class="form-control form-select" required>
                    <option value=""> Selecciona un estat </option>
                    <option value="En Curs"
                        <?php if($incidencia["estat"]=="En Curs") echo "selected"; ?>>
                        En Curs
                    </option>
                    <option value="Finalitzada"
                        <?php if($incidencia["estat"]=="Finalitzada") echo "selected"; ?>>
                        Finalitzada
                    </option>
                </select>
            </div>
        <?php } ?>

        <button type="submit" class="btn btn-success">
            Guardar canvis
        </button>

    </form>

</div>

<?php include_once "footer.php"; ?>