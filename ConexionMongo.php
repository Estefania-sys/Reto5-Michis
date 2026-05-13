<?php
require 'vendor/autoload.php'; // Carga la librería de MongoDB

class ConexionMongo {
    private $host = "localhost";
    private $port = "27017";
    private $dbName = "MichisBlog";
    private $client;

    public function getConnection() {
        try {
            // La cadena de conexión estándar
            $this->client = new MongoDB\Client("mongodb://{$this->host}:{$this->port}");
            return $this->client->selectDatabase($this->dbName);
        } catch (Exception $e) {
            echo "Error conectando a MongoDB: " . $e->getMessage();
            return null;
        }
    }
}
?>