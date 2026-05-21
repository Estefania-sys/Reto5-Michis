<?php
session_start();
require_once 'Clases/Conexion.php';
require_once 'Clases/Admin.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = (new Conexion())->getConnection();
    $user = Admin::login($pdo, $_POST['email'], $_POST['password']);
    if($user) {
        header("Location: Admin/admin-index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Inicio de Sesión</title>
</head>
<body>
    <?php include 'navbar/header.php'?>
    <section class="login-box">
        <form method="POST">
            <h2 class="traductor" lang="es">Acceso Personal</h2>
            <h2 class="traductor" lang="ca">Accés Personal</h2>
            <input class="traductor" lang="es" type="email" name="email" placeholder="Email" required>
            <input class="traductor" lang="ca" type="email" name="email" placeholder="Correu Electrònic" required>
            <input class="traductor" lang="es" type="password" name="password" placeholder="Contraseña" required>
            <input class="traductor" lang="ca" type="password" name="password" placeholder="Contrasenya" required>
            <button class="traductor" lang="es" type="submit">Iniciar Sesión</button>
            <button class="traductor" lang="ca" type="submit">Iniciar Sessió</button>
        </form>
    </section>
    <?php include 'navbar/footer.php' ?>
</body>
</html>