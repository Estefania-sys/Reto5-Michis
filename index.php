<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Inicio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <link rel="icon" href="Imagenes/Items/logoconfondo.jpg">
</head>
<body>
<?php include 'navbar/header.php'; ?>


<section class="contactos">
    <h1 class="traductor" lang="es">Asociación Colonias Gatos Sant Quirze Safaja</h1>
    <h1 class="traductor" lang="ca">Associació Còlonies Gats Sant Quirze Safaja</h1>

    <section class="bio-container">
        <h3 class="traductor" lang="es">Bienvenidos a nuestro espacio dedicado a la protección, bienestar y búsqueda de hogar para los gatos de nuestro municipio.</h3>
        <h3 class="traductor" lang="ca">Benvinguts al nostre espai dedicat a la protecció, el benestar i la cerca d'un lloc per a els gats del nostre municipi.</h3>
        &nbsp;
        <p class="traductor" lang="es">Nosotros trabajamos incansablemente para gestionar y proteger las colonias de felinos de nuestro entorno. Nuestro objetivo principal es garantizar que cada animal reciba la atención, el respeto y la calidad de vida que se merece.</p>
        <p class="traductor" lang="ca">Treballem incansablement per gestionar i protegir les colònies de felins del nostre entorn. El nostre objectiu principal és garantir que cada animal rebi l'atenció, el respecte i la qualitat de vida que es mereix.</p>
        &nbsp;
        <p class="traductor" lang="es">Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong></p>
        <p class="traductor" lang="ca">Aquest refugi va néixer de la necessitat de donar veu a qui no la té. De forma totalment voluntària i personal, gestio aquest espai amb un únic objectiu: <strong>que cada gat que passi per les meves mans trobi una família que el amï per sempre.</strong></p>
        &nbsp;
        <blockquote class="traductor" lang="es"><b><i>"Rescatar a un gato no cambiará el mundo, pero para ese gato, su mundo habrá cambiado para siempre."</i></b></blockquote>
        <blockquote class="traductor" lang="ca"><b><i>"Rescatar a un gat no canviarà el món, però per a aquest gat, el seu món haurà canviat per sempre."</i></b></blockquote>
        &nbsp;
        <h2 class="traductor" lang="es">¿Estás interesado/a en algún aspecto de nuestra asociación?</h2>
        <h2 class="traductor" lang="ca">¿Estàs interessat/a en algun aspecte de la nostra associació?</h2>
        <p class="traductor" lang="es">Si quieres adoptar, saber más sobre nuestra labor, o simplemente conocernos, no dudes en contactarnos, ¡estaremos encantados de atenderte!</p>
        <p class="traductor" lang="ca">Si vols adoptar, saber més sobre la nostra feina, o simplement conversar amb nosaltres, no dubtes en contactar-nos, estaràs encantat de atendre't!</p>
        <p class="traductor" lang="es">¡Tampoco dudes en revisar a nuestros gatos disponibles para adopción!</p>
        <p class="traductor" lang="ca">¡Tampoc dubtes en revisar a nuestros gatos disponibles para adopción!</p>
</section>
<section class="botoneracontacto">
    <p class="traductor" lang="es"><a class="contactobtn" href="contacto.php"><i class="fa-solid fa-address-book"></i>  Contacta conmigo aquí</a></p>
    <p class="traductor" lang="ca"><a class="contactobtn" href="contacto.php"><i class="fa-solid fa-address-book"></i>  Contacta amb mi aquí</a></p>
    <p class="traductor" lang="es"><a class="contactobtn" href="catalogo.php"><i class="fa-solid fa-cat"></i>  Nuestros Gatos</a></p>
    <p class="traductor" lang="ca"><a class="contactobtn" href="catalogo.php"><i class="fa-solid fa-cat"></i>  Els nostres gats</a></p>
</section>
<?php include 'navbar/footer.php' ?>

</body>
</html>