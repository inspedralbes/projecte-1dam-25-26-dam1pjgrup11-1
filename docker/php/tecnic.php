<?php 
include_once "header.php";
require_once "connexio.php";

$sql = "SELECT tecnic_id, nom, cognom    FROM tecnic ORDER BY nom";
$result = $conn->query($sql);
?>

<a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">← Tornar</a>

<div class="d-flex justify-content-center align-items-center vh-100">

    <div class="text-center w-50">

        <h1 class="mb-3">Quin tècnic es vol connectar?</h1>
        <h4 class="mb-4">Selecciona el teu tècnic:</h4>

        <form action="llistar.php" method="GET">

            <select name="tecnic_id" id="tecnic_id" class="form-select mb-3">
                <option value="">-- Tria un tècnic --</option>

                <?php
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['tecnic_id'] . '">' . htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['cognom']) . '</option>';
                }
                ?>
            </select>

            <input type="submit" value="Llistar les incidències" class="btn btn-success btn-lg rounded w-100">
        </form>

    </div>

</div>

<?php 
$conn->close();
include_once "footer.php";
?>