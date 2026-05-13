<?php
require_once 'vendor/autoload.php';

class BlogMichis {
    private $coleccion;

    public function __construct() {
        try {
            // Conexión a MongoDB local
            $cliente = new MongoDB\Client("mongodb://localhost:27017");
            $this->coleccion = $cliente->MichisBlog->posts;
        } catch (Exception $e) {
            die("Error en Mongo: " . $e->getMessage());
        }
    }

    // Método para crear un post cuando un gato es adoptado
    public function crearPost($id_gato, $nombre, $historia, $foto) {
        return $this->coleccion->insertOne([
            'id_gato_sql' => $id_gato,
            'titulo' => "¡Final Feliz para $nombre!",
            'contenido' => $historia,
            'foto' => $foto,
            'fecha' => new MongoDB\BSON\UTCDateTime(),
            'etiquetas' => ['Adoptado', 'Final Feliz']
        ]);
    }

    // Método para obtener todos los posts
    public function listarPosts() {
        return $this->coleccion->find([], ['sort' => ['fecha' => -1]]);
    }
}