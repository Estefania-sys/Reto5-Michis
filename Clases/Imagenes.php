<?php
class Imagenes {
    private static function getBaseFolder() {
        return __DIR__ . '/../Imagenes/Gatos/';
    }

    private static function normalizeFolderName($nombre) {
        if (empty($nombre)) {
            return null;
        }

        return basename(trim($nombre));
    }

    private static function sanitizeForFolder($nombre) {
        if (empty($nombre)) {
            return null;
        }
        $s = trim(mb_strtolower($nombre, 'UTF-8'));
        $s = preg_replace('/[\s\/\\\]+/', '_', $s);
        $s = preg_replace('/[^a-z0-9_\-]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $s));
        $s = preg_replace('/_+/', '_', $s);
        $s = trim($s, '_-');
        return $s ?: null;
    }

    private static function scanFolderNames() {
        $base = self::getBaseFolder();
        if (!is_dir($base)) {
            return [];
        }

        $folders = [];
        foreach (scandir($base) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir($base . $item)) {
                $folders[] = $item;
            }
        }

        return $folders;
    }

    private static function findFolderName($gato) {
        $folders = self::scanFolderNames();
        if (empty($folders)) {
            return null;
        }

        // Prioritize folders that begin with the numeric id (new scheme: "id_slug")
        if (!empty($gato['id_gato'])) {
            $idStr = (string)$gato['id_gato'];
            foreach ($folders as $folder) {
                if (stripos($folder, $idStr . '_') === 0 || strcasecmp($folder, $idStr) === 0) {
                    return $folder;
                }
            }
        }

        // Fall back to matching by normalized name (compatibility with old folders)
        if (!empty($gato['nombre'])) {
            $candidate = self::normalizeFolderName($gato['nombre']);
            foreach ($folders as $folder) {
                if (strcasecmp($folder, $candidate) === 0) {
                    return $folder;
                }
                // also try sanitized slug match
                $slug = self::sanitizeForFolder($gato['nombre']);
                if (!empty($slug) && strcasecmp($folder, $slug) === 0) {
                    return $folder;
                }
            }
        }

        if (!empty($gato['foto_url'])) {
            $parts = explode('/', str_replace('\\', '/', $gato['foto_url']));
            $index = array_search('Gatos', $parts);
            if ($index !== false && isset($parts[$index + 1])) {
                $folder = $parts[$index + 1];
                foreach ($folders as $existing) {
                    if (strcasecmp($existing, $folder) === 0) {
                        return $existing;
                    }
                }
            }
        }

        return null;
    }

    public static function obtenerNombre($gato) {
        // Prefer the explicit name stored for the cat for visual display
        if (!empty($gato['nombre'])) {
            return $gato['nombre'];
        }

        $folderName = self::findFolderName($gato);
        return !empty($folderName) ? $folderName : null;
    }

    public static function obtenerFotos($gato) {
        $imagenes = [];
        $nombreCarpeta = self::findFolderName($gato);
        if (!empty($nombreCarpeta)) {
            $imagenes = self::scanImagesInFolder($nombreCarpeta);
        }

        if (empty($imagenes)) {
            // Devolvemos un marcador especial en lugar de una ruta de imagen
            return ['SIN_FOTO'];
        }

        return array_values(array_unique($imagenes));
    }

    private static function scanImagesInFolder($nombreCarpeta) {
        $imagenes = [];
        $rutaCarpeta = self::getBaseFolder() . $nombreCarpeta;
        if (!is_dir($rutaCarpeta)) {
            return $imagenes;
        }

        foreach (scandir($rutaCarpeta) as $archivo) {
            if ($archivo === '.' || $archivo === '..') {
                continue;
            }
            $rutaArchivo = $rutaCarpeta . '/' . $archivo;
            if (!is_file($rutaArchivo)) {
                continue;
            }
            if (preg_match('/\.(jpe?g|png|gif)$/i', $archivo)) {
                $imagenes[] = 'Imagenes/Gatos/' . $nombreCarpeta . '/' . $archivo;
            }
        }

        return $imagenes;
    }

    // =========================================================================
    // NUEVOS MÉTODOS PARA SUBIR Y ELIMINAR FOTOS (POO)
    // =========================================================================

    /**
     * Sube un archivo físico a la carpeta sanitizada del gato.
     * @param array $file Bloque individual de $_FILES (ej: $_FILES['nueva_foto'])
     * @param string $nombreGato Nombre del gato para generar la carpeta
     * @return string|bool Devuelve la ruta relativa que se guardará (ej: 'Imagenes/Gatos/michi/foto.png') o false.
     */
    // =========================================================================
    // MÉTODOS PARA SUBIR Y ELIMINAR FOTOS (OPTIMIZADOS PARA TU CACHÉ)
    // =========================================================================

    /**
     * Sube un archivo físico a la carpeta sanitizada del gato respetando el ID.
     */
    public static function subirFoto($file, $gato) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK || empty($gato)) {
            return false;
        }

        // Buscamos si ya existe una carpeta asignada o generamos la nueva con el esquema id_nombre
        $folderName = self::findFolderName($gato);
        
        if (!$folderName) {
            // Si no tiene carpeta, la creamos usando "id_nombre" como en tu base de datos
            $slug = self::sanitizeForFolder($gato['nombre']);
            $folderName = $gato['id_gato'] . '_' . $slug;
        }

        $targetFolder = self::getBaseFolder() . $folderName;

        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0755, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowed)) {
            return false;
        }

        $fileName = uniqid('img_', true) . '.' . $extension;
        $absolutePath = $targetFolder . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $absolutePath)) {
            return 'Imagenes/Gatos/' . $folderName . '/' . $fileName;
        }

        return false;
    }
    
    /**
     * Elimina una foto original y su correspondiente versión en caché.
     */
    public static function eliminarFoto($rutaRelativa) {
        if (empty($rutaRelativa)) return false;

        // Localizamos la raíz del proyecto (un nivel arriba de donde está Imagenes.php)
        $root = realpath(dirname(__FILE__) . '/../');
        $rutaFinal = $root . '/' . ltrim($rutaRelativa, '/');

        if (file_exists($rutaFinal) && is_file($rutaFinal)) {
            if (@unlink($rutaFinal)) {
                // Si hay cache, intentar borrarla también
                $rutaCache = str_replace('Imagenes/Gatos/', 'Imagenes/Gatos/cache/', $rutaFinal);
                if (file_exists($rutaCache)) @unlink($rutaCache);
                return true;
            }
        }
        return false;
    }
}