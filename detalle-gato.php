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
    <style>
        .detalle-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        .detalle-header {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .detalle-img {
            flex: 1;
            min-width: 300px;
        }
        .detalle-img img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .detalle-info {
            flex: 1;
            min-width: 300px;
        }
        .detalle-info h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        .badge.disponible {
            background-color: #4ade80;
            color: white;
        }
        .badge.en.tratamiento {
            background-color: #facc15;
            color: #333;
        }
        .detalle-datos {
            margin: 20px 0;
        }
        .dato {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .dato-label {
            font-weight: bold;
            color: #666;
        }
        .dato-valor {
            color: #333;
        }
        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn-volver:hover {
            background-color: #2563eb;
        }
    </style>
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
        <div class="detalle-header">
            <div class="detalle-img">
                <img src="Imagenes/Gatos/<?php echo htmlspecialchars($gato['nombre']); ?>.png" alt="<?php echo htmlspecialchars($gato['nombre']); ?>">
            </div>
            <div class="detalle-info">
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
                    <button class="btn-primary" onclick="alert('¡Gracias por tu interés! Pronto podrás completar la solicitud de adopción.')">Solicitar adopción</button>
                <?php endif; ?>

                <a href="index.php#catalogo" class="btn-volver">← Volver al catálogo</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Cat Shelter Proyecto Final. Hecho con ❤️ para los michis.</p>
    </footer>

</body>
</html>
