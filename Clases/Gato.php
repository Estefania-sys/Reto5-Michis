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

    /**
     * Lista todos los gatos que no han sido adoptados.
     */
    public static function listarNoAdoptados($pdo) {
        $sql = "SELECT * FROM Gatos WHERE estado != 'adoptado' ORDER BY nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los datos de un gato específico por su ID.
     */
    public static function obtenerPorId($pdo, $id) {
        $sql = "SELECT * FROM Gatos WHERE id_gato = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el historial médico delegando la lógica a la clase HistorialMedico.
     * (Se eliminó la duplicidad que causaba el error)
     */
    public static function obtenerHistorial($pdo, $id) {
        // Asegúrate de que este archivo y la clase HistorialMedico existan
        require_once 'HistorialMedico.php'; 
        return HistorialMedico::obtenerPorGato($pdo, $id);
    }

    // Getters
    public function getId() { 
        return $this->id; 
        }
        
    public function getNombre() { 
        return $this->nombre; 
        }

    public function getFotoUrl() { 
        return $this->foto_url; 
        }

    public function getEstado() { 
        return $this->estado; 
        }
}
?>