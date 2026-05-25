<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Incluimos el CSS de Font Awesome -->
    <link rel="icon" href="Imagenes/Items/logoconfondo.jpg">
</head>
<body>
<?php include 'navbar/headeradmin.php'; ?>
<section class="contactos">
    <h1 class="traductor" data-es="¡Contáctanos!" data-ca="¡Contacta amb nosaltres!">¡Contáctanos!</h1>
    <p class="traductor" data-es="Si tienes alguna duda respecto a los animales que tenemos en la protectora, no dudes en contactarnos en:" data-ca="Si tens alguna duda respecte als animals que tenim a la protectora, no dubtes en contactar amb nosaltres a:">Si tienes alguna duda respecto a los animales que tenemos en la protectora, no dudes en contactarnos en:</p>
</section>
<section class="botoneracontacto">
    <p><a class="contactobtn" href="mailto:correo@ejemplo.com"><i class="fa-solid fa-envelope-circle-check"></i> <span class="traductor" data-es="Envíame un Email clicando aquí a correo@ejemplo.com" data-ca="Envia'm un Email fent clic aquí a correo@ejemplo.com">Envíame un Email clicando aquí a correo@ejemplo.com</span></a></p>
    <p><a class="contactobtn" href="tel:123456789"><i class="fa-solid fa-phone"></i> <span class="traductor" data-es="Llámanos clicando aquí o al número: 123 45 67 89" data-ca="Trucar-nos fent clic aquí o al número: 123 45 67 89">Llámanos clicando aquí o al número: 123 45 67 89</span></a></p>
</section>

<?php include 'navbar/footer.php' ?>

<script src="traduccionscript.js"></script>
</body>
</html>