<?php
session_start();
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

<?php
$paginaActual = basename($_SERVER['PHP_SELF']);
?>

<header class="bg-white shadow-sm py-3 mb-3">
    <div class="container d-flex align-items-center gap-3">

        <a href="../">
            <img src="../img/logo.png" alt="Logo" style="height:50px;">
        </a>

        <div class="ms-3">
            <?php if (!isset($_SESSION['user'])): ?>

                <a href="#" class="text-dark fw-bold"
                   data-bs-toggle="modal" data-bs-target="#loginModal">
                    Iniciar Sesió
                </a>

            <?php else: ?>

                <span class="fw-bold text-dark me-2">
                    <?= htmlspecialchars($_SESSION['user']) ?>
                </span>

                <a href="logout.php" class="text-danger fw-bold">
                    Tancar sessió
                </a>

            <?php endif; ?>
        </div>

        <nav class="menu_header ms-auto d-flex gap-3">

            <?php if ($paginaActual === 'crear.php'): ?>
                <a href="tecnic.php">Tecnic</a>
                <a href="llistar_total.php">Admin</a>

            <?php elseif ($paginaActual === 'tecnic.php'): ?>
                <a href="crear.php">Professor</a>
                <a href="llistar_total.php">Admin</a>

            <?php elseif ($paginaActual === 'llistar_total.php'): ?>
                <a href="crear.php">Professor</a>
                <a href="tecnic.php">Tecnic</a>

            <?php elseif ($paginaActual !== 'index.php'): ?>
                <a href="crear.php">Professor</a>
                <a href="tecnic.php">Tecnic</a>
                <a href="llistar_total.php">Admin</a>
            <?php endif; ?>

        </nav>

    </div>
</header>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Iniciar sessió</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

          <?php if (isset($_SESSION['login_error'])): ?>
              <div class="alert alert-danger">
                  <?= $_SESSION['login_error'] ?>
              </div>
              <?php unset($_SESSION['login_error']); ?>
          <?php endif; ?>

        <div class="d-flex align-items-center gap-4">

          <div class="flex-shrink-0">
            <a href="../">
              <img src="../img/gi3p.png" alt="Logo" style="height:120px;">
            </a>
          </div>

          <div class="flex-grow-1">

            <form action="login.php" method="POST">

              <div class="mb-3">
                <label class="form-label">Correu electrònic</label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Contrasenya</label>
                <input type="password" name="password" class="form-control" required>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                Entrar
              </button>

            </form>

          </div>

        </div>

      </div>
    </div>
  </div>
</div>

<?php if ($paginaActual === 'crear.php' || $paginaActual === 'llistar_total.php'): ?>
    <a href="index.php" class="button tornar">Tornar</a>
<?php elseif ($paginaActual !== 'index.php'): ?>
    <a href="javascript:history.back()" class="button tornar">Tornar</a>
<?php endif; ?>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🔥 ESTE ES EL FIX QUE TE FALTABA -->
<?php if (isset($_SESSION['login_error'])): ?>
<script>
window.addEventListener('load', function () {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
});
</script>
<?php endif; ?>

</body>
</html>