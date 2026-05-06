<?php
session_start();
require_once 'Clases/Conexion.php';
require_once 'Clases/Usuario.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = (new Conexion())->getConnection();
    $user = Usuario::login($pdo, $_POST['email'], $_POST['password']);
    if($user) {
        $_SESSION['admin'] = $user->getNombreCompleto();
        header("Location: admin-dashboard.php");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar/header.php'?>
    <div class="login-box">
        <form method="POST">
            <h2>Acceso Personal</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
    <?php include 'navbar/footer.php' ?>
</body>
</html>