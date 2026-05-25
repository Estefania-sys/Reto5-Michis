<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once '../Clases/Conexion.php';
require_once '../Clases/Gato.php';
require_once '../Clases/Imagenes.php';

$pdo = (new Conexion())->getConnection();

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$esNuevo = ($id_gato === 0);

$gato = $esNuevo ? null : Gato::obtenerPorId($pdo, $id_gato);

if (!$esNuevo && !$gato) {
    header('Location: ../catalogo.php');
    exit;
}

$mensaje = "";
$clase_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre'           => $_POST['nombre'],
        'fecha_nacimiento' => $_POST['fecha_nacimiento'], // Añadido
        'raza'             => $_POST['raza'],
        'genero'           => $_POST['genero'],
        'capa_patron'      => $_POST['capa_patron'],
        'pelo_largo'       => $_POST['pelo_largo'],
        'esterilizado'     => isset($_POST['esterilizado']) ? 1 : 0,
        'estado'           => $_POST['estado'],
        'notas_cuidador'   => $_POST['notas_cuidador'],
        'numero_microchip' => $_POST['numero_microchip'], // ¡Aquí es donde da el error!
        'peso_kg'          => $_POST['peso_kg'],
        'tamano'           => $_POST['tamano']
    ];

    try {
        if ($esNuevo) {
            $nuevo_id = Gato::crear($pdo, $datos);
            if ($nuevo_id) {
                // Si hay fotos, las subimos (opcional según tu código)
                header("Location: ../catalogo.php?msg=creado");
                exit;
            }
        } else {
            if (Gato::actualizar($pdo, $id_gato, $datos)) {
                header("Location: ../catalogo.php?msg=actualizado");
                exit;
            }
        }
    } catch (PDOException $e) {
        // Capturamos el error de la base de datos
        if ($e->getCode() == '23505') { // Código SQL para "Unique Violation"
            $mensaje = "Error: El número de microchip ya existe para otro gato.";
        } else {
            $mensaje = "Hubo un error al guardar los datos: " . $e->getMessage();
        }
        $clase_mensaje = "error-banner"; // Clase CSS para ponerlo en rojo
    }

    if ($esNuevo) {
        // 1. Creamos el registro en la BD
        $id_generado = Gato::crear($pdo, $datos);
        if ($id_generado) {
            $id_gato = $id_generado;
            // Preparamos un array con los datos mínimos que tu método subirFoto necesita
            $datosGatoParaFoto = ['id_gato' => $id_gato, 'nombre' => $datos['nombre']];
            
            $primeraFotoGuardada = "";

            // 2. Procesamos la subida de fotos
            if (!empty($_FILES['fotos']['name'][0])) {
                // Como $_FILES['fotos'] es un array de archivos, recorremos cada uno
                foreach ($_FILES['fotos']['name'] as $key => $val) {
                    $fileArray = [
                        'name'     => $_FILES['fotos']['name'][$key],
                        'type'     => $_FILES['fotos']['type'][$key],
                        'tmp_name' => $_FILES['fotos']['tmp_name'][$key],
                        'error'    => $_FILES['fotos']['error'][$key],
                        'size'     => $_FILES['fotos']['size'][$key]
                    ];
                    
                    $rutaSubida = Imagenes::subirFoto($fileArray, $datosGatoParaFoto);
                    
                    // Guardamos la ruta de la primera foto con éxito para la portada
                    if ($rutaSubida && empty($primeraFotoGuardada)) {
                        $primeraFotoGuardada = $rutaSubida;
                    }
                }
            }

            // 3. Si se subieron fotos, actualizamos foto_url con la primera. 
            // Si no, ponemos la ruta de la carpeta (o una por defecto)
            if (!empty($primeraFotoGuardada)) {
                Gato::actualizarFotoUrl($pdo, $id_gato, $primeraFotoGuardada);
            } else {
                // Ruta de carpeta por defecto si no hay fotos
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $datos['nombre']));
                Gato::actualizarFotoUrl($pdo, $id_gato, "Imagenes/Gatos/" . $id_gato . "_" . $slug);
            }

            header("Location: ../detalle-gato.php?id=$id_gato");
            exit;
        }
    } else {
        // Lógica de edición existente
        if (Gato::actualizar($pdo, $id_gato, $datos)) {
            if (!empty($_FILES['fotos']['name'][0])) {
                $datosGatoParaFoto = ['id_gato' => $id_gato, 'nombre' => $datos['nombre']];
                foreach ($_FILES['fotos']['name'] as $key => $val) {
                    $fileArray = [
                        'name'     => $_FILES['fotos']['name'][$key],
                        'type'     => $_FILES['fotos']['type'][$key],
                        'tmp_name' => $_FILES['fotos']['tmp_name'][$key],
                        'error'    => $_FILES['fotos']['error'][$key],
                        'size'     => $_FILES['fotos']['size'][$key]
                    ];
                    $ruta = Imagenes::subirFoto($fileArray, $datosGatoParaFoto);
                    
                    // Si el gato no tenía foto de portada antes, le ponemos esta
                    if ($ruta && (empty($gato['foto_url']) || strpos($gato['foto_url'], '.') === false)) {
                        Gato::actualizarFotoUrl($pdo, $id_gato, $ruta);
                    }
                }
            }
            // Borrar fotos seleccionadas
            if (!empty($_POST['fotos_eliminar'])) {
                foreach ($_POST['fotos_eliminar'] as $fotoRuta) {
                    Imagenes::eliminarFoto($fotoRuta);
                }
            }
            $mensaje = "Gato actualizado correctamente";
            $clase_mensaje = "mensaje-exito";
            $gato = Gato::obtenerPorId($pdo, $id_gato);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="traductor" data-es="Panel de Control - Adopciones" data-ca="Panell de Control - Adopcions"></title>
    <link rel="stylesheet" href="/Reto5-Michis/style.css">
</head>
<body>
    <?php include '../navbar/headeradmin.php'; ?>
    <div class="edit-container">
        <h1 class="traductor" data-es="Editar Gato" data-ca="Editar Gat"></h1>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $clase_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="container">
        <?php if (!empty($mensaje)): ?>
            <div class="<?php echo $clase_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
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
                <label class="traductor" data-es="Fecha de Nacimiento" data-ca="Data de Naixement">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" 
                    value="<?php echo $gato ? htmlspecialchars($gato['fecha_nacimiento'] ?? '') : ''; ?>" 
                    required>
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
                <label for="numero_microchip" class="traductor" data-es="Número de Microchip:" data-ca="Número de Microxip:"></label>
                <input type="text" name="numero_microchip" 
                    value="<?php echo $gato ? htmlspecialchars($gato['numero_microchip'] ?? '') : ''; ?>" 
                    placeholder="Introduce un número único">
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

            <div class="form-actions">
                <button type="submit" class="btn-primary traductor" data-es="Guardar cambios" data-ca="Desar canvis">Guardar cambios</button>
                <a href="../catalogo.php" class="btn-secondary traductor" data-es="Cancelar" data-ca="Cancel·lar">Cancelar</a>
            </div>

            <?php if (!$esNuevo): ?>
                <a href="eliminar-gato.php?id=<?php echo $id_gato; ?>" 
                class="btn-danger" 
                onclick="return confirm('¿Estás seguro de que quieres eliminar a este gato permanentemente? Esta acción no se puede deshacer.');">
                    <i class="fa-solid fa-trash"></i> Eliminar Gato
                </a>
            <?php endif; ?>
        </div>
        </form>
    </div>

    <script src="../traduccionscript.js"></script>
    <?php include '../navbar/footer.php' ?>
</body>
</html>