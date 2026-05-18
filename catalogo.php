<?php
require_once 'Clases/Conexion.php';
require_once 'Clases/Gato.php';
require_once 'Clases/Imagenes.php';

$conexion = new Conexion();
$pdo = $conexion->getConnection();

$gatos = [];
if ($pdo) {
    // Mantenemos la lógica de negocio fuera de la vista
    $sql = "SELECT * FROM Gatos WHERE estado != 'adoptado' ORDER BY nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $gatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cat Shelter - Adopta un amigo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar/header.php'?>

    <main id="catalogo" class="container">
        <h2 class="section-title">Gatitos en adopción</h2>
        
        <section class="grid-gatos">
            <?php if (!empty($gatos)): ?>
                <?php foreach ($gatos as $gato): ?>
                    <?php $nombreMostrar = Imagenes::obtenerNombre($gato); ?>
                    <?php $fotosGato = Imagenes::obtenerFotos($gato); ?>
                    <article class="card">
                        <section class="card-img">
                            <section class="card-carousel <?php echo count($fotosGato) === 1 ? 'single-image' : ''; ?>" id="carousel-<?php echo htmlspecialchars($gato['id_gato']); ?>">
                                <?php foreach ($fotosGato as $index => $foto): ?>
                                    <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nombreMostrar . ' foto ' . ($index + 1)); ?>" class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                <?php endforeach; ?>
                                <button type="button" class="carousel-btn carousel-prev" aria-label="Anterior">‹</button>
                                <button type="button" class="carousel-btn carousel-next" aria-label="Siguiente">›</button>
                            </section>
                            <span class="badge <?php echo strtolower($gato['estado']); ?>">
                                <?php 
                                $estados = [
                                    'disponible' => 'Disponible',
                                    'en tratamiento' => 'En tratamiento',
                                    'adoptado' => 'Adoptado'
                                ];
                                echo isset($estados[$gato['estado']]) ? $estados[$gato['estado']] : ucfirst($gato['estado']);
                                ?>
                            </span>
                        </section>
                        <a href="detalle-gato.php?id=<?php echo htmlspecialchars($gato['id_gato']); ?>" class="card-link">
                            <section class="card-info">
                                <h3><?php echo htmlspecialchars($nombreMostrar); ?></h3>
                                <p class="raza"><?php echo htmlspecialchars($gato['raza'] ?? ''); ?> • <?php echo htmlspecialchars(Gato::calcularEdadDesdeNacimiento($gato['fecha_nacimiento'] ?? '')); ?> años</p>
                                <?php $capa = $gato['capa_patron'] ?? ''; ?>
                                <?php $pelo = $gato['pelo_largo'] ?? ''; ?>
                                <?php if ($capa !== '' || $pelo !== ''): ?>
                                    <p class="meta">
                                        <?php if ($capa !== ''): ?>Capa: <?php echo htmlspecialchars($capa); ?><?php endif; ?>
                                        <?php if ($capa !== '' && $pelo !== ''): ?> • <?php endif; ?>
                                        <?php if ($pelo !== ''): ?>Pelo: <?php echo htmlspecialchars($pelo); ?><?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                <?php $tagList = !empty($gato['character_tags']) ? Gato::parsePgArray($gato['character_tags']) : []; ?>
                                <?php if (!empty($tagList)): ?>
                                    <p class="tags"><?php echo htmlspecialchars(implode(', ', $tagList)); ?></p>
                                <?php endif; ?>
                                <p class="desc"><?php echo htmlspecialchars(substr($gato['notas_cuidador'] ?? '', 0, 60)); ?>...</p>
                            </section>
                        </a>
                        <button class="btn-adoptar" onclick="location.href='detalle-gato.php?id=<?php echo htmlspecialchars($gato['id_gato']); ?>'">Conocer más</button>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay gatos disponibles en este momento.</p>
            <?php endif; ?>
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
                    prevButton.style.display = 'none';
                    nextButton.style.display = 'none';
                    return;
                }

                prevButton.addEventListener('click', event => {
                    event.stopPropagation();
                    activeIndex = (activeIndex - 1 + slides.length) % slides.length;
                    showSlide(activeIndex);
                });

                nextButton.addEventListener('click', event => {
                    event.stopPropagation();
                    activeIndex = (activeIndex + 1) % slides.length;
                    showSlide(activeIndex);
                });
            });
        });
    </script>
</body>
</html>