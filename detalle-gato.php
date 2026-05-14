<?php
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
    <title><?php echo htmlspecialchars($nombreMostrar); ?> - Detalle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar/header.php'?>


    <main class="detalle-container">
        <section class="detalle-header">
            <section class="detalle-img">
                <?php $fotosDetalle = Imagenes::obtenerFotos($gato); ?>
                <div class="card-carousel <?php echo count($fotosDetalle) === 1 ? 'single-image' : ''; ?>" id="detalle-carousel-<?php echo htmlspecialchars($gato['id_gato']); ?>">
                    <?php foreach ($fotosDetalle as $index => $foto): ?>
                        <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nombreMostrar . ' foto ' . ($index + 1)); ?>" class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php endforeach; ?>
                    <button type="button" class="carousel-btn carousel-prev" aria-label="Anterior">‹</button>
                    <button type="button" class="carousel-btn carousel-next" aria-label="Siguiente">›</button>
                </div>
            </section>
            <section class="detalle-info">
                <h1><?php echo htmlspecialchars($nombreMostrar); ?></h1>
                
                <section class="info-medica">
                    <h3>Historial Médico y Vacunas</h3>
                    <?php if (!empty($vacunas)): ?>
                        <ul>
                            <?php foreach ($vacunas as $vacuna): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars(date('d/m/Y', strtotime($vacuna['fecha_revision']))); ?>:</strong>
                                    <?php echo htmlspecialchars($vacuna['nombre_vacuna']); ?>
                                    <?php if (!empty($vacuna['fecha_vacuna'])): ?>
                                        (vacuna aplicada el <?php echo htmlspecialchars(date('d/m/Y', strtotime($vacuna['fecha_vacuna']))); ?>)
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php elseif (!empty($historial)): ?>
                        <ul>
                            <?php foreach ($historial as $h): ?>
                                <li><strong><?php echo htmlspecialchars(date('d/m/Y', strtotime($h['fecha_revision']))); ?>:</strong> <?php echo htmlspecialchars($h['nombre_vacuna'] ?? 'Revisión general'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No hay registros médicos públicos disponibles.</p>
                    <?php endif; ?>
                </section>

                <div class="detalle-datos">
                    <div class="dato">
                        <span class="dato-label">Raza:</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['raza']); ?></span>
                    </div>
                    <div class="dato">
                        <span class="dato-label">Edad:</span>
                        <span class="dato-valor"><?php echo htmlspecialchars(Gato::calcularEdadDesdeNacimiento($gato['fecha_nacimiento'])); ?> años</span>
                    </div>
                    <div class="dato">
                        <span class="dato-label">Género:</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['genero']); ?></span>
                    </div>
                    <div class="dato">
                        <span class="dato-label">Esterilizado:</span>
                        <span class="dato-valor"><?php echo $gato['esterilizado'] ? 'Sí' : 'No'; ?></span>
                    </div>
                    <div class="dato">
                        <span class="dato-label">Fecha de nacimiento:</span>
                        <span class="dato-valor"><?php echo date('d/m/Y', strtotime($gato['fecha_nacimiento'])); ?></span>
                    </div>
                    <?php $capaPatron = $gato['capa_patron'] ?? ''; ?>
                    <?php $peloLargo = $gato['pelo_largo'] ?? ''; ?>
                    <?php $tags = !empty($gato['character_tags']) ? Gato::parsePgArray($gato['character_tags']) : []; ?>

                    <?php if ($capaPatron !== ''): ?>
                        <div class="dato">
                            <span class="dato-label">Patrón de color:</span>
                            <span class="dato-valor"><?php echo htmlspecialchars($capaPatron); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($peloLargo !== ''): ?>
                        <div class="dato">
                            <span class="dato-label">Pelo:</span>
                            <span class="dato-valor"><?php echo htmlspecialchars($peloLargo); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tags)): ?>
                        <div class="dato">
                            <span class="dato-label">Característica:</span>
                            <span class="dato-valor"><?php echo htmlspecialchars(implode(', ', $tags)); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="dato">
                        <span class="dato-label">Vacunas registradas:</span>
                        <span class="dato-valor"><?php echo !empty($vacunas) ? 'Sí' : 'No'; ?></span>
                    </div>
                </div>

                <h3>Acerca de <?php echo htmlspecialchars($gato['nombre'] ?? ''); ?></h3>
                <p><?php echo htmlspecialchars($gato['notas_cuidador'] ?? ''); ?></p>

                <?php if (strtolower($gato['estado']) === 'disponible'): ?>
                    <a href="solicitud-adopcion.php?id=<?php echo $gato['id_gato']; ?>" class="btn-primary">Solicitar información / Cita</a>
                <?php endif; ?>

                <a href="catalogo.php" class="btn-secondary">← Volver al catálogo</a>
            </section>
        </section>
    </main>
    <?php include 'navbar/footer.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.card-carousel').forEach(carousel => {
                const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
                const prevButton = carousel.querySelector('.carousel-prev');
                const nextButton = carousel.querySelector('.carousel-next');
                let activeIndex = 0;

                const showSlide = index => {
                    slides.forEach((slide, slideIndex) => {
                        slide.classList.toggle('active', slideIndex === index);
                    });
                };

                if (slides.length <= 1) {
                    if (prevButton) prevButton.style.display = 'none';
                    if (nextButton) nextButton.style.display = 'none';
                    return;
                }

                if (prevButton) {
                    prevButton.addEventListener('click', event => {
                        event.stopPropagation();
                        activeIndex = (activeIndex - 1 + slides.length) % slides.length;
                        showSlide(activeIndex);
                    });
                }

                if (nextButton) {
                    nextButton.addEventListener('click', event => {
                        event.stopPropagation();
                        activeIndex = (activeIndex + 1) % slides.length;
                        showSlide(activeIndex);
                    });
                }
            });
        });
    </script>
</body>
</html>