<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
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
    $dni = $_POST['dni'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $direccion = $_POST['direccion'] ?? null;
    $poblacion = $_POST['poblacion'] ?? null;
    $cp = $_POST['cp'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $mensaje = $_POST['mensaje'];
    $id_gato_form = $_POST['id_gato'];

    if (TicketAdopcion::registrarInteres($pdo, $id_gato_form, $nombres, $apellidos, $email, $dni, $fecha_nacimiento, $direccion, $poblacion, $cp, $telefono, $mensaje)) {
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
    <section class="container">
        <h2>Solicitud de Información / Adopción</h2>
        <p class="<?php echo $clase_mensaje; ?>"><?php echo $mensaje_resultado; ?></p>
        <form method="POST">
            <input type="hidden" name="id_gato" value="<?php echo $id_gato; ?>">
            <div>
                <label for="tipo-solicitud">¿En qué estás interesado/a?</label>
                <select id="tipo-solicitud" name="tipo_solicitud" required>
                    <option value="" disabled selected>Selecciona una opción...</option>
                    <option value="informacion">Quiero información acerca del gato/a</option>
                    <option value="adopcion">Quiero presentar una solicitud de adopción</option>
                </select>
            </div>
            <input type="text" name="nombres" placeholder="Tu Nombre" required>
            <input type="text" name="apellidos" placeholder="Tus Apellidos" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="text" name="poblacion" placeholder="Población">
            <input type="text" name="cp" placeholder="Código postal">
            <input type="tel" name="telefono" placeholder="Teléfono">
            <textarea name="mensaje" placeholder="¿De qué quieres solicitar información o por qué quieres adoptarlo?"></textarea>
            <button type="submit">Enviar solicitud</button>
        </form>
    </section>
    <?php include 'navbar/footer.php' ?>
</body>
</html>