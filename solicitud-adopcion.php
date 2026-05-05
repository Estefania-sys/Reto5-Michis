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
<<<<<<< HEAD
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
=======
    <header class="navbar">
        <section class="logo">🐱 <span>CatShelter</span></section>
        <nav>
            <ul>
                <a href="index.php">Inicio</a>
                <a href="index.php#catalogo">Adoptar</a>
                <a href="#">Contacto</a>
                <a href="login.php" class="btn-login">Admin Login</a>
            </ul>
        </nav>
    </header>

    <main class="form-container">
        <section class="form-box">
            <h1>Solicitud para: <?php echo $gato ? htmlspecialchars($gato['nombre']) : 'Michi'; ?></h1>
            
            <?php if ($mensaje_resultado): ?>
                <div class="<?php echo $clase_mensaje; ?>">
                    <?php echo $mensaje_resultado; ?>
                </div>
            <?php endif; ?>

            <form action="solicitud-adopcion.php?id=<?php echo $id_gato; ?>" method="POST" class="estilo-formulario">
                <input type="hidden" name="id_gato" value="<?php echo htmlspecialchars($id_gato); ?>">

                <div class="grupo-input">
                    <label>Nombres:</label>
                    <input type="text" name="nombres" placeholder="Tu nombre" required>
                </div>

                <div class="grupo-input">
                    <label>Apellidos:</label>
                    <input type="text" name="apellidos" placeholder="Tus apellidos" required>
                </div>

                <div class="grupo-input">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="grupo-input">
                    <label>Mensaje / Consulta:</label>
                    <textarea name="mensaje" rows="4" placeholder="¿Por qué quieres adoptar o qué información necesitas?" required></textarea>
                </div>

                <button type="submit" class="btn-primary">Enviar Solicitud</button>
            </form>
            
            <a href="detalle-gato.php?id=<?php echo $id_gato; ?>" class="btn-volver">← Volver al detalle</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Cat Shelter Proyecto Final.</p>
    </footer>
>>>>>>> 41c928fd405608b2f4efd8d9afaab08bc4e43051
</body>
</html>