<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

require_once 'Clases/Conexion.php';
require_once 'Clases/BlogMichis.php';

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog = new BlogMichis();
    $res = $blog->crearPost($_POST['id_gato'], $_POST['titulo'], $_POST['historia'], $_POST['foto']);
    if($res->getInsertedCount() > 0) {
        $mensaje = "¡Historia publicada con éxito en MongoDB!";
    }
}

// Obtener gatos adoptados de Postgres para el desplegable
$pdo = (new Conexion())->getConnection();
$gatosAdoptados = $pdo->query("SELECT id_gato, nombre FROM Gatos WHERE estado = 'adoptado'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar Final Feliz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar/headeradmin.php' ?>
    <div class="container">
        <h2>Publicar un "Final Feliz" (Blog NoSQL)</h2>
        <?php if($mensaje) echo "<p class='mensaje-exito'>$mensaje</p>"; ?>

        <form method="POST" class="form-solicitud">
            <label>Selecciona al Gato:</label>
            <select name="id_gato" required>
                <?php foreach($gatosAdoptados as $g): ?>
                    <option value="<?php echo $g['id_gato']; ?>"><?php echo $g['nombre']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>Título de la historia:</label>
            <input type="text" name="titulo" placeholder="Ej: El nuevo hogar de Luna" required>

            <label>La historia:</label>
            <textarea name="historia" rows="6" required></textarea>

            <label>Nombre del archivo de imagen (Ej: luna_feliz.jpg):</label>
            <input type="text" name="foto" required>

            <button type="submit" class="btn-primary">Publicar en el Blog</button>
        </form>
    </div>
</body>
</html>