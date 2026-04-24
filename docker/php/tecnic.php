<?php include_once "header.php"?>

<h1>Quin técnic es vol connectar?</h1>
<h4>Posa el teu nom:</h4>
<form action="llistar.php" method="GET">
    <input type="text" name="id_tecnic" id="id_tecnic" value="Toni">
    <input type="submit" value="Llistar les incidencies" class="btn btn-success btn-lg rounded mt-3 d-block" >
</form>


<?php include_once "footer.php" ?>