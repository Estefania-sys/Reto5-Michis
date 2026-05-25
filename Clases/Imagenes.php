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
        $s = preg_replace('/[\s\/\\]+/', '_', $s);
        $s = preg_replace('/[^a-z0-9_\-]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $s));
        $s = preg_replace('/_+/', '_', $s);
        $s = trim($s, '_-');
        return $s ?: null;
    }

    public static function getImageOrientation($imagenRelPath) {
        // Todas las imágenes se tratan como verticales de igual tamaño.
        return 'vertical';
    }

    private static function getTargetImageSize() {
        return ['width' => 899, 'height' => 1599];
    }

    private static function getCacheFolder() {
        return self::getBaseFolder() . 'cache/';
    }

    private static function getCachedImagePath($imagenRelPath) {
        $relative = preg_replace('#^Imagenes/Gatos/#', '', $imagenRelPath);
        return 'Imagenes/Gatos/cache/' . $relative;
    }

    private static function ensureDirectoryExists($path) {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    private static function resizeImageToUniformSize($sourcePath, $destPath) {
        if (!function_exists('imagecreatetruecolor') || !function_exists('imagecopyresampled')) {
            return false;
        }

        $size = @getimagesize($sourcePath);
        if ($size === false) {
            return false;
        }

        list($origWidth, $origHeight, $imageType) = $size;
        $target = self::getTargetImageSize();
        $targetWidth = $target['width'];
        $targetHeight = $target['height'];

        if ($origWidth <= 0 || $origHeight <= 0) {
            return false;
        }

        $srcRatio = $origWidth / $origHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($srcRatio > $targetRatio) {
            $newWidth = $targetWidth;
            $newHeight = (int)round($targetWidth / $srcRatio);
        } else {
            $newHeight = $targetHeight;
            $newWidth = (int)round($targetHeight * $srcRatio);
        }

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                if (!function_exists('imagecreatefromjpeg')) {
                    return false;
                }
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                if (!function_exists('imagecreatefrompng')) {
                    return false;
                }
                $image = @imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                if (!function_exists('imagecreatefromgif')) {
                    return false;
                }
                $image = @imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
            imagecolortransparent($canvas, $white);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
        }

        $dstX = (int)round(($targetWidth - $newWidth) / 2);
        $dstY = (int)round(($targetHeight - $newHeight) / 2);
        imagecopyresampled($canvas, $image, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        self::ensureDirectoryExists(dirname($destPath));

        $success = false;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($canvas, $destPath, 85);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($canvas, $destPath, 6);
                break;
            case IMAGETYPE_GIF:
                $success = imagegif($canvas, $destPath);
                break;
        }

        imagedestroy($image);
        imagedestroy($canvas);

        return $success;
    }

    private static function obtenerImagenHomogenea($imagen) {
        if (empty($imagen)) {
            return $imagen;
        }

        $rutaAbs = __DIR__ . '/../' . $imagen;
        if (!file_exists($rutaAbs) || !is_file($rutaAbs)) {
            return $imagen;
        }

        $cachedRel = self::getCachedImagePath($imagen);
        $cachedAbs = __DIR__ . '/../' . $cachedRel;

        if (file_exists($cachedAbs) && filemtime($cachedAbs) >= filemtime($rutaAbs)) {
            return $cachedRel;
        }

        if (self::resizeImageToUniformSize($rutaAbs, $cachedAbs)) {
            return $cachedRel;
        }

        return $imagen;
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
                $rutaRel = 'Imagenes/Gatos/' . $nombreCarpeta . '/' . $archivo;
                $imagenes[] = self::obtenerImagenHomogenea($rutaRel);
            }
        }

        return $imagenes;
    }

    private static function obtenerFotoFallback($gato, $nombreCarpeta) {
        if (!empty($gato['foto_url'])) {
            $rutaFoto = __DIR__ . '/../' . $gato['foto_url'];
            if (file_exists($rutaFoto)) {
                return self::obtenerImagenHomogenea($gato['foto_url']);
            }
        }

        if (!empty($nombreCarpeta)) {
            $candidate1 = 'Imagenes/Gatos/' . $nombreCarpeta . '.png';
            $candidate2 = 'Imagenes/Gatos/' . $nombreCarpeta . '/default.png';
            if (file_exists(__DIR__ . '/../' . $candidate1)) {
                return self::obtenerImagenHomogenea($candidate1);
            }
            if (file_exists(__DIR__ . '/../' . $candidate2)) {
                return self::obtenerImagenHomogenea($candidate2);
            }
            return self::obtenerImagenHomogenea($candidate1);
        }

        return self::obtenerImagenHomogenea('Imagenes/Gatos/default.png');
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
        if (empty($rutaRelativa) || strpos($rutaRelativa, '..') !== false) {
            return false;
        }

        // Si la ruta recibida es de la caché, calculamos la original primero
        if (strpos($rutaRelativa, 'Imagenes/Gatos/cache/') === 0) {
            $rutaOriginal = str_replace('Imagenes/Gatos/cache/', 'Imagenes/Gatos/', $rutaRelativa);
            $rutaCache = $rutaRelativa;
        } else {
            $rutaOriginal = $rutaRelativa;
            // Generamos la ruta equivalente de la caché usando tu propio método privado
            $rutaCache = self::getCachedImagePath($rutaRelativa);
        }

        $absOriginal = __DIR__ . '/../' . $rutaOriginal;
        $absCache = __DIR__ . '/../' . $rutaCache;

        // Borrar imagen original
        if (file_exists($absOriginal) && is_file($absOriginal)) {
            unlink($absOriginal);
        }

        // Borrar miniatura optimizada en caché
        if (file_exists($absCache) && is_file($absCache)) {
            unlink($absCache);
        }

        return true;
    }
}
?>