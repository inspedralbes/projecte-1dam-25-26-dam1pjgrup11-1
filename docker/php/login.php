<?php
session_start();
require_once 'connexio.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo "Tots els camps són obligatoris";
    exit;
}

$sql = "SELECT usuari_id, email, password, rol FROM usuari WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $password === $user['password']) {

    $_SESSION['user'] = $user['email'];
    $_SESSION['user_id'] = $user['usuari_id'];
    $_SESSION['rol'] = $user['rol'];

    header("Location: index.php");
    exit;

} else {
    echo "Usuari o contrasenya incorrectes";
}
?>