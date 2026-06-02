<?php
require_once 'Clases/Admin.php';
Admin::iniciar();
require_once 'Clases/Conexion.php';
require_once 'Clases/Gato.php';
require_once 'Clases/Imagenes.php';

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdo = (new Conexion())->getConnection();

// Usamos el método de la clase
$gato = Gato::obtenerPorId($pdo, $id_gato);
$historial = Gato::obtenerHistorial($pdo, $id_gato);
$vacunas = Gato::obtenerVacunas($pdo, $id_gato);
$nombreMostrar = Imagenes::obtenerNombre($gato);

if (!$gato) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nombreMostrar); ?> - Detalle</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="Imagenes/Items/logoconfondo.jpg">
</head>
<body>
<?php include 'navbar/header.php'?>

    <main class="detalle-container">
        <?php
            $fotosDetalle = Imagenes::obtenerFotos($gato);
            $orientationClass = 'vertical';
        ?>
        <section class="detalle-header">
            <section class="detalle-img">
                <?php $fotosDetalle = Imagenes::obtenerFotos($gato); ?>
                <section class="card-carousel <?php echo count($fotosDetalle) === 1 ? 'single-image' : ''; ?>" id="detalle-carousel-<?php echo htmlspecialchars($gato['id_gato']); ?>">
                    <?php foreach ($fotosDetalle as $index => $foto): ?>
                        <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nombreMostrar . ' foto ' . ($index + 1)); ?>" class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php endforeach; ?>
                    <button type="button" class="carousel-btn carousel-prev" aria-label="Anterior">‹</button>
                    <button type="button" class="carousel-btn carousel-next" aria-label="Siguiente">›</button>
                </section>
            </section>
            <section class="detalle-info">
                <h1><?php echo htmlspecialchars($nombreMostrar); ?></h1>
                
                <section class="info-medica">
                    <h3 class="traductor" data-es="Historial Médico y Vacunas" data-ca="Historial Mèdic i Vacunes">Historial Médico y Vacunas</h3>
                    <?php if (!empty($vacunas)): ?>
                        <ul>
                            <?php foreach ($vacunas as $vacuna): ?>
                                <li>
                                    <strong>Fecha de alta: </strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($vacuna['fecha_revision']))); ?><br>
                                    <?php echo htmlspecialchars($vacuna['nombre_vacuna']); ?>
                                    <?php if (!empty($vacuna['fecha_vacuna'])): ?>
                                        (Fecha de revisión: <?php echo htmlspecialchars(date('d/m/Y', strtotime($vacuna['fecha_vacuna']))); ?>)
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php elseif (!empty($historial)): ?>
                        <ul>
                            <?php foreach ($historial as $h): ?>
                                <li>role="presentation"<strong><?php echo htmlspecialchars(date('d/m/Y', strtotime($h['fecha_revision']))); ?>:</strong> <?php echo htmlspecialchars($h['nombre_vacuna'] ?? 'Revisión general'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="traductor" data-es="No hay registros médicos públicos disponibles." data-ca="No hi ha registres mèdics públics disponibles.">No hay registros médicos públicos disponibles.</p>
                    <?php endif; ?>
                </section>

                <section class="detalle-datos">
                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Raza:" data-ca="Raça:">Raza:</span></span></i></b>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['raza']); ?></span>
                    </section>
                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Edad:" data-ca="Edat:">Edad:</span></span></i></b>
                        <span class="dato-valor"><?php echo htmlspecialchars(Gato::calcularEdadDesdeNacimiento($gato['fecha_nacimiento'])); ?></span>
                    </section>
                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Género:" data-ca="Gènere:">Género:</span></span></i></b>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['genero']); ?></span>
                    </section>
                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Esterilizado:" data-ca="Esterilitzat:">Esterilizado:</span></span></i></b>
                        <span class="dato-valor"><?php echo $gato['esterilizado'] ? 'Sí' : 'No'; ?></span>
                    </section>
                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Fecha de nacimiento:" data-ca="Data de naixement:">Fecha de nacimiento:</span></span></i></b>
                        <span class="dato-valor"><?php echo date('d/m/Y', strtotime($gato['fecha_nacimiento'])); ?></span>
                    </section>
                    <?php $capaPatron = $gato['capa_patron'] ?? ''; ?>
                    <?php $peloLargo = $gato['pelo_largo'] ?? ''; ?>
                    <?php $tags = !empty($gato['character_tags']) ? Gato::parsePgArray($gato['character_tags']) : []; ?>

                    <?php if ($capaPatron !== ''): ?>
                        <section class="dato">
                            <b><i><span class="dato-label"><span class="traductor" data-es="Patrón de color:" data-ca="Patró de color:">Patrón de color:</span></span></i></b>
                            <span class="dato-valor"><?php echo htmlspecialchars($capaPatron); ?></span>
                        </section>
                    <?php endif; ?>

                    <?php if ($peloLargo !== ''): ?>
                        <section class="dato">
                            <b><i><span class="dato-label"><span class="traductor" data-es="Pelo:" data-ca="Pèl:">Pelo:</span></span></i></b>
                            <span class="dato-valor"><?php echo htmlspecialchars($peloLargo); ?></span>
                        </section>
                    <?php endif; ?>

                    <?php if (!empty($tags)): ?>
                        <section class="dato">
                            <b><i><span class="dato-label"><span class="traductor" data-es="Características:" data-ca="Característiques:">Características:</span></span></i></b>
                            <span class="dato-valor"><?php echo htmlspecialchars(implode(', ', $tags)); ?></span>
                        </section>
                    <?php endif; ?>

                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Tamaño:" data-ca="Mida:">Tamaño:</span></span></i></b>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['tamano'] ?? 'No disponible'); ?></span>
                    </section>
                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Peso:" data-ca="Pes:">Peso:</span></span></i></b>
                        <span class="dato-valor"><?php echo !empty($gato['peso_kg']) ? htmlspecialchars(number_format((float)$gato['peso_kg'], 3, ',', '.')) . ' kg' : 'No disponible'; ?></span>
                    </section>

                    <section class="dato">
                        <b><i><span class="dato-label"><span class="traductor" data-es="Vacunas registradas:" data-ca="Vacunes registrades:">Vacunas registradas:</span></span></i></b>
                        <span class="dato-valor"><?php echo !empty($vacunas) ? 'Sí' : 'No'; ?></span>
                    </section>
                </section>

                <br><h3 class="traductor" data-es="Acerca de <?php echo htmlspecialchars($gato['nombre'] ?? ''); ?>" data-ca="Sobre <?php echo htmlspecialchars($gato['nombre'] ?? ''); ?>">Acerca de <?php echo htmlspecialchars($gato['nombre'] ?? ''); ?></h3>
                <p><?php echo htmlspecialchars($gato['notas_cuidador'] ?? ''); ?></p>

                <?php $isDisponible = strtolower(trim($gato['estado'] ?? '')) === 'disponible'; ?>
                <div class="detalle-actions">
                    <section class="botoneraseparacion">
                        <p><a href="solicitud-adopcion.php?id=<?php echo $gato['id_gato']; ?>" class="btn-adoptar<?php echo $isDisponible ? '' : ' btn-disabled'; ?> traductor" <?php echo $isDisponible ? '' : 'aria-disabled="true" tabindex="-1"'; ?> data-es="Solicitar información / Cita" data-ca="Solicitar informació / Cita">Solicitar información / Cita</a></p>
                        <p><a href="catalogo.php" class="btn-secondary traductor" data-es="← Volver al catálogo" data-ca="← Tornar al catàleg">← Volver al catálogo</a></p>
                    </section>
                </div>
            </section>
        </section>
    </main>
    <?php include 'navbar/footer.php' ?>

    <script src="traduccionscript.js"></script>
</body>
</html>