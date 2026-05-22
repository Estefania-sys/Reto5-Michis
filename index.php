<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menu de Inicio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
</head>
<body>
<?php include 'navbar/headeradmin.php'; ?>

<section class="contactos">
    <h1 class="traductor" 
        data-es="Entre oficina y ronroneos: Mi misión" 
        data-ca="Entre oficina i ronronejos: La meva missió">Entre oficina y ronroneos: Mi misión</h1>

    <section class="bio-container">
        <p class="traductor" 
           data-es="<strong>¡Hola! Soy la persona detrás de este refugio.</strong>" 
           data-ca="<strong>Hola! Sóc la persona darrere d'aquest refugi.</strong>"><strong>¡Hola! Soy la persona detrás de este refugio.</strong></p>
        
        <p class="traductor" 
           data-es="Durante el día, cumplo con mi jornada laboral como cualquier otra profesional, pero mi verdadera vocación comienza cuando cierro el ordenador. Mi vida está dedicada a ser el puente entre el abandono y el calor de un hogar."
           data-ca="Durant el dia, completo la meva jornada laboral com qualsevol altra professional, però la meva veritable vocació comença quan tanco l'ordinador. La meva vida està dedicada a ser el pont entre l'abandonament i el calor d'un lloc.">Durante el día, cumplo con mi jornada laboral como cualquier otra profesional, pero mi verdadera vocación comienza cuando cierro el ordenador. Mi vida está dedicada a ser el puente entre el abandono y el calor de un hogar.</p>

        <p class="traductor" 
           data-es="Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong>"
           data-ca="Aquest refugi va néixer de la necessitat de donar veu a qui no la té. De forma totalment voluntària i personal, gestio aquest espai amb un únic objectiu: <strong>que cada gat que passi per les meves mans trobi una família que el amï per sempre.</strong>">Este refugio nació de la necesidad de dar voz a quienes no la tienen. De forma totalmente voluntaria y personal, gestiono este espacio con un único objetivo: <strong>que cada gato que pase por mis manos encuentre una familia que lo ame para siempre.</strong></p>

        <blockquote class="traductor" 
                    data-es="&ldquo;Rescatar a un gato no cambiará el mundo, pero para ese gato, su mundo habrá cambiado para siempre.&rdquo;"
                    data-ca="&ldquo;Rescatar a un gat no canviarà el món, però per a aquest gat, el seu món haurà canviat per sempre.&rdquo;">"Rescatar a un gato no cambiará el mundo, pero para ese gato, su mundo habrá cambiado para siempre."</blockquote>
    </section>
</section>

<section class="botoneracontacto">
    <h2 class="traductor" 
        data-es="¿Estás listo para darle una oportunidad?" 
        data-ca="¿Ets preparat per donar-li una oportunitat?">¿Estás listo para darle una oportunidad?</h2>
        
    <p class="traductor" 
       data-es="Si quieres adoptar, ser casa de acogida o simplemente conocer más sobre mi labor, estaré encantada de hablar contigo."
       data-ca="Si vols adoptar, ser casa de acogida o simplemente conèixer més sobre la meva feina, estaré encantada de parlar amb tu.">Si quieres adoptar, ser casa de acogida o simplemente conocer más sobre mi labor, estaré encantada de hablar contigo.</p>
    
    <p><a class="contactobtn traductor" href="contacto.php" 
          data-es="<i class='fa-solid fa-address-book'></i> Contacta conmigo aquí" 
          data-ca="<i class='fa-solid fa-address-book'></i> Contacta amb mi aquí"><i class="fa-solid fa-address-book"></i> Contacta conmigo aquí</a></p>
</section>

<?php include 'navbar/footer.php' ?>
</body>
</html>