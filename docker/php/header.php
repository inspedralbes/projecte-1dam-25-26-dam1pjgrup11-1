<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <title>Aplicació incidències</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="js/main.js" defer></script>
</head>

<body>

<header class="bg-white shadow-sm py-3 mb-3">
    <div class="container d-flex align-items-center gap-3">
        <img src="../img/logo.png" alt="Logo" style="height:50px;">
        <strong>Iniciar Sesió</strong>
        <nav class="menu_header ms-auto d-flex gap-3">
            <?php
            $paginaActual = basename($_SERVER['PHP_SELF']);
            if ($paginaActual === 'crear.php' && $paginaActual !== 'index.php'): ?>
                <a href="../">Inici</a>
                <a href="tecnic.php">Tecnic</a>
                <a href="llistar_total.php">Admin</a>
            <?php elseif ($paginaActual === 'tecnic.php' && $paginaActual !== 'index.php'): ?>
                <a href="../">Inici</a>
                <a href="crear.php">Professor</a>
                <a href="llistar_total.php">Admin</a>
            <?php elseif ($paginaActual === 'llistar_total.php' && $paginaActual !== 'index.php'): ?>
                <a href="../">Inici</a>
                <a href="crear.php">Professor</a>
                <a href="tecnic.php">Tecnic</a>
            <?php elseif ($paginaActual !== 'index.php'): ?>
                <a href="../">Inici</a>
                <a href="crear.php">Professor</a>
                <a href="tecnic.php">Tecnic</a>
                <a href="llistar_total.php">Admin</a>
            <?php endif; ?>
            
        </nav>
    </div>
</header>

<?php
if ($paginaActual === 'buscar_id.php' || $paginaActual === 'crear.php' || $paginaActual === 'llistar_total.php'): ?>
    <a href="index.php" class="button tornar">Tornar</a>
<?php elseif ($paginaActual !== 'index.php'): ?>
    <a href="javascript:history.back()" class="button tornar"">Tornar</a>
<?php endif; ?>

