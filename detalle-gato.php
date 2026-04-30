<?php
require_once 'Clases/Conexion.php';
require_once 'Clases/Gato.php';

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$gato = null;

$conexion = new Conexion();
$pdo = $conexion->getConnection();

if ($pdo && $id_gato > 0) {
    $sql = "SELECT * FROM Gatos WHERE id_gato = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_gato, PDO::PARAM_INT);
    $stmt->execute();
    $gato = $stmt->fetch(PDO::FETCH_ASSOC);
}

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
    <title><?php echo htmlspecialchars($gato['nombre']); ?> - Cat Shelter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="navbar">
        <section class="logo">🐱 <span>CatShelter</span></section>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="index.php#catalogo">Adoptar</a></li>
                <li><a href="#">Contacto</a></li>
                <li><a href="login.php" class="btn-login">Admin Login</a></li>
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

                <section class="detalle-datos">
                    <section class="dato">
                        <span class="dato-label">Raza:</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['raza']); ?></span>
                    </section>
                    <section class="dato">
                        <span class="dato-label">Edad:</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['edad']); ?> años</span>
                    </section>
                    <section class="dato">
                        <span class="dato-label">Género:</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($gato['genero']); ?></span>
                    </section>
                    <section class="dato">
                        <span class="dato-label">Esterilizado:</span>
                        <span class="dato-valor"><?php echo $gato['esterilizado'] ? 'Sí' : 'No'; ?></span>
                    </section>
                    <section class="dato">
                        <span class="dato-label">Fecha de nacimiento:</span>
                        <span class="dato-valor"><?php echo date('d/m/Y', strtotime($gato['fecha_nacimiento'])); ?></span>
                    </section>
                </section>

                <h3>Acerca de <?php echo htmlspecialchars($gato['nombre']); ?></h3>
                <p><?php echo htmlspecialchars($gato['descripcion']); ?></p>

                <?php if (strtolower($gato['estado']) === 'disponible'): ?>
                    <button class="btn-primary" onclick="alert('¡Gracias por tu interés! Pronto podrás completar la solicitud de adopción.')">Solicitar adopción</button>
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
