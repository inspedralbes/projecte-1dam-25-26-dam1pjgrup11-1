<?php
session_start();

unset($_SESSION['user']);
unset($_SESSION['user_id']);
unset($_SESSION['rol']);

session_destroy();

header("Location: index.php");
exit;
?>