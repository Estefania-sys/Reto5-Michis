<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once '../Clases/Conexion.php';
require_once '../Clases/Gato.php';
require_once '../Clases/HistorialMedico.php';
require_once '../Clases/Imagenes.php';

$pdo = (new Conexion())->getConnection();

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;
$esNuevo = ($id_gato === 0);

$gato = $esNuevo ? null : Gato::obtenerPorId($pdo, $id_gato);

if (!$esNuevo && !$gato) {
    header('Location: ../catalogo.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Iniciamos la transacción ANTES de cualquier operación
    $pdo->beginTransaction();

    try {
        // Formateo de etiquetas para PostgreSQL
        $tagsRaw = $_POST['character_tags'] ?? '';
        $tagsArray = array_filter(array_map('trim', explode(',', $tagsRaw)));
        $pgTags = '{' . implode(',', $tagsArray) . '}';

        $datos = [
            'nombre'           => $_POST['nombre'],
            'fecha_nacimiento' => $_POST['fecha_nacimiento'],
            'raza'             => $_POST['raza'],
            'genero'           => $_POST['genero'],
            'capa_patron'      => $_POST['capa_patron'],
            'pelo_largo'       => $_POST['pelo_largo'],
            'esterilizado'     => isset($_POST['esterilizado']) ? 'true' : 'false',
            'estado'           => $_POST['estado'],
            'notas_cuidador'   => $_POST['notas_cuidador'],
            'numero_microchip' => !empty($_POST['numero_microchip']) ? $_POST['numero_microchip'] : null,
            'peso_kg'          => $_POST['peso_kg'],
            'tamano'           => $_POST['tamano'],
            'character_tags'   => $pgTags
        ];

        if ($esNuevo) {
            $id_gato = Gato::crear($pdo, $datos);
            // IMPORTANTE: Si crear falla o devuelve false, lanza una excepción para ir al catch
            if (!$id_gato) {
                throw new Exception("No se pudo crear el gato en la base de datos.");
            }
        } else {
            Gato::actualizar($pdo, $id_gato, $datos);
            if (isset($_POST['fotos_eliminar']) && is_array($_POST['fotos_eliminar'])) {
                foreach ($_POST['fotos_eliminar'] as $rutaCompleta) {
                    // Limpiamos la ruta por si acaso
                    $rutaCompleta = trim($rutaCompleta);
                    
                    // 1. Intentar borrar el archivo físico primero
                    $exitoFisico = Imagenes::eliminarFoto($rutaCompleta);
                    
                    // 2. Preparar la ruta para la BD (quitamos el prefijo Imagenes/Gatos/)
                    $rutaBD = str_replace('Imagenes/Gatos/', '', $rutaCompleta);

                    // 3. Borrado de la base de datos (lo ejecutamos siempre para asegurar limpieza)
                    $sql_del = "DELETE FROM fotos_gatos WHERE id_gato = ? AND ruta = ?";
                    $stmt_del = $conn->prepare($sql_del);
                    $stmt_del->bind_param("is", $id_gato, $rutaBD);
                    $stmt_del->execute();
                    
                    // DEBUG: Descomenta la siguiente línea si quieres ver qué está pasando si falla
                    // echo "Intentando borrar: $rutaCompleta | En BD: $rutaBD | Resultado Físico: " . ($exitoFisico ? 'SI' : 'NO') . "<br>";
                }
            }
        }

        // --- GESTIÓN MÉDICA ---
        if (!empty($_POST['nuevo_diagnostico'])) {
            $id_vacuna = !empty($_POST['id_vacuna']) ? $_POST['id_vacuna'] : null;
            HistorialMedico::agregarEntrada($pdo, $id_gato, $_POST['nuevo_diagnostico'], $id_vacuna);
        }

        // --- GESTIÓN DE FOTO PRINCIPAL ---
        // Ajustamos al nombre del input HTML 'fotos[]' y corregimos parámetros
        if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
            $fileArray = [
                'name'     => $_FILES['fotos']['name'][0],
                'type'     => $_FILES['fotos']['type'][0],
                'tmp_name' => $_FILES['fotos']['tmp_name'][0],
                'error'    => $_FILES['fotos']['error'][0],
                'size'     => $_FILES['fotos']['size'][0]
            ];
            
            // Pasamos el array con los datos del gato necesarios para la carpeta
            $gatoRef = ['id_gato' => $id_gato, 'nombre' => $_POST['nombre']];
            $rutaFoto = Imagenes::subirFoto($fileArray, $gatoRef);
            
            if ($rutaFoto) {
                Gato::actualizarFotoUrl($pdo, $id_gato, $rutaFoto);
            }
        }

        $pdo->commit();
        header("Location: ../detalle-gato.php?id=$id_gato");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        // AÑADE ESTO PARA DEBUREAR:
        die("Error al guardar: " . $e->getMessage()); 
    }
}

    // Obtener vacunas disponibles para el desplegable
    $stmtV = $pdo->query("SELECT * FROM Vacunas ORDER BY nombre_vacuna ASC");
    $todasLasVacunas = $stmtV->fetchAll(PDO::FETCH_ASSOC);

    $nombreMostrar = $esNuevo ? "Nuevo Gato" : ($gato['nombre'] ?? 'Gato');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Gato</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include '../navbar/headeradmin.php'; ?>
    <form id="form-gato" action="editar-gato.php<?php echo !$esNuevo ? '?id='.$id_gato : ''; ?>" method="POST" enctype="multipart/form-data" style="display: none;"></form>
    <main class="detalle-container">
        <section class="detalle-header">
            <section class="detalle-img">
                <section class="info-medica">
                    <h3 class="traductor" data-es="Gestión de Fotos" data-ca="Gestió de Fotos">Gestión de Fotos</h3><br>
                    <div class="dato">
                        <b><span class="traductor" data-es="Añadir fotos:" data-ca="Afegir fotos:">Añadir fotos:</span></b>
                        <input type="file" name="fotos[]" form="form-gato" multiple accept="image/*">
                    </div>

                <?php 
                    $fotosGuardadas = $esNuevo ? [] : Imagenes::obtenerFotos($gato);
                    if (!empty($fotosGuardadas)):
                    ?>
                        <div class="seccion-fotos-admin">
                            <label class="traductor" data-es="Imágenes actuales en el servidor:" data-ca="Imatges actuals al servidor:"></label>
                            <p class="traductor" data-es="Marca las casillas de las fotos que desees eliminar permanentemente:" data-ca="Marca les caselles de las fotos que vulguis eliminar permanentment:"></p>
                            
                            <div class="grid-fotos-borrar">
                                <?php foreach ($fotosGuardadas as $foto): 
                                    $srcFinal = is_array($foto) ? $foto['src'] : $foto;
                                    
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
                </section>
            </section>

            <section class="detalle-info">
                <h1>
                    <input type="text" name="nombre" form="form-gato" value="<?php echo htmlspecialchars($gato['nombre'] ?? ''); ?>" required placeholder="Nombre" style="font: inherit; border: none; border-bottom: 2px solid var(--primary); width: 100%; background: transparent;">
                </h1>

                <section class="detalle-datos">
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Microchip:" data-ca="Microxip:">Microchip:</span></i></b>
                        <input type="text" name="numero_microchip" form="form-gato" value="<?php echo htmlspecialchars($gato['numero_microchip'] ?? ''); ?>" class="dato-valor">
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Esterilizado:" data-ca="Esterilitzat:">Esterilizado:</span></i></b>
                        <input type="checkbox" name="esterilizado" form="form-gato" <?php echo (isset($gato['esterilizado']) && ($gato['esterilizado'] == 1 || strtolower($gato['esterilizado']) === 'sí')) ? 'checked' : ''; ?>>
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Raza:" data-ca="Raça:">Raza:</span></i></b>
                        <input type="text" name="raza" form="form-gato" value="<?php echo htmlspecialchars($gato['raza'] ?? ''); ?>" class="dato-valor">
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Género:" data-ca="Gènere:">Género:</span></i></b>
                        <select name="genero" form="form-gato" class="dato-valor">
                            <option value="Macho" <?php echo ($gato['genero'] ?? '') === 'Macho' ? 'selected' : ''; ?>>Macho</option>
                            <option value="Hembra" <?php echo ($gato['genero'] ?? '') === 'Hembra' ? 'selected' : ''; ?>>Hembra</option>
                        </select>
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Nacimiento:" data-ca="Naixement:">Nacimiento:</span></i></b>
                        <input type="date" name="fecha_nacimiento" form="form-gato" value="<?php echo $gato['fecha_nacimiento'] ?? ''; ?>" required class="dato-valor">
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Estado:" data-ca="Estat:">Estado:</span></i></b>
                        <select name="estado" class="dato-valor" form="form-gato">
                            <option value="Disponible" <?php echo (strtolower($gato['estado'] ?? '') === 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                            <option value="Acogida" <?php echo (strtolower($gato['estado'] ?? '') === 'acogida') ? 'selected' : ''; ?>>Acogida</option>
                            <option value="Reservado" <?php echo (strtolower($gato['estado'] ?? '') === 'reservado') ? 'selected' : ''; ?>>Reservado</option>
                            <option value="Adoptado" <?php echo (strtolower($gato['estado'] ?? '') === 'adoptado') ? 'selected' : ''; ?>>Adoptado</option>
                        </select>
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Peso (kg):" data-ca="Pes (kg):">Peso (kg):</span></i></b>
                        <input type="number" placeholder="Ejemplo: 2,567" min="0" step="0.001" lang="es-ES" inputmode="decimal" name="peso_kg" form="form-gato" value="<?php echo htmlspecialchars($gato['peso_kg'] ?? ''); ?>" class="dato-valor">
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Tamaño:" data-ca="Mida:">Tamaño:</span></i></b>
                        <select name="tamano" form="form-gato" class="dato-valor">
                            <option value="Pequeño" <?php echo ($gato['tamano'] ?? '') === 'Pequeño' ? 'selected' : ''; ?>>Pequeño</option>
                            <option value="Mediano" <?php echo ($gato['tamano'] ?? '') === 'Mediano' ? 'selected' : ''; ?>>Mediano</option>
                            <option value="Grande" <?php echo ($gato['tamano'] ?? '') === 'Grande' ? 'selected' : ''; ?>>Grande</option>
                        </select>
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Capa:" data-ca="Capa:">Capa:</span></i></b>
                        <input type="text" name="capa_patron" form="form-gato" value="<?php echo htmlspecialchars($gato['capa_patron'] ?? ''); ?>" class="dato-valor">
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Pelo:" data-ca="Pèl:">Pelo:</span></i></b>
                        <select name="pelo_largo" form="form-gato" class="dato-valor">
                            <option value="Corto" <?php echo ($gato['pelo_largo'] ?? '') === 'Corto' ? 'selected' : ''; ?>>Corto</option>
                            <option value="Semilargo" <?php echo ($gato['pelo_largo'] ?? '') === 'Semilargo' ? 'selected' : ''; ?>>Semilargo</option>
                            <option value="Largo" <?php echo ($gato['pelo_largo'] ?? '') === 'Largo' ? 'selected' : ''; ?>>Largo</option>
                        </select>
                    </div>
                    <div class="dato">
                        <b><i><span class="traductor" data-es="Características:" data-ca="Característiques:">Características:</span></i></b>
                        <input type="text" name="character_tags" form="form-gato" 
                                    value="<?php 
                                    if (!$esNuevo && !empty($gato['character_tags'])) {
                                        // Convertimos el {tag1,tag2} de la BD a "tag1, tag2" para el input
                                        $tagsArray = Gato::parsePgArray($gato['character_tags']);
                                        echo htmlspecialchars(implode(', ', $tagsArray));
                                    }
                            ?>" 
                            class="dato-valor" placeholder="Ej: Cariñoso, Juguetón, Tranquilo">
                    </div>
                    <h3 class="traductor" data-es="Añadir entrada Médica" data-ca="Afegir entrada Mèdica">Añadir entrada Médica</h3>
                    <div class="dato">
                        <label>Diagnóstico / Revisión:</label>
                        <input type="text" name="nuevo_diagnostico" form="form-gato" placeholder="Ej: Revisión anual o Vacunación">
                    </div>
                    <div class="dato">
                        <label>Asociar Vacuna:</label>
                        <select name="id_vacuna" form="form-gato">
                            <option value="">-- Ninguna --</option>
                            <?php foreach ($todasLasVacunas as $v): ?>
                                <option value="<?php echo $v['id_vacuna']; ?>">
                                    <?php echo htmlspecialchars($v['nombre_vacuna']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                </section>

                <h3 class="traductor" data-es="Notas del Cuidador" data-ca="Notes del Cuidador">Notas del Cuidador</h3>
                <textarea name="notas_cuidador" form="form-gato" rows="4" class="dato-valor" style="width: 100%; height: auto;"><?php echo htmlspecialchars($gato['notas_cuidador'] ?? ''); ?></textarea>

                <div class="detalle-actions">
                    <section class="botoneraseparacion">
                        <button type="submit" form="form-gato" class="btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span class="traductor" data-es="Guardar Cambios" data-ca="Desar Canvis">Guardar Cambios</span>
                        </button>
                        <a href="../detalle-gato.php?id=<?php echo $id_gato; ?>" class="btn-secondary">
                            <i class="fa-solid fa-times"></i>
                            <span class="traductor" data-es="Cancelar" data-ca="Cancel·lar">Cancelar</span>
                        </a>
                        <?php if (!$esNuevo): ?>
                        <a href="eliminar-gato.php?id=<?php echo $id_gato; ?>" 
                            class="btn-tertiary" 
                            onclick="return confirm('¿Estás seguro de que quieres eliminar a este gato permanentemente? Esta acción no se puede deshacer.');">
                                <i class="fa-solid fa-trash"></i>
                                <span class="traductor" data-es="Eliminar Gato" data-ca="Eliminar Gat">Eliminar Gato</span>
                        </a>
                        <?php endif; ?>
                    </section>
                </div>

            </section>
        </section>
    </main>

    <?php include '../navbar/footer.php'; ?>
    <script src="../traduccionscript.js"></script>
</body>
</html>