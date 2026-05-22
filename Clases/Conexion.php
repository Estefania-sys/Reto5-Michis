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

    // Nuevo método para centralizar la consulta de adopciones
    public function obtenerAdopcionesCompletas() {
        $db = $this->getConnection();
        if (!$db) return [];

        $query = "
            SELECT
                u.id_usuario,
                u.nombres,
                u.apellidos,
                u.email,
                u.dni,
                u.fecha_nacimiento AS usuario_fecha_nacimiento,
                u.direccion,
                u.poblacion,
                u.cp,
                u.telefono,
                g.id_gato,
                g.nombre AS gato_nombre,
                g.numero_microchip,
                g.peso_kg,
                g.tamano,
                a.id_adopcion,
                a.fecha_adopcion,
                a.observaciones,
                a.cita1_ok,
                a.cita2_ok
            FROM Adopciones a
            JOIN Usuarios u ON a.id_usuario = u.id_usuario
            JOIN Gatos g ON a.id_gato = g.id_gato
            ORDER BY a.fecha_adopcion DESC
        ";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Puedes manejar el error como prefieras (logs, echo, etc.)
            error_log("Error en la consulta de adopciones: " . $e->getMessage());
            return [];
        }
    }
}
?>