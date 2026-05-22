<?php
session_start();
require_once 'Clases/Conexion.php';
require_once 'Clases/Admin.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = (new Conexion())->getConnection();
    // Aquí se genera el Objeto Admin y se asigna la sesión automáticamente
    $user = Admin::login($pdo, $_POST['email'], $_POST['password']);
    
    if($user) {
        header("Location: Admin/admin-index.php");
        exit(); // Detiene la ejecución del script garantizando la redirección
    } else {
        echo "<script>alert('Credenciales incorrectas o no tienes permisos de administrador.');</script>";
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

    <?php include 'navbar/footer.php' ?>
</body>
</html>