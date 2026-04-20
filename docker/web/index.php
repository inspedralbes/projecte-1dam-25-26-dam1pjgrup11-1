<?php
// Connexió a la base de dades
// Nota: 'db' és el nom del servei definit al docker-compose.yml
// Docker actua com a DNS i resol 'db' a la IP del contenidor MySQL
$host = 'db';
$dbname = 'inventari';
$user = 'usuari';
$password = 'usuari1234';

// Crear connexió amb mysqli
$connexio = mysqli_connect($host, $user, $password, $dbname);

// Comprovar si la connexió ha funcionat
if (!$connexio) {
    die("Error de connexió: " . mysqli_connect_error());
}

// Consulta per obtenir tots els ordinadors
$sql = "SELECT * FROM ordinadors ORDER BY data_insercio DESC";
$resultat = mysqli_query($connexio, $sql);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventari d'Ordinadors</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/estils.css">
</head>
<body>
    <div class="container">
        <h1>📦 Inventari d'Ordinadors</h1>
        
        <a href="nou-equip.php" class="btn">➕ Afegir nou equip</a>
        
        <?php if (mysqli_num_rows($resultat) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data inserció</th>
                        <th>RAM (GB)</th>
                        <th>CPU</th>
                        <th>Persona</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultat)): ?>
                        <tr>
                            <td><?php echo $fila['id']; ?></td>
                            <td><?php echo $fila['data_insercio']; ?></td>
                            <td><?php echo $fila['ram_gb']; ?> GB</td>
                            <td><?php echo $fila['tipus_cpu']; ?></td>
                            <td><?php echo $fila['nom_persona']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty">No hi ha ordinadors a l'inventari</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
// Tancar la connexió
mysqli_close($connexio);
?>
