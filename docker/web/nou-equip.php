<?php
// Inicialitzar variables
$missatge = '';
$error = '';

// Processar el formulari quan s'envia
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connexió a la base de dades
    $host = 'db';
    $dbname = 'inventari';
    $user = 'usuari';
    $password = 'usuari1234';
    
    $connexio = mysqli_connect($host, $user, $password, $dbname);
    
    if (!$connexio) {
        $error = "Error de connexió: " . mysqli_connect_error();
    } else {
        // Obtenir les dades del formulari
        $ram_gb = $_POST['ram_gb'];
        $tipus_cpu = $_POST['tipus_cpu'];
        $nom_persona = $_POST['nom_persona'];
        
        // TODO: Sanejar les entrades de l'usuari amb htmlspecialchars() 
        // o mysqli_real_escape_string() per evitar injeccions SQL i XSS
        
        // Obtenir la data actual des del servidor PHP
        // IMPORTANT: La data la genera el servidor, no ve del formulari.
        // Això evita que algú pugui falsificar la data des del frontend
        // modificant el codi HTML o fent peticions manipulades.
        $data_insercio = date('Y-m-d H:i:s');
        
        // Validació bàsica
        if (empty($ram_gb) || empty($tipus_cpu) || empty($nom_persona)) {
            $error = "Tots els camps són obligatoris";
        } elseif (!is_numeric($ram_gb) || $ram_gb <= 0) {
            $error = "La RAM ha de ser un número positiu";
        } else {
            // TODO: Si hi ha errors de validació, hauríem de tornar a mostrar
            // el formulari amb els valors que l'usuari havia introduït als camps
            // correctes, per no obligar-lo a tornar-ho a escriure tot.
            // Exemple: <input value="<?php echo isset($ram_gb) ? $ram_gb : ''; ? > ">
            
            // Preparar la consulta SQL
            // ATENCIÓ: Aquesta consulta NO és segura! Estem concatenant directament
            // les variables a la SQL. En producció hauríem d'usar prepared statements.
            $sql = "INSERT INTO ordinadors (data_insercio, ram_gb, tipus_cpu, nom_persona) 
                    VALUES ('$data_insercio', '$ram_gb', '$tipus_cpu', '$nom_persona')";
            
            // Executar la consulta
            if (mysqli_query($connexio, $sql)) {
                $missatge = "✅ Equip afegit correctament!";
            } else {
                $error = "Error en afegir l'equip: " . mysqli_error($connexio);
            }
        }
        
        mysqli_close($connexio);
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afegir nou equip</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/estils.css">
</head>
<body>
    <div class="container">
        <h1>➕ Afegir nou equip</h1>
        
        <?php if ($missatge): ?>
            <div class="missatge">
                <?php echo $missatge; ?>
                <div>
                    <a href="index.php" class="btn">← Tornar a l'inventari</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!$missatge): ?>
            <form method="POST" action="nou-equip.php">
                <div class="form-group">
                    <label for="ram_gb">RAM (GB):</label>
                    <input type="number" id="ram_gb" name="ram_gb" required min="1">
                </div>
                
                <div class="form-group">
                    <label for="tipus_cpu">Tipus de CPU:</label>
                    <input type="text" id="tipus_cpu" name="tipus_cpu" required 
                           placeholder="Ex: Intel Core i5-12400">
                </div>
                
                <div class="form-group">
                    <label for="nom_persona">Nom de la persona:</label>
                    <input type="text" id="nom_persona" name="nom_persona" required 
                           placeholder="Ex: Maria García">
                </div>
                
                <button type="submit" class="btn">💾 Guardar equip</button>
                <a href="index.php" class="btn">Cancel·lar</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
