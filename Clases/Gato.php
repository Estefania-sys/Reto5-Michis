<?php
class Gato {
    // Atributos privados
    private $id;
    private $nombre;
    private $fecha_nacimiento;
    private $edad;
    private $genero;
    private $raza;
    private $esterilizado;
    private $descripcion;
    private $estado;
    private $foto_url;

    public function __construct($id, $nombre, $fecha_nacimiento, $edad, $genero, $raza, $esterilizado, $descripcion, $estado, $foto_url) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->edad = $edad;
        $this->genero = $genero;
        $this->raza = $raza;
        $this->esterilizado = $esterilizado;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->foto_url = $foto_url;
    }

    // Métodos SQL dentro de la clase
    public static function listarNoAdoptados($pdo) {
        $sql = "SELECT * FROM Gatos WHERE estado != 'adoptado' ORDER BY nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornamos array
    }

    public static function obtenerPorId($pdo, $id) {
        $sql = "SELECT * FROM Gatos WHERE id_gato = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerHistorial($pdo, $id) {
        $sql = "SELECT h.*, v.nombre_vacuna FROM Historial_Medico h 
                LEFT JOIN Vacunas v ON h.id_vacuna = v.id_vacuna 
                WHERE h.id_gato = :id ORDER BY h.fecha_revision DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getters por si los necesitas después
    public function getNombre() { return $this->nombre; }
    public function getFotoUrl() { return $this->foto_url; }
}
?>