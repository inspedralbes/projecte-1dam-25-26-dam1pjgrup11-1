<?php

include "conexio.php";

if (isset($_GET['id_tecnic'])) {

    $id_tecnic = $_GET['id_tecnic'];

    $sql = "SELECT id, name FROM incidencia WHERE tecnic_id = '$id_tecnic'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            echo "<p>ID: " . $row["id"] . " - Nom: " . htmlspecialchars($row["name"]) .
                 " <a href='esborrar.php?id=" . $row["id"] . "'>Esborrar</a></p>";
        }

    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

} else {
    echo "<p>No s'ha rebut cap tècnic.</p>";
}

$conn->close();
?>