<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huellas con Alma | Refugio de Gatos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
</head>
<body>
<?php Admin::renderizarHeader(); ?>

<section class="contactos">
    <h1 class="traductor" lang="es">Entre oficina y ronroneos: Mi misión</h1>
    <h1 class="traductor" lang="ca">Entre oficina i ronronejos: La meva missió</h1>

    <section class="bio-container">
        <p class="traductor" lang="es"><strong>¡Hola! Soy la persona detrás de este refugio.</strong></p>
        <p class="traductor" lang="ca"><strong>Hola! Sóc la persona darrere d'aquest refugi.</strong></p>
        
        <p class="traductor" lang="es">Durante el día, cumplo con mi jornada laboral como cualquier otra profesional, pero mi verdadera vocación comienza cuando cierro el ordenador. Mi vida está dedicada a ser el puente entre el abandono y el calor de un hogar.</p>
        <p class="traductor" lang="ca">Durant el dia, completo la meva jornada laboral com qualsevol altra professional, però la meva veritable vocació comença quan tanco l'ordinador. La meva vida està dedicada a ser el pont entre l'abandonament i el calor d'un lloc.</p>

        <p class="traductor" lang="es">Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong></p>
        <p class="traductor" lang="ca">Aquest refugi va néixer de la necessitat de donar veu a qui no la té. De forma totalment voluntària i personal, gestio aquest espai amb un únic objectiu: <strong>que cada gat que passi per les meves mans trobi una família que el amï per sempre.</strong></p>

        <blockquote class="traductor" lang="es">"Rescatar a un gato no cambiará el mundo, pero para ese gato, su mundo habrá cambiado para siempre."
        </blockquote>
        <blockquote class="traductor" lang="ca">"Rescatar a un gat no canviarà el món, però per a aquest gat, el seu món haurà canviat per sempre."
        </blockquote>
    </section>
</section>

<section class="botoneracontacto">
    <h2 class="traductor" lang="es">¿Estás listo para darle una oportunidad?</h2>
    <h2 class="traductor" lang="ca">¿Ets preparat per donar-li una oportunitat?</h2>
    <p class="traductor" lang="es">Si quieres adoptar, ser casa de acogida o simplemente conocer más sobre mi labor, estaré encantada de hablar contigo.</p>
    <p class="traductor" lang="ca">Si vols adoptar, ser casa de acogida o simplemente conèixer més sobre la meva feina, estaré encantada de parlar amb tu.</p>
    <p>
        <a class="contactobtn" href="contacto.php"><i class="fa-solid fa-address-book"></i>  Contacta conmigo aquí</a>
    </p>
</section>
<?php include 'navbar/footer.php' ?>

</body>
</html>