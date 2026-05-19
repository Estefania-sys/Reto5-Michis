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
    <h1>Entre oficina y ronroneos: Mi misión</h1>
    
    <section class="bio-container">
        <p><strong>¡Hola! Soy la persona detrás de este refugio.</strong></p>
        
        <p>Durante el día, cumplo con mi jornada laboral como cualquier otra profesional, pero mi verdadera vocación comienza cuando cierro el ordenador. Mi vida está dedicada a ser el puente entre el abandono y el calor de un hogar.</p>

        <p>Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong></p>

        <blockquote>
            "Rescatar a un gato no cambiará el mundo, pero para ese gato, su mundo habrá cambiado para siempre."
        </blockquote>
    </section>
</section>

<section class="botoneracontacto">
    <h2>¿Estás listo para darle una oportunidad?</h2>
    <p>Si quieres adoptar, ser casa de acogida o simplemente conocer más sobre mi labor, estaré encantada de hablar contigo.</p>
    
    <p>
        <a class="contactobtn" href="contacto.php"><i class="fa-solid fa-address-book"></i>  Contacta conmigo aquí</a>
    </p>
</section>
<?php include 'navbar/footer.php' ?>

</body>
</html>