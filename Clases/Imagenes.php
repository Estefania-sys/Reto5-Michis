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

        if (!empty($gato['nombre'])) {
            $candidate = self::normalizeFolderName($gato['nombre']);
            foreach ($folders as $folder) {
                if (strcasecmp($folder, $candidate) === 0) {
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
        $folderName = self::findFolderName($gato);
        if (!empty($folderName)) {
            return $folderName;
        }

        return !empty($gato['nombre']) ? $gato['nombre'] : null;
    }

    public static function obtenerFotos($gato) {
        $imagenes = [];
        $nombreCarpeta = self::findFolderName($gato);
        if (!empty($nombreCarpeta)) {
            $imagenes = self::scanImagesInFolder($nombreCarpeta);
        }

        if (empty($imagenes)) {
            $imagenes[] = self::obtenerFotoFallback($gato, $nombreCarpeta);
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

    private static function obtenerFotoFallback($gato, $nombreCarpeta) {
        if (!empty($gato['foto_url'])) {
            $rutaFoto = __DIR__ . '/../' . $gato['foto_url'];
            if (file_exists($rutaFoto)) {
                return $gato['foto_url'];
            }
        }

        if (!empty($nombreCarpeta)) {
            return 'Imagenes/Gatos/' . $nombreCarpeta . '.png';
        }

        return 'Imagenes/Gatos/default.png';
    }
}
?>