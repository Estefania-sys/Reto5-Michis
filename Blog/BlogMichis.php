<?php
require_once __DIR__ . '/../vendor/autoload.php';

class BlogMichis {
    private $coleccion;

    public function __construct() {
        try {
            // Conexión a MongoDB local (Compass)
            $cliente = new MongoDB\Client("mongodb://localhost:27017");
            // Seleccionamos base de datos "MichisBlog" y colección "posts"
            $this->coleccion = $cliente->MichisBlog->posts;
        } catch (Exception $e) {
            // Si no hay conexión a Mongo, fallará silenciosamente o mostrará error
            error_log("Error en MongoDB: " . $e->getMessage());
        }
    }

    public function crearPost($id_gato, $titulo, $historia, $foto) {
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
        if (!$this->coleccion) return [];
        // Traemos todos los posts ordenados por fecha descendente
        return $this->coleccion->find([], ['sort' => ['fecha' => -1]]);
    }
}
?>