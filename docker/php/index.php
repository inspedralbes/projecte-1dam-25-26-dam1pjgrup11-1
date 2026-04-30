<header>
    <?php include_once "header.php"?>
</header>

<div class="titol">
    <h1>GI3P</h1>
    <h3>Gestió incidències informàtiques Institut Pedralbes</h3>
</div>
<div class="container-fluid vh-100">
  <div class="row h-100">

    <div class="col-6 d-flex justify-content-center align-items-center bg-light">
      <div class="botones d-flex flex-column justify-content-center align-items-center gap-2">
        <h3>Qui ets?</h3>
        <a href="crear.php" class="btn btn-danger">Professor</a>
        <a href="tecnic.php" class="btn btn-primary">Tècnic</a>
        <a href="llistar_total.php" class="btn btn-dark">Admin</a>
      </div>
    </div>

    <div class="col-6 d-flex flex-column justify-content-center align-items-center bg-light">
    
    <h3 class="mb-4">Buscar una incidència</h3>

    <form method="GET" action="buscar_id.php" class="card p-4 shadow-sm mb-4">
        <fieldset>
            <legend class="h5 mb-3">INCIDÈNCIA</legend>

            <label for="incidencia_id" class="form-label">Id de la incidència:</label>

            <input type="number" id="incidencia_id" name="incidencia_id" class="form-control" required>

            <button type="submit" class="btn btn-primary mt-3">Buscar</button>
        </fieldset>
    </form>

    </div>

</div>
</div>
    
<?php include_once "footer.php"?>