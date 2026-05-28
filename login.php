<?php
session_start();
require_once 'Clases/Conexion.php';
require_once 'Clases/Admin.php';
require_once 'Clases/Voluntaria.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = (new Conexion())->getConnection();
    
    // 1. Intentamos login como Administrador
    $admin = Admin::login($pdo, $_POST['email'], $_POST['password']);
    if($admin) {
        header("Location: Admin/paneladmin.php");
        exit();
    }

    // 2. Si no es admin, intentamos como Voluntaria
    $voluntaria = Voluntaria::login($pdo, $_POST['email'], $_POST['password']);
    if($voluntaria) {
        header("Location: Admin/paneladmin.php");
        exit();
    } else {
        echo "<script>alert('Credenciales incorrectas o acceso denegado.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <link rel="icon" href="Imagenes/Items/logoconfondo.jpg">
</head>
<body>
    <?php include 'navbar/header.php'?>

    <section class="login-box">
    <form method="POST">
        <h2 class="traductor" data-es="Acceso Personal" data-ca="Accés Personal">Acceso Personal</h2>
        
        <input class="traductor" type="email" name="email" required
               data-es-placeholder="Email" 
               data-ca-placeholder="Correu Electrònic" 
               placeholder="Email">
               
        <input class="traductor" type="password" name="password" required
               data-es-placeholder="Contraseña" 
               data-ca-placeholder="Contrasenya" 
               placeholder="Contraseña">
               
        <button class="traductor" type="submit" 
                data-es="Iniciar Sesión" 
                data-ca="Iniciar Sessió">Iniciar Sesión</button>
    </form>
    <?php include 'navbar/footer.php' ?>
</body>
</html>