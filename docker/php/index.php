<?php include_once "header.php"?>

<main class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">GI3P</h1>
        <h3 class="text-muted">Gestió d’incidències informàtiques Institut Pedralbes</h3>
    </div>


    <div class="d-flex justify-content-center mb-5">
        <div class="card shadow-sm p-5 text-center w-100" style="max-width: 500px;">

            <h3 class="mb-4">Qui ets?</h3>

            <div class="d-grid gap-3 menu_principal" id ="menu_principal">
                <a href="crear.php" class="btn btn-danger btn-lg">Professor</a>
                <a href="tecnic.php" class="btn btn-primary btn-lg">Tècnic</a>
                <a href="llistar_total.php" class="btn btn-dark btn-lg">Admin</a>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-center">

        <div class="card shadow-sm p-3 w-100" style="max-width: 350px; opacity: 0.85;">

            <h6 class="text-center mb-3 text-muted">
                Buscar incendia (només si ja tens l’ID)
            </h6>

            <form method="GET" action="buscar_id.php">

                <input type="number" name="incidencia_id" class="form-control mb-2" placeholder="ID incidència" required>

                <button type="submit" class="btn btn-warning w-100 fw-bold">Buscar</button>

            </form>

        </div>
    </div>

</main>


<?php include_once "footer.php"?>