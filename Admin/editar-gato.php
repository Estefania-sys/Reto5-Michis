<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once '../Clases/Conexion.php';
require_once '../Clases/Gato.php';
require_once '../Clases/Imagenes.php';

$pdo = (new Conexion())->getConnection();

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$gato = Gato::obtenerPorId($pdo, $id_gato);

if (!$gato) {
    header('Location: admin-index.php');
    exit;
}

$mensaje = "";
$clase_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos_actualizados = [
        'nombre'           => $_POST['nombre'] ?? $gato['nombre'],
        'raza'             => $_POST['raza'] ?? $gato['raza'],
        'genero'           => $_POST['genero'] ?? $gato['genero'],
        'capa_patron'      => $_POST['capa_patron'] ?? $gato['capa_patron'],
        'pelo_largo'       => $_POST['pelo_largo'] ?? $gato['pelo_largo'],
        'esterilizado'     => isset($_POST['esterilizado']) ? 1 : 0,
        'estado'           => $_POST['estado'] ?? $gato['estado'],
        'notas_cuidador'   => $_POST['notas_cuidador'] ?? $gato['notas_cuidador'],
        'numero_microchip' => !empty($_POST['numero_microchip']) ? $_POST['numero_microchip'] : null,
        'peso_kg'          => !empty($_POST['peso_kg']) ? floatval($_POST['peso_kg']) : null,
        'tamano'           => !empty($_POST['tamano']) ? $_POST['tamano'] : null,
    ];

    if (Gato::actualizar($pdo, $id_gato, $datos_actualizados)) {
        
        // 1. GESTIÓN DE BORRADO DE IMÁGENES SELECCIONADAS
        if (!empty($_POST['fotos_eliminar']) && is_array($_POST['fotos_eliminar'])) {
            foreach ($_POST['fotos_eliminar'] as $rutaFoto) {
                Imagenes::eliminarFoto($rutaFoto);
                
                // Si la foto eliminada coincide con la principal 'foto_url' de la BD, la limpiamos
                if ($gato['foto_url'] === $rutaFoto || str_replace('Imagenes/Gatos/cache/', 'Imagenes/Gatos/', $rutaFoto) === $gato['foto_url']) {
                    Gato::actualizarFotoUrl($pdo, $id_gato, null);
                }
            }
        }

        // 2. GESTIÓN DE SUBIDA DE NUEVAS IMÁGENES
        if (isset($_FILES['fotos_subir']) && !empty($_FILES['fotos_subir']['name'][0])) {
            $totalArchivos = count($_FILES['fotos_subir']['name']);
            
            for ($i = 0; $i < $totalArchivos; $i++) {
                $archivoIndividual = [
                    'name'     => $_FILES['fotos_subir']['name'][$i],
                    'type'     => $_FILES['fotos_subir']['type'][$i],
                    'tmp_name' => $_FILES['fotos_subir']['tmp_name'][$i],
                    'error'    => $_FILES['fotos_subir']['error'][$i],
                    'size'     => $_FILES['fotos_subir']['size'][$i]
                ];

                // Subir archivo al servidor físico
                $rutaGuardada = Imagenes::subirFoto($archivoIndividual, $gato);
                
                // ¡IMPORTANTE! Vinculamos la primera imagen subida con la columna 'foto_url' de tu base de datos
                if ($rutaGuardada && ($i === 0 || empty($gato['foto_url']))) {
                    Gato::actualizarFotoUrl($pdo, $id_gato, $rutaGuardada);
                }
            }
        }

        header('Location: ../catalogo.php');
        exit;
    } else {
        $mensaje = "Error al actualizar los datos.";
        $clase_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title class="traductor" data-es="Editar Gato" data-ca="Editar Gat">Editar Gato</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="edit-container">
        <h1 class="traductor" data-es="Editar Gato" data-ca="Editar Gat"></h1>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $clase_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="edit-form">
            <div class="form-group">
                <label for="nombre" class="traductor" data-es="Nombre:" data-ca="Nom:"></label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($gato['nombre'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="raza" class="traductor" data-es="Raza:" data-ca="Raça:"></label>
                <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($gato['raza'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="genero" class="traductor" data-es="Género:" data-ca="Gènere:"></label>
                <select id="genero" name="genero">
                    <option value="Macho" class="traductor" data-es="Macho" data-ca="Mascle" <?php echo $gato['genero'] === 'Macho' ? 'selected' : ''; ?>></option>
                    <option value="Hembra" class="traductor" data-es="Hembra" data-ca="Femella" <?php echo $gato['genero'] === 'Hembra' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="capa_patron" class="traductor" data-es="Capa/Patrón:" data-ca="Capa/Patró:"></label>
                <input type="text" id="capa_patron" name="capa_patron" value="<?php echo htmlspecialchars($gato['capa_patron'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="pelo_largo" class="traductor" data-es="Pelo largo:" data-ca="Pèl llarg:"></label>
                <select id="pelo_largo" name="pelo_largo">
                    <option value="sí" class="traductor" data-es="Sí" data-ca="Sí" <?php echo strtolower($gato['pelo_largo'] ?? '') === 'sí' ? 'selected' : ''; ?>></option>
                    <option value="no" class="traductor" data-es="No" data-ca="No" <?php echo strtolower($gato['pelo_largo'] ?? '') === 'no' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group checkbox-group">
                <label for="esterilizado" class="traductor" data-es="Esterilizado:" data-ca="Esterilitzat:"></label>
                <input type="checkbox" id="esterilizado" name="esterilizado" value="1" <?php echo (!empty($gato['esterilizado']) && ($gato['esterilizado'] == 1 || strtolower($gato['esterilizado']) === 'sí')) ? 'checked' : ''; ?>>
            </div>

            <div class="form-group">
                <label for="estado" class="traductor" data-es="Estado:" data-ca="Estat:"></label>
                <select id="estado" name="estado">
                    <option value="Disponible" class="traductor" data-es="Disponible" data-ca="Disponible" <?php echo $gato['estado'] === 'Disponible' ? 'selected' : ''; ?>></option>
                    <option value="Acogida" class="traductor" data-es="En acogida" data-ca="En acollida" <?php echo $gato['estado'] === 'Acogida' ? 'selected' : ''; ?>></option>
                    <option value="Adoptado" class="traductor" data-es="Adoptado" data-ca="Adoptat" <?php echo $gato['estado'] === 'Adoptado' ? 'selected' : ''; ?>></option>
                    <option value="Reservado" class="traductor" data-es="Reservado" data-ca="Reservat" <?php echo $gato['estado'] === 'Reservado' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="numero_microchip" class="traductor" data-es="Número de microchip:" data-ca="Número de microxip:"></label>
                <input type="text" id="numero_microchip" name="numero_microchip" value="<?php echo htmlspecialchars($gato['numero_microchip'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="peso_kg" class="traductor" data-es="Peso (kg):" data-ca="Pes (kg):"></label>
                <input type="number" step="0.01" id="peso_kg" name="peso_kg" value="<?php echo htmlspecialchars($gato['peso_kg'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="tamano" class="traductor" data-es="Tamaño:" data-ca="Mida:"></label>
                <select id="tamano" name="tamano">
                    <option value="" class="traductor" data-es="Selecciona..." data-ca="Selecciona..."></option>
                    <option value="Pequeño" class="traductor" data-es="Pequeño" data-ca="Petit" <?php echo $gato['tamano'] === 'Pequeño' ? 'selected' : ''; ?>></option>
                    <option value="Mediano" class="traductor" data-es="Mediano" data-ca="Mitjà" <?php echo $gato['tamano'] === 'Mediano' ? 'selected' : ''; ?>></option>
                    <option value="Grande" class="traductor" data-es="Grande" data-ca="Gran" <?php echo $gato['tamano'] === 'Grande' ? 'selected' : ''; ?>></option>
                </select>
            </div>

            <div class="form-group">
                <label for="notas_cuidador" class="traductor" data-es="Notas del cuidador:" data-ca="Notes del cuidador:"></label>
                <textarea id="notas_cuidador" name="notas_cuidador"><?php echo htmlspecialchars($gato['notas_cuidador'] ?? ''); ?></textarea>
            </div>

            <div class="seccion-fotos-admin">
                <label style="font-weight: bold;" class="traductor" data-es="Añadir nuevas imágenes al álbum:" data-ca="Afegir noves imatges a l'àlbum:"></label>
                <div class="input-file-container">
                    <input type="file" name="fotos_subir[]" id="fotos_subir" multiple accept="image/*">
                </div>
            </div>

            <?php 
            $fotosGuardadas = Imagenes::obtenerFotos($gato);
            if (!empty($fotosGuardadas)):
            ?>
                <div class="seccion-fotos-admin">
                    <label style="font-weight: bold;" class="traductor" data-es="Imágenes actuales en el servidor:" data-ca="Imatges actuals al servidor:"></label>
                    <p style="font-size:0.85rem; color:#666;" class="traductor" data-es="Marca las casillas de las fotos que desees eliminar permanentemente:" data-ca="Marca les caselles de las fotos que vulguis eliminar permanentment:"></p>
                    
                    <div class="grid-fotos-borrar">
                        <?php foreach ($fotosGuardadas as $foto): 
                            $srcFinal = is_array($foto) ? $foto['src'] : $foto;
                            
                            // Omitimos la foto por defecto para evitar que el usuario intente borrarla
                            if (strpos($srcFinal, 'default.png') !== false) continue;
                        ?>
                            <div class="card-foto-borrar">
                                <img src="../<?php echo htmlspecialchars($srcFinal); ?>" width="100" height="100">
                                <label>
                                    <input type="checkbox" name="fotos_eliminar[]" value="<?php echo htmlspecialchars($srcFinal); ?>"> 
                                    <span class="traductor" data-es="Borrar" data-ca="Esborrar">Borrar</span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-actions" style="margin-top: 30px;">
                <button type="submit" class="btn-primary traductor" data-es="Guardar cambios" data-ca="Desar canvis"></button>
                <a href="../catalogo.php" class="btn-secondary traductor" data-es="Cancelar" data-ca="Cancel·lar">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="../traduccionscript.js"></script>
</body>
</html>