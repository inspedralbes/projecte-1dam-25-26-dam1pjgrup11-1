<?php include_once "header.php"?>

<a href="../" class="btn btn-secondary mt-3" style="position: absolute; top: 10px; left: 10px;">← Tornar</a>

<h1>Quin técnic es vol connectar?</h1>
<h4>Posa el teu nom:</h4>
<form action="llistar.php" method="GET">
    <input type="text" name="nom_tecnic" id="nom_tecnic" placeholder="Escriu el nom del técnic">
    <input type="submit" value="Llistar les incidencies" class="btn btn-success btn-lg rounded mt-3 d-block">
</form>

<?php include_once "footer.php" ?>
