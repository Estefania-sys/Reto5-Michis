<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contáctanos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Incluimos el CSS de Font Awesome -->
</head>
<body>
<?php Admin::renderizarHeader(); ?>
<section class="contactos">
    <h1>¡Contáctanos!</h1>
    <p>Si tienes alguna duda respecto a los animales que tenemos en la protectora, no dudes en contactarnos en:</p>
</section>
<section class="botoneracontacto">
    <p><a class="contactobtn" href="mailto:correo@ejemplo.com"><i class="fa-solid fa-envelope-circle-check"></i> Envíame un Email clicando aquí a correo@ejemplo.com</a></p>
    <p><a class="contactobtn" href="tel:123456789"><i class="fa-solid fa-phone"></i> Llámanos clicando aquí o al número: 123 45 67 89</a></p>
</section>

<?php include 'navbar/footer.php' ?>

</body>
</html>