<?php
require_once 'Clases/Conexion.php';
require_once 'Clases/Adopcion.php';

$id_gato = $_GET['id'] ?? 0;
$mensaje = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = (new Conexion())->getConnection();
    $ok = Adopcion::registrarInteres($pdo, $_POST['id_gato'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['mensaje']);
    $mensaje = $ok ? "Solicitud enviada. Contactaremos contigo pronto." : "Hubo un error.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar/header.php'?>
    <div class="container">
        <h2>Solicitud de Adopción</h2>
        <p><?php echo $mensaje; ?></p>
        <form method="POST">
            <input type="hidden" name="id_gato" value="<?php echo $id_gato; ?>">
            <input type="text" name="nombre" placeholder="Tu Nombre" required>
            <input type="text" name="apellido" placeholder="Tus Apellidos" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <textarea name="mensaje" placeholder="¿Por qué quieres adoptarlo?"></textarea>
            <button type="submit">Enviar solicitud</button>
        </form>
    </div>
</body>
</html>