<?php
class Conexion {
    private $host = "192.168.4.18";
    private $port = "5432";
    private $dbname = "Michis";
    private $username = "postgres";
    private $password = "P@ssw0rd";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $this->conn;
    }

    
}
?>