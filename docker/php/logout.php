<?php
session_start();

// Eliminar variables de sesión
unset($_SESSION['user']);
unset($_SESSION['user_id']);
unset($_SESSION['rol']);

// Opcional: destruir toda la sesión
session_destroy();

// Redirigir al inicio
header("Location: index.php");
exit;
?>