<?php
session_start();
require_once "connexio.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
if(($_SESSION['rol'] !== 'tecnic')){
    if(($_SESSION['rol'] == 'professor')){
        header("Location: professor.php");
        exit;
    }elseif(($_SESSION['rol'] == 'admin')){
        header("Location: llistar_total.php");
        exit;
    }else{
        header("Location: index.php");
        exit;
    }

}
include_once "header.php";

$usuari_id = $_SESSION['user_id'];

$sql = "SELECT t.tecnic_id, t.nom, t.cognom, t.usuari_id
        FROM tecnic t
        INNER JOIN usuari u ON u.usuari_id = t.usuari_id
        WHERE u.usuari_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuari_id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();

$stmt->close();
?>

<div class="d-flex justify-content-center" style="padding-top: 60px;">

    <div class="text-center w-50">

        <h1 class="mb-3">
            Benvingut, <?= htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['cognom']) ?>
        </h1>

        <h4 class="mb-4">
            Aquí pots veure les incidències que tens assignades:
        </h4>

        <form action="llistar.php" method="GET">

            <!-- ENVIEM EL TECNIC ID -->
            <input type="hidden" name="tecnic_id" value="<?= htmlspecialchars($row['tecnic_id']) ?>">

            <input
                type="submit"
                value="Llistar les incidències"
                class="btn btn-success btn-lg rounded w-100"
            >

        </form>

    </div>

</div>

<?php
$conn->close();
include_once "footer.php";
?>