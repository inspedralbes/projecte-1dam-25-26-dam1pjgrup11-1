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
    $_SESSION['login_error'] = "Tots els camps són obligatoris";
    header("Location: index.php");
    exit;
}

$sql = "SELECT u.usuari_id, u.email, u.password, u.rol, t.tecnic_id
        FROM usuari u
        LEFT JOIN tecnic t ON u.usuari_id = t.usuari_id
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $password === $user['password']) {

    $_SESSION['user'] = $user['email'];
    $_SESSION['user_id'] = $user['usuari_id'];
    $_SESSION['rol'] = $user['rol'];
    $_SESSION['tecnic_id'] = $user['tecnic_id'] ?? null;

    include_once 'logger.php';

    if ($user['rol'] === 'professor'){
    header("Location: professor.php?usuari_id=" . $user['usuari_id']);
    exit;
    }elseif ($user['rol'] === 'tecnic'){
         header("Location: tecnic.php?usuari_id=" . $user['usuari_id']);
         exit;
    }elseif ($user['rol'] === 'admin'){
             header("Location: llistar_total.php");
             exit;
    }else {
    header("Location: index.php");
    exit;
    }

} else {
    $_SESSION['login_error'] = "Usuari o contrasenya incorrectes";
    header("Location: ../");
    exit;
}
?>