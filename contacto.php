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
<?php include 'navbar/headeradmin.php'; ?>
<section class="contactos">
    <h1 class="traductor" lang="es">¡Contáctanos!</h1>
    <h1 class="traductor" lang="ca">¡Contacta amb nosaltres!</h1>
    <p class="traductor" lang="es">Si tienes alguna duda respecto a los animales que tenemos en la protectora, no dudes en contactarnos en:</p>
    <p class="traductor" lang="ca">Si tens alguna duda respecte als animals que tenim a la protectora, no dubtes en contactar amb nosaltres a:</p>
</section>
<section class="botoneracontacto">
    <p class="traductor" lang="es"><a class="contactobtn" href="mailto:correo@ejemplo.com"><i class="fa-solid fa-envelope-circle-check"></i> Envíame un Email clicando aquí a correo@ejemplo.com</a></p>
    <p class="traductor" lang="ca"><a class="contactobtn" href="mailto:correo@ejemplo.com"><i class="fa-solid fa-envelope-circle-check"></i> Envia'm un Email fent clic aquí a correo@ejemplo.com</a></p>
    <p class="traductor" lang="es"><a class="contactobtn" href="tel:123456789"><i class="fa-solid fa-phone"></i> Llámanos clicando aquí o al número: 123 45 67 89</a></p>
    <p class="traductor" lang="ca"><a class="contactobtn" href="tel:123456789"><i class="fa-solid fa-phone"></i> Trucar-nos fent clic aquí o al número: 123 45 67 89</a></p>
</section>

<?php include 'navbar/footer.php' ?>

</body>
</html>