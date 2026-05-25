<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();

require_once '../Clases/Conexion.php';
require_once '../Clases/Gato.php';
require_once '../Clases/Imagenes.php';

$id_gato = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_gato > 0) {
    $pdo = (new Conexion())->getConnection();

    if ($pdo) {
        try {
            // 1. Localizar los datos del gato antes de borrarlo de la base de datos
            $gato = Gato::obtenerPorId($pdo, $id_gato);

            if ($gato) {
                // 2. BORRAR TODOS LOS ARCHIVOS FÍSICOS DE SU ÁLBUM
                $fotosAsociadas = Imagenes::obtenerFotos($gato);
                if (!empty($fotosAsociadas)) {
                    foreach ($fotosAsociadas as $foto) {
                        $rutaSrc = is_array($foto) ? $foto['src'] : $foto;
                        Imagenes::eliminarFoto($rutaSrc); // Limpieza de ficheros individuales
                    }
                }

                // 3. BORRAR CARPETA FÍSICA Y TODO SU CONTENIDO
                    $nombreCarpeta = $gato['id_gato'] . "_" . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $gato['nombre']));
                    $rutaDirectorio = __DIR__ . '/../Imagenes/Gatos/' . $nombreCarpeta;

                    if (file_exists($rutaDirectorio) && is_dir($rutaDirectorio)) {
                        // Función auxiliar para borrar carpetas no vacías
                        function eliminarDirectorioCompleto($dir) {
                            if (!file_exists($dir)) return true;
                            if (!is_dir($dir)) return unlink($dir);

                            foreach (scandir($dir) as $item) {
                                if ($item == '.' || $item == '..') continue;
                                if (!eliminarDirectorioCompleto($dir . DIRECTORY_SEPARATOR . $item)) return false;
                            }
                            return rmdir($dir);
                        }

                        eliminarDirectorioCompleto($rutaDirectorio);
                    }

                    // 4. ELIMINAR EL REGISTRO DE LA BASE DE DATOS (si no lo has hecho ya arriba)
                    $sqlDelete = "DELETE FROM Gatos WHERE id_gato = :id";
                    $stmt = $pdo->prepare($sqlDelete);
                    $stmt->execute([':id' => $id_gato]);
            }

        } catch (Exception $e) {
            // Manejo silencioso o logs en producción
        }
    }
}

// Redireccionar al catálogo tras la operación
header("Location: ../catalogo.php");
exit;