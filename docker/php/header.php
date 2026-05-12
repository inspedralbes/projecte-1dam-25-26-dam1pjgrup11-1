<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'logger.php';
?>

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

<?php $paginaActual = basename($_SERVER['SCRIPT_NAME']);?>

<header class="bg-white shadow-sm py-3 mb-3">
    <div class="container d-flex align-items-center gap-3">

        <a href="../">
            <img src="../img/logo.png" alt="Logo" style="height:50px;">
        </a>

        <div class="ms-3">
            <?php if (isset($_SESSION['user'])): ?>

                <span class="fw-bold text-dark me-2">
                    <?= htmlspecialchars($_SESSION['user']) ?>
                </span>

                <a href="logout.php" class="text-danger fw-bold">
                    Tancar sessió
                </a>

            <?php endif; ?>
        </div>

        <nav class="menu_header ms-auto d-flex gap-3">

            <?php if (isset($_SESSION['rol'])): ?>

                <?php if ($_SESSION['rol'] === 'professor'): ?>
                    <a href="professor.php">Professor</a>
                <?php elseif ($_SESSION['rol'] === 'tecnic'): ?>
                    <a href="tecnic.php">Tècnic</a>
                <?php elseif ($_SESSION['rol'] === 'admin'): ?>
                    <a href="llistar_total.php">Admin</a>
                <?php endif; ?>

            <?php endif; ?>

        </nav>

    </div>
</header>

<?php if ($paginaActual === 'buscar_id.php' && ($_SESSION['rol'] ?? '') === 'professor'): ?>
    <a href="professor.php" class="button tornar">Tornar</a>
<?php elseif ($paginaActual === 'buscar_id.php' && ($_SESSION['rol'] ?? '') === 'tecnic'): ?>
    <a href="tecnic.php" class="button tornar">Tornar</a>
<?php elseif ($paginaActual === 'buscar_id.php' && ($_SESSION['rol'] ?? '') === 'admin'): ?>
    <a href="llistar_total.php" class="button tornar">Tornar</a>
<?php elseif ($paginaActual !== 'index.php' && $paginaActual !== 'professor.php' && $paginaActual !== 'llistar_total.php' && $paginaActual !== 'tecnic.php'): ?>
    <a href="javascript:history.back()" class="button tornar">Tornar</a>
<?php endif; ?>