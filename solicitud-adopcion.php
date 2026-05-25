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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <link rel="icon" href="Imagenes/Items/logoconfondo.jpg">
</head>
<body>
<?php include 'navbar/header.php'?>

<section class="container">
        <h2 class="traductor" data-es="Solicitud de Información / Adopción" data-ca="Sol·licitud d'Informació / Adopció">Solicitud de Información / Adopción</h2>
        
        <p class="<?php echo $clase_mensaje; ?>"><?php echo $mensaje_resultado; ?></p>
        
        <form method="POST">
            <input type="hidden" name="id_gato" value="<?php echo $id_gato; ?>">
            
            <div>
                <label class="traductor" for="tipo-solicitud" 
                       data-es="¿En qué estás interesado/a?" 
                       data-ca="¿En què estàs interessat/a?">¿En qué estás interesado/a?</label>
                
                <select id="tipo-solicitud" name="tipo_solicitud" required>
                    <option class="traductor" value="" disabled selected 
                            data-es="Selecciona una opción..." 
                            data-ca="Selecciona una opció...">Selecciona una opción...</option>
                    <option class="traductor" value="informacion" 
                            data-es="Quiero información acerca del gato/a" 
                            data-ca="Vull informació sobre el gat/a">Quiero información acerca del gato/a</option>
                    <option class="traductor" value="adopcion" 
                            data-es="Quiero presentar una solicitud de adopción" 
                            data-ca="Vull presentar una sol·licitud d'adopció">Quiero presentar una solicitud de adopción</option>
                </select>
            </div>
            
            <input class="traductor" type="text" name="nombres" required
                   data-es-placeholder="Tu Nombre" data-ca-placeholder="El teu Nom" placeholder="Tu Nombre">
                   
            <input class="traductor" type="text" name="apellidos" required
                   data-es-placeholder="Tus Apellidos" data-ca-placeholder="Els teus Cognoms" placeholder="Tus Apellidos">
                   
            <input class="traductor" type="email" name="email" required
                   data-es-placeholder="Correo electrónico" data-ca-placeholder="Correu electrònic" placeholder="Correo electrónico">
                   
            <input class="traductor" type="text" name="poblacion" 
                   data-es-placeholder="Población" data-ca-placeholder="Població" placeholder="Población">
                   
            <input class="traductor" type="text" name="cp" 
                   data-es-placeholder="Código postal" data-ca-placeholder="Codi postal" placeholder="Código postal">
                   
            <input class="traductor" type="tel" name="telefono" 
                   data-es-placeholder="Teléfono" data-ca-placeholder="Telèfon" placeholder="Teléfono">
                   
            <textarea class="traductor" name="mensaje" 
                      data-es-placeholder="¿De qué quieres solicitar información o por qué quieres adoptarlo?" 
                      data-ca-placeholder="¿De què vols sol·licitar informació o per què vols adoptar-lo?" 
                      placeholder="¿De qué quieres solicitar información o por qué quieres adoptarlo?"></textarea>
                      
            <button class="traductor" type="submit" 
                    data-es="Enviar solicitud" data-ca="Enviar sol·licitud">Enviar solicitud</button>
        </form>
    </section>

    <?php include 'navbar/footer.php' ?>
</body>
</html>