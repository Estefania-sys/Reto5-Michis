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
        // Mensaje de éxito traducido para el frontend mediante atributos más abajo
        $mensaje = "exito"; 
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
    <?php include '../navbar/headeradmin.php'; ?>
    <main class="container">
        <section class="form-box">
            <h2 class="traductor" data-es="Publicar un 'Final Feliz'" data-ca="Publicar un 'Final Feliç'"></h2>
            
            <?php if ($mensaje === "exito"): ?>
                <p class="mensaje-exito traductor" data-es="¡Historia publicada con éxito en MongoDB!" data-ca="¡Història publicada amb èxit a MongoDB!"></p>
            <?php endif; ?>

            <?php if (!empty($errorBlog)): ?>
                <p class="mensaje-error"><?php echo htmlspecialchars($errorBlog); ?></p>
            <?php endif; ?>

            <?php if ($blog->isAvailable()): ?>
                <form method="POST" class="estilo-formulario">
                    <section class="grupo-input">
                        <label class="traductor" data-es="Selecciona al Gato:" data-ca="Selecciona el Gat:"></label>
                        <select name="id_gato" required>
                            <?php foreach ($gatosAdoptados as $g): ?>
                                <option value="<?php echo htmlspecialchars($g['id_gato']); ?>"><?php echo htmlspecialchars($g['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </section>

                    <section class="grupo-input">
                        <label class="traductor" data-es="Título de la historia:" data-ca="Títol de la història:"></label>
                        <input type="text" name="titulo" class="traductor" data-es-placeholder="Ej: El nuevo hogar de Luna" data-ca-placeholder="Ex: La nova llar de la Luna" required>
                    </section>

                    <section class="grupo-input">
                        <label class="traductor" data-es="La historia:" data-ca="La història:"></label>
                        <textarea name="historia" rows="6" class="traductor" data-es-placeholder="Escribe aquí la historia..." data-ca-placeholder="Escriu aquí la història..." required></textarea>
                    </section>

                    <section class="grupo-input">
                        <label class="traductor" data-es="Nombre del archivo de imagen (Ej: luna_feliz.jpg):" data-ca="Nom del fitxer de imatge (Ex: luna_feliz.jpg):"></label>
                        <input type="text" name="foto" required>
                    </section>

                    <button type="submit" class="btn-primary traductor" data-es="Publicar en el Blog" data-ca="Publicar al Blog"></button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>