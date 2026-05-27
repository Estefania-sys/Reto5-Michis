<?php
require_once __DIR__ . '/../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/../Clases/Gato.php'; 
require_once __DIR__ . '/../Blog/BlogMichis.php';
require_once __DIR__ . '/../Clases/Imagenes.php';

$blog = new BlogMichis();
$errorBlog = $blog->getErrorMessage();
$mensaje = "";

// Obtener la conexión PDO para listar los gatos
$pdo = (new Conexion())->getConnection();
$gatosAdoptados = Gato::listarAdoptados($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $blog->isAvailable()) {
    $idGatoSeleccionado = $_POST['id_gato'];
    $nombreGatoSeleccionado = "";

    // 2. Buscamos el nombre del gato seleccionado en el array para estructurarlo como lo pide Imagenes.php
    foreach ($gatosAdoptados as $g) {
        if ((string)$g['id_gato'] === (string)$idGatoSeleccionado) {
            $nombreGatoSeleccionado = $g['nombre'];
            break;
        }
    }

    // Estructura que requiere tu método findFolderName / subirFoto
    $gatoData = [
        'id_gato' => $idGatoSeleccionado,
        'nombre'  => $nombreGatoSeleccionado
    ];

    $rutaFotoGuardada = false;

    // 3. Procesamos el archivo físico usando tu clase Imagenes
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $rutaFotoGuardada = Imagenes::subirFoto($_FILES['foto'], $gatoData);
    }

    if ($rutaFotoGuardada !== false) {
        // 4. Si la foto se subió con éxito, pasamos '$rutaFotoGuardada' a MongoDB en vez de $_POST['foto']
        $res = $blog->crearPost($idGatoSeleccionado, $_POST['titulo'], $_POST['historia'], $rutaFotoGuardada);
        
        if ($res && $res->getInsertedCount() > 0) {
            $mensaje = "exito";

            // Guardar también en JSON
            $rutaJson = __DIR__ . '/blog.json';
            $historias = file_exists($rutaJson) 
                ? json_decode(file_get_contents($rutaJson), true) 
                : [];

            if (!is_array($historias)) $historias = [];

            $historias[] = [
                'id_gato'   => $idGatoSeleccionado,
                'titulo'    => $_POST['titulo'],
                'contenido' => $_POST['historia'],
                'foto'      => $rutaFotoGuardada, // <--- Guardamos la ruta física generada en el JSON
                'fecha'     => date('d/m/Y')
            ];

            file_put_contents($rutaJson, json_encode($historias, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $errorBlog = "No se pudo insertar la historia en la base de datos.";
        }
    } else {
        $errorBlog = "Error al subir o procesar la imagen física del gato.";
    }
}
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
                <form method="POST" class="estilo-formulario" enctype="multipart/form-data">
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
                        <label class="traductor" data-es="Imagen:" data-ca="Imatge:"></label>
                        <input type="file" name="foto" accept="image/*" required>
                    </section>

                    <button type="submit" class="btn-primary traductor" data-es="Publicar en el Blog" data-ca="Publicar al Blog"></button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>