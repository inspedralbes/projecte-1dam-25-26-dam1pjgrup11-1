<?php
session_start();
include_once "logger.php";
include_once "header.php";
?>

<main class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">GI3P</h1>
        <h3>Gestió d’incidències informàtiques Institut Pedralbes</h3>
    </div>

    <div class="d-flex justify-content-center gap-4 flex-wrap">

        <div class="card shadow-sm p-4 w-100" style="max-width: 350px;">

            <h4 class="text-center mb-3" style="color: black !important;">Iniciar sessió</h4>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['login_error'] ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <div class="mb-3">
                    <label class="form-label">Correu electrònic</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contrasenya</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    Entrar
                </button>

            </form>

        </div>

        <div class="card shadow-sm p-4 w-100" style="max-width: 350px;">

            <h4 class="text-center mb-4" style="color: black !important;">Buscar incidència</h4>

            <form method="GET" action="buscar_id.php">

                <input type="number"
                       name="incidencia_id"
                       class="form-control mb-2"
                       placeholder="ID incidència"
                       required>

                <button type="submit" class="btn btn-warning w-100 fw-bold">
                    Buscar
                </button>

            </form>

        </div>

    </div>

</main>

<?php include_once "footer.php"; ?>