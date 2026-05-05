<?php
require_once 'Clases/Conexion.php';
require_once 'Clases/Gato.php';

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdo = (new Conexion())->getConnection();

// Usamos el método de la clase
$gato = Gato::obtenerPorId($pdo, $id_gato);
$historial = Gato::obtenerHistorial($pdo, $id_gato);

if (!$gato) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($gato['nombre']); ?> - Detalle</title>
    <link rel="stylesheet" href="style.css">m
</head>
<body>
    <header class="navbar">
        <section class="logo">🐱 <span>CatShelter</span></section>
        <nav>
            <ul>
                <a href="index.php">Inicio</a>
                <a href="#catalogo">Adoptar</a>
                <a href="#">Contacto</a>
                <a href="login.php" class="btn-login">Admin Login</a>
            </ul>
        </nav>
    </header>

    <main class="detalle-container">
        <section class="detalle-header">
            <section class="detalle-img">
                <img src="Imagenes/Gatos/<?php echo htmlspecialchars($gato['nombre']); ?>.png" alt="<?php echo htmlspecialchars($gato['nombre']); ?>">
            </section>
            <section class="detalle-info">
                <h1><?php echo htmlspecialchars($gato['nombre']); ?></h1>
                
                <section class="info-medica">
                    <h3>Historial Médico y Vacunas</h3>
                    <?php if (!empty($historial)): ?>
                        <ul>
                            <?php foreach ($historial as $h): ?>
                                <li><strong><?php echo $h['fecha_revision']; ?>:</strong> <?php echo htmlspecialchars($h['nombre_vacuna'] ?? 'Revisión general'); ?></li>
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
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['edad']); ?> años</span>
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
                </div>

                <h3>Acerca de <?php echo htmlspecialchars($gato['nombre']); ?></h3>
                <p><?php echo htmlspecialchars($gato['descripcion']); ?></p>

                <?php if (strtolower($gato['estado']) === 'disponible'): ?>
                    <a href="solicitud-adopcion.php?id=<?php echo $gato['id_gato']; ?>" class="btn-primary" style="text-decoration: none; display: inline-block; text-align: center;">Solicitar información / Cita</a>
                <?php endif; ?>

                <a href="index.php#catalogo" class="btn-volver">← Volver al catálogo</a>
            </section>
        </section>
    </main>
    <footer>
        <p>&copy; 2026 Cat Shelter Proyecto Final. Hecho con ❤️ para los michis.</p>
    </footer>
</body>
</html>