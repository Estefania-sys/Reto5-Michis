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
        header("Location: Admin/admin-index.php");
        exit();
    } else {
        echo "<script>alert('Credenciales incorrectas o acceso denegado.');</script>";
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
</section>

        <form method="POST">
            <h2>Acceso Personal</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
    </section>
    <?php include 'navbar/footer.php' ?>
</body>
</html>