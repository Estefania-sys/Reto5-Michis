<?php
require_once 'Clases/Conexion.php';
require_once 'Clases/Gato.php';

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
                    <article class="card">
                        <a href="detalle-gato.php?id=<?php echo htmlspecialchars($gato['id_gato']); ?>" class="card-link">
                            <section class="card-img">
                                <img src="Imagenes/Gatos/<?php echo htmlspecialchars($gato['nombre']); ?>.png" alt="<?php echo htmlspecialchars($gato['nombre']); ?>">
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
                            <section class="card-info">
                                <h3><?php echo htmlspecialchars($gato['nombre']); ?></h3>
                                <p class="raza"><?php echo htmlspecialchars($gato['raza']); ?> • <?php echo htmlspecialchars($gato['edad']); ?> años</p>
                                <p class="desc"><?php echo htmlspecialchars(substr($gato['descripcion'], 0, 60)); ?>...</p>
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

    <footer>
        <p>&copy; 2026 Cat Shelter Proyecto Final.</p>
    </footer>

</body>
</html>