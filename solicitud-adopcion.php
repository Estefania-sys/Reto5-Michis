<?php
require_once 'Clases/Conexion.php';
require_once 'Clases/TicketAdopcion.php';
require_once 'Clases/Gato.php';

$db = new Conexion();
$pdo = $db->getConnection();

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$gato = null;

if ($id_gato) {
    $gato = Gato::obtenerPorId($pdo, $id_gato);
}

$mensaje_resultado = "";
$clase_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];
    $id_gato_form = $_POST['id_gato'];

    if (TicketAdopcion::registrarInteres($pdo, $id_gato_form, $nombres, $apellidos, $email, $mensaje)) {
        $mensaje_resultado = "¡Solicitud enviada! Nos pondremos en contacto contigo pronto.";
        $clase_mensaje = "mensaje-exito";
    } else {
        $mensaje_resultado = "Hubo un error al procesar tu solicitud.";
        $clase_mensaje = "mensaje-error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Información - Michis</title>
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