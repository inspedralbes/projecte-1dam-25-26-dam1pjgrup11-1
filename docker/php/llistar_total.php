<?php include_once "header.php";
require_once 'connexio.php';

$filtre = $_GET['filtre'] ?? 'sense_assignar';
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

            <option value="total" <?= $filtre == 'total' ? 'selected' : '' ?>>
                Total
            </option>

            <option value="assignades" <?= $filtre == 'assignades' ? 'selected' : '' ?>>
                Assignades
            </option>

            <option value="finalitzades" <?= $filtre == 'finalitzades' ? 'selected' : '' ?>>
                Finalitzades
            </option>

        </select>
    </form>

    <?php

    if ($filtre == 'total') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                ORDER BY i.prioritat";

    } else if ($filtre == 'sense_assignar') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.estat = 'Oberta'
                ORDER BY i.prioritat";

    } else if ($filtre == 'assignades') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.estat = 'En Curs'
                ORDER BY i.prioritat";

    } else if ($filtre == 'finalitzades') {
        $sql = "SELECT i.incidencia_id, i.descripcio_incidencia, i.prioritat, i.estat,
                       t.nom AS tipologia_nom, te.nom AS tecnic_nom, te.cognom AS tecnic_cognom
                FROM incidencia i
                LEFT JOIN tipologia t ON i.tipologia_id = t.tipologia_id
                LEFT JOIN tecnic te ON i.tecnic_id = te.tecnic_id
                WHERE i.estat = 'Finalitzada'
                ORDER BY i.prioritat";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    ?>

    <?php if ($result->num_rows > 0): ?>

        <table class="table table-hover table-bordered align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Descripció</th>
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

    <?php else: ?>
        <div class="alert alert-info">No hi ha incidències.</div>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once 'footer.php'; ?>
