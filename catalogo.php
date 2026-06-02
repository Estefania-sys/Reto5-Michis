<?php
require_once 'Clases/Admin.php';
require_once 'Clases/Voluntaria.php';
require_once 'Clases/Conexion.php';
require_once 'Clases/Gato.php';
require_once 'Clases/Imagenes.php';

Admin::iniciar();
$conexion = new Conexion();
$pdo = $conexion->getConnection();

// Verificar si es admin/voluntario logueado
$esAdmin = Admin::tieneAdminActivo();
$esVoluntaria = Voluntaria::tieneVoluntariaActiva();
if($esAdmin || $esVoluntaria) {
   include 'navbar/headeradmin.php';
} else {
    include 'navbar/header.php';
}

$gatos = [];
if ($pdo) {
    // 1. Siempre obtenemos los gatos que no han sido adoptados
    $gatos = Gato::listarNoAdoptados($pdo);

    // 2. Si el usuario es admin/voluntario, traemos también los adoptados
    if ($esAdmin || $esVoluntaria) {
        // Usamos el método de Gato.php que ahora devuelve todas las columnas (*)
        $gatosAdoptados = Gato::listarAdoptados($pdo);
        
        // Los unimos al final de la lista principal
        $gatos = array_merge($gatos, $gatosAdoptados);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gatos en adopción</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <link rel="icon" href="/Imagenes/Items/logoconfondo.jpg">
</head>
<body>

    <main id="catalogo" class="container">
        <h2 class="section-title">
            <span class="traductor" data-es="Gatitos en adopción" data-ca="Gatets en adopció">Gatitos en adopción</span>
        </h2>
        <?php if ($esAdmin || $esVoluntaria): ?>
            <section class="admin-actions" style="margin-bottom: 20px; text-align: right;">
                <a href="Admin/editar-gato.php" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> 
                    <span class="traductor" data-es="Añadir un nuevo gato" data-ca="Afegir un nou gat">Añadir un nuevo gato</span>
                </a>
            </section>
        <?php endif; ?>
        <section class="grid-gatos">
            <?php if (!empty($gatos)): ?>
                <?php foreach ($gatos as $gato): ?>
                    <?php $nombreMostrar = Imagenes::obtenerNombre($gato); ?>
                    <?php $fotosGato = Imagenes::obtenerFotos($gato); ?>
                    <?php $esAdoptado = ($gato['estado'] === 'adoptado'); ?>
                    <article class="card <?php echo $esAdoptado ? 'gato-adoptado' : ''; ?>">
                        <section class="card-img">
                            <section class="card-carousel <?php echo (empty($fotosGato) || count($fotosGato) === 1) ? 'single-image' : ''; ?>" id="carousel-<?php echo htmlspecialchars($gato['id_gato']); ?>">
                                <?php if (!empty($fotosGato)): ?>
                                    <?php foreach ($fotosGato as $index => $foto): ?>
                                        <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nombreMostrar . ' foto ' . ($index + 1)); ?>" class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <?php endforeach; ?>
                                    
                                    <?php if (count($fotosGato) > 1): ?>
                                        <button type="button" class="carousel-btn carousel-prev" aria-label="Anterior">‹</button>
                                        <button type="button" class="carousel-btn carousel-next" aria-label="Siguiente">›</button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="carousel-slide active">
                                        <i class="fa-solid fa-cat"></i>
                                    </div>
                                <?php endif; ?>
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
                                <p class="raza"><?php echo htmlspecialchars($gato['raza'] ?? ''); ?> • <?php echo htmlspecialchars(Gato::calcularEdadDesdeNacimiento($gato['fecha_nacimiento'] ?? '')); ?></p>
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
                        <section class="botoneraseparacion">
                        </a>
                        <?php if ($esAdmin): ?>
                                    <a href="Admin/editar-gato.php?id=<?php echo htmlspecialchars($gato['id_gato']); ?>" class="btn-adoptar"><i class="fas fa-edit"></i> Editar</a>
                                <?php endif; ?>
                        <a href="detalle-gato.php?id=<?php echo htmlspecialchars($gato['id_gato']); ?>" class="btn-adoptar"><span class="traductor" data-es="Conocer más" data-ca="Conèixer més">Conocer más</span></a>
                        </section>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="traductor" data-es="No hay gatos disponibles en este momento." data-ca="No hi ha gats disponibles en aquest moment.">No hay gatos disponibles en este momento.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'navbar/footer.php' ?>

    <script src="traduccionscript.js"></script>
</body>
</html>