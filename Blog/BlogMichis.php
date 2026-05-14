<?php

class BlogMichis {
    private $coleccion;
    private $errorMessage = null;

    private static function registerAutoload(): void {
        $root = dirname(__DIR__);
        $vendorAutoload = $root . '/vendor/autoload.php';
        $composerAutoloadReal = $root . '/vendor/composer/autoload_real.php';

        if (file_exists($vendorAutoload) && file_exists($composerAutoloadReal)) {
            require_once $vendorAutoload;
            return;
        }

        spl_autoload_register(function ($class) use ($root) {
            $prefixes = [
                'MongoDB\\' => $root . '/vendor/mongodb/mongodb/src/',
                'Psr\\Log\\' => $root . '/vendor/psr/log/src/',
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                if (strpos($class, $prefix) === 0) {
                    $relativeClass = substr($class, strlen($prefix));
                    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                    if (file_exists($file)) {
                        require_once $file;
                    }
                    return;
                }
            }
        });
    }

    public function __construct() {
        self::registerAutoload();

        if (!class_exists('MongoDB\\Client')) {
            $this->errorMessage = 'La extensión PHP mongodb no está disponible en este servidor.';
            error_log($this->errorMessage);
            return;
        }

        try {
            $cliente = new MongoDB\Client('mongodb://localhost:27017');
            $this->coleccion = $cliente->MichisBlog->posts;
        } catch (\Throwable $e) {
            $this->errorMessage = 'No se pudo conectar a MongoDB: ' . $e->getMessage();
            error_log($this->errorMessage);
            $this->coleccion = null;
        }
    }

    public function crearPost($id_gato, $titulo, $historia, $foto) {
        if (!$this->coleccion) {
            return null;
        }

        return $this->coleccion->insertOne([
            'id_gato_sql' => (int)$id_gato,
            'titulo' => $titulo,
            'contenido' => $historia,
            'foto' => $foto,
            'fecha' => new MongoDB\BSON\UTCDateTime(),
            'etiquetas' => ['Final Feliz', 'Adoptado']
        ]);
    }

    public function obtenerTodos() {
        if (!$this->coleccion) {
            return [];
        }

        return $this->coleccion->find([], ['sort' => ['fecha' => -1]]);
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function isAvailable() {
        return $this->coleccion !== null;
    }
}
?>