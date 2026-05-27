<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Inicio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <link rel="icon" href="Imagenes/Items/logoconfondo.jpg">
</head>
<body>
<?php include 'navbar/header.php'; ?>

<section class="contactos">
    <h1 class="traductor"
        data-es="Asociación Colonias Gatos Sant Quirze Safaja"
        data-ca="Associació Còlonies Gats Sant Quirze Safaja">Asociación Colonias Gatos Sant Quirze Safaja</h1>

    <section class="bio-container">
        <h3 class="traductor"
            data-es="Bienvenidos a nuestro espacio dedicado a la protección, bienestar y búsqueda de hogar para los gatos de nuestro municipio."
            data-ca="Benvinguts al nostre espai dedicat a la protecció, el benestar i la cerca d'un lloc per a els gats del nostre municipi.">Bienvenidos a nuestro espacio dedicado a la protección, bienestar y búsqueda de hogar para los gatos de nuestro municipio.</h3>
        &nbsp;
        <p class="traductor"
           data-es="Nosotros trabajamos incansablemente para gestionar y proteger las colonias de felinos de nuestro entorno. Nuestro objetivo principal es garantizar que cada animal reciba la atención, el respeto y la calidad de vida que se merece."
           data-ca="Treballem incansablement per gestionar i protegir les colònies de felins del nostre entorn. El nostre objectiu principal és garantir que cada animal rebi l'atenció, el respecte i la qualitat de vida que es mereix.">Nosotros trabajamos incansablemente para gestionar y proteger las colonias de felinos de nuestro entorno. Nuestro objetivo principal es garantizar que cada animal reciba la atención, el respeto y la calidad de vida que se merece.</p>
        &nbsp;
        <p class="traductor"
           data-es="Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong>"
           data-ca="Aquest refugi va néixer de la necessitat de donar veu a qui no la té. De forma totalment voluntària i personal, gestio aquest espai amb un únic objectiu: <strong>que cada gat que passi per les meves mans trobi una família que el amï per sempre.</strong>">Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong></p>
        &nbsp;
        <p class="traductor"
                    data-es="<b><i>Rescatar a un gato no cambiará el mundo, pero para ese gato, su mundo habrá cambiado para siempre.</i></b>"
                    data-ca="<b><i>Rescatar a un gat no canviarà el món, però per a aquest gat, el seu món haurà canviat per sempre.</i></b>"></p>
        &nbsp;
        <h2 class="traductor"
            data-es="¿Estás interesado/a en algún aspecto de nuestra asociación?"
            data-ca="¿Estàs interessat/a en algun aspecte de la nostra associació?">¿Estás interesado/a en algún aspecto de nuestra asociación?</h2>
        <p class="traductor"
           data-es="Si quieres adoptar, saber más sobre nuestra labor, o simplemente conocernos, no dudes en contactarnos, ¡estaremos encantados de atenderte!"
           data-ca="Si vols adoptar, saber més sobre la nostra feina, o simplement conversar amb nosaltres, no dubtes en contactar-nos, estaràs encantat de atendre't!">Si quieres adoptar, saber más sobre nuestra labor, o simplemente conocernos, no dudes en contactarnos, ¡estaremos encantados de atenderte!</p>
        <p class="traductor"
           data-es="¡Tampoco dudes en revisar a nuestros gatos disponibles para adopción!"
           data-ca="¡Tampoc dubtes en revisar els nostres gats disponibles per a adopció!">¡Tampoco dudes en revisar a nuestros gatos disponibles para adopción!</p>
    </section>

    <section class="botoneracontacto">
        <p><a class="contactobtn traductor"
              href="contacto.php"
              data-es="<i class='fa-solid fa-address-book'></i>  Contacta conmigo aquí"
              data-ca="<i class='fa-solid fa-address-book'></i>  Contacta amb mi aquí"><i class="fa-solid fa-address-book"></i>  Contacta conmigo aquí</a></p>
        <p><a class="contactobtn traductor"
              href="catalogo.php"
              data-es="<i class='fa-solid fa-cat'></i>  Nuestros Gatos"
              data-ca="<i class='fa-solid fa-cat'></i>  Els nostres gats"><i class="fa-solid fa-cat"></i>  Nuestros Gatos</a></p>
    </section>
</section>

<?php include 'navbar/footer.php' ?>
</body>
</html>