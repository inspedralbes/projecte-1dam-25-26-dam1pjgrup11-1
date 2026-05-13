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

include_once "header.php";

$filtre = $_GET['filtre'] ?? 'sense_assignar';
$start = isset($_GET['start']) ? (int)$_GET['start'] : 1;

$limit = 20;
$page = ($start - 1) * $limit;

$result = null;
$stmt = null;
?>

<body>


<div class="container mt-4">

    <h1 class="mb-5">Llistat d'incidències</h1>

<div class="botones-panel">
    <a href="informacio.php" class="btn-panel">Informacions</a>
</div>

    <br>

    <form id="formFiltre" action="llistar_total.php" method="GET">
        <select name="filtre" id="filtre" class="form-select mb-3" onchange="this.form.submit()" style="width: 200px;">
            <option value="sense_assignar" <?= $filtre == 'sense_assignar' ? 'selected' : '' ?>>
                Sense assignar
            </option>

            <option value="assignades" <?= $filtre == 'assignades' ? 'selected' : '' ?>>
                Assignades
            </option>

            <option value="finalitzades" <?= $filtre == 'finalitzades' ? 'selected' : '' ?>>
                Finalitzades
            </option>

            <option value="total" <?= $filtre == 'total' ? 'selected' : '' ?>>
                Total
            </option>

        </select>
    </form>

    <?php

    if ($filtre == 'total') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.data_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                ORDER BY i.prioritat,
                i.data_incidencia DESC,
                i.incidencia_id
                LIMIT ? OFFSET ?";

        $countSql = "SELECT COUNT(*) as total FROM incidencia";

    } else if ($filtre == 'sense_assignar') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.data_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.estat = 'Oberta'
                ORDER BY i.prioritat,
                i.data_incidencia DESC,
                i.incidencia_id
                LIMIT ? OFFSET ?";

        $countSql = "SELECT COUNT(*) as total
                     FROM incidencia
                     WHERE estat = 'Oberta'";

    } else if ($filtre == 'assignades') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.data_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.estat = 'En Curs'
                ORDER BY i.prioritat,
                i.data_incidencia DESC,
                i.incidencia_id
                LIMIT ? OFFSET ?";

        $countSql = "SELECT COUNT(*) as total
                     FROM incidencia
                     WHERE estat = 'En Curs'";

    } else if ($filtre == 'finalitzades') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.data_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.estat = 'Finalitzada'
                ORDER BY i.prioritat,
                i.data_incidencia DESC,
                i.incidencia_id
                LIMIT ? OFFSET ?";

        $countSql = "SELECT COUNT(*) as total
                     FROM incidencia
                     WHERE estat = 'Finalitzada'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $page);
    $stmt->execute();
    $result = $stmt->get_result();

    // Aqui es es fa el recompte gracies al COUNT
    $countResult = $conn->query($countSql);
    $totalRows = $countResult->fetch_assoc()['total'];

    $totalPages = ceil($totalRows / $limit);

    ?>

    <?php if ($result->num_rows > 0): ?>

        <table class="table table-hover table-bordered align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Descripció</th>
                    <th>Data</th>
                    <th>Tipologia</th>
                    <th>Prioritat</th>
                    <th>Estat</th>
                    <th>Tècnic</th>
                    <th>Accions</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>

                    <?php
                        $prioritat_class = '';

                        if ($row['prioritat'] == 'Alta') {
                            $prioritat_class = 'text-danger fw-semibold';
                        } elseif ($row['prioritat'] == 'Mitja') {
                            $prioritat_class = 'text-warning fw-semibold';
                        } elseif ($row['prioritat'] == 'Baixa') {
                            $prioritat_class = 'text-success fw-semibold';
                        }
                    ?>

                    <tr>
                        <td class="text-center fw-bold">
                            <?= $row['incidencia_id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['descripcio_incidencia'] ?? '—') ?>
                        </td>

                        <td class="text-center">
                            <?= date('d/m/Y', strtotime($row['data_incidencia'])) ?>
                        </td>

                        <td class="text-center">
                            <?= htmlspecialchars($row['tipologia_nom'] ?? '—') ?>
                        </td>

                        <td class="text-center <?= $prioritat_class ?>">
                            <?= $row['prioritat'] ?? '—'?>
                        </td>

                        <td class="text-center">
                            <?= htmlspecialchars($row['estat'] ?? '—') ?>
                        </td>

                        <td class="text-center">
                            <?= htmlspecialchars($row['tecnic_nom'] ?? '—') ?> <?= htmlspecialchars($row['tecnic_cognom'] ?? '—') ?>
                        </td>

                        <td class="text-center">

                            <a class="btn btn-sm btn-outline-primary"
                               href="llistar_filtrar.php?id=<?= $row['incidencia_id'] ?>">
                                Modificar
                            </a>

                            <form action="esborrar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['incidencia_id'] ?>">

                                <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Segur que vols esborrar aquesta incidència?');">
                                    Descartar
                                </button>
                            </form>

                        </td>
                    </tr>

                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap">

            <?php if ($start > 1): ?>
                <a href="?filtre=<?= $filtre ?>&start=<?= $start - 1 ?>" class="btn btn-outline-dark">
                    <img src="../img/flechal.png" alt="Flecha Izquierda" style="height:30px; padding:5px"> Anterior
                </a>
            <?php endif; ?>

            <?php
            $maxButtons = 5;

            $inicio = max(1, $start - 2); //max 1 hace que no pueda bajar de 1, start - 2 es para que la pagina en la que estes este justo en medio de las 5, ej 8 tiene 6 y 7 detras, 9 y 10 delante
            $fin = min($totalPages, $inicio + $maxButtons - 1); //Esto sirve para dejarlo en medio
            $inicio = max(1, $fin - $maxButtons + 1); //Esto le assigna los valores actuales a inicio otra vez

            //FOr que cuenta los 5 votones

            for ($i = $inicio; $i <= $fin; $i++):
            ?>
                <a href="?filtre=<?= $filtre ?>&start=<?= $i ?>"
                   class="btn <?= ($i == $start) ? 'btn-success text-dark fw-bold' : 'btn-outline-success' ?>">
                    <?= $i ?>
                </a>

            <?php endfor; ?>

            <?php if ($start < $totalPages): ?>
                <a href="?filtre=<?= $filtre ?>&start=<?= $start + 1 ?>" class="btn btn-outline-dark"> Següent
                    <img src="../img/flechad.png" alt="Flecha Derecha" style="height:30px; padding:5px">
                </a>
            <?php endif; ?>

        </div>

    <?php else: ?>
        <div class="alert alert-info">No hi ha incidències.</div>

        <?php if ($start > 1): ?>
            <a href="?filtre=<?= $filtre ?>&start=<?= $start - 1 ?>" style="background-color:#e5e6e3; border-color:#000000;" class="btn text-dark fw-bold">
                <img src="../img/flechal.png" alt="Flecha Izquierda" style="height:30px; padding:5px"> Anterior
            </a>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once 'footer.php'; ?>