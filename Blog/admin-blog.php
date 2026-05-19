<?php
require_once __DIR__ . '/../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/BlogMichis.php';

$blog = new BlogMichis();
$errorBlog = $blog->getErrorMessage();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $blog->isAvailable()) {
    $res = $blog->crearPost($_POST['id_gato'], $_POST['titulo'], $_POST['historia'], $_POST['foto']);
    if ($res && $res->getInsertedCount() > 0) {
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
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php Admin::renderizarHeader('../'); ?>
    <main class="container">
        <section class="form-box">
            <h2>Publicar un "Final Feliz"</h2>
                <?php if ($mensaje): ?>
                <p class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>

            <?php if (!empty($errorBlog)): ?>
                <p class="mensaje-error"><?php echo htmlspecialchars($errorBlog); ?></p>
            <?php endif; ?>

            <?php if ($blog->isAvailable()): ?>
                <form method="POST" class="estilo-formulario">
                    <section class="grupo-input">
                        <label>Selecciona al Gato:</label>
                        <select name="id_gato" required>
                            <?php foreach ($gatosAdoptados as $g): ?>
                                <option value="<?php echo htmlspecialchars($g['id_gato']); ?>"><?php echo htmlspecialchars($g['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </section>

                    <section class="grupo-input">
                        <label>Título de la historia:</label>
                        <input type="text" name="titulo" placeholder="Ej: El nuevo hogar de Luna" required>
                    </section>

                    <section class="grupo-input">
                        <label>La historia:</label>
                        <textarea name="historia" rows="6" required></textarea>
                    </section>

                    <section class="grupo-input">
                        <label>Nombre del archivo de imagen (Ej: luna_feliz.jpg):</label>
                        <input type="text" name="foto" required>
                    </section>

                    <button type="submit" class="btn-primary">Publicar en el Blog</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>