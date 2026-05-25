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

                // 3. INTENTAR BORRAR LA CARPETA EN DISCO (Si se queda vacía tras vaciar las fotos)
                // Usamos el mismo algoritmo de sanitización que tiene tu clase original Imagenes.php
                $sName = trim(mb_strtolower($gato['nombre'], 'UTF-8'));
                $sName = preg_replace('/[\s\/\\\\]+/', '_', $sName);
                $sName = preg_replace('/[^a-z0-9_\-]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $sName));
                $sName = preg_replace('/_+/', '_', $sName);
                $nombreCarpeta = trim($sName, '_-');

                if (!empty($nombreCarpeta)) {
                    $rutaDirectorio = __DIR__ . '/../Imagenes/Gatos/' . $nombreCarpeta;
                    if (file_exists($rutaDirectorio) && is_dir($rutaDirectorio)) {
                        // Limpieza de subcarpeta opcional de caché
                        if (file_exists($rutaDirectorio . '/cache') && is_dir($rutaDirectorio . '/cache')) {
                            @rmdir($rutaDirectorio . '/cache');
                        }
                        @rmdir($rutaDirectorio); // Elimina el directorio raíz del gato si ya no tiene archivos
                    }
                }

                // 4. ELIMINAR EL REGISTRO DE LA BASE DE DATOS
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