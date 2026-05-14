<?php
class Gato {
    // Atributos privados
    private $id;
    private $nombre;
    private $fecha_nacimiento;
    private $genero;
    private $raza;
    private $capa_patron;
    private $pelo_largo;
    private $character_tags;
    private $esterilizado;
    private $notas_cuidador;
    private $estado;
    private $foto_url;

    public function __construct($id, $nombre, $fecha_nacimiento, $genero, $raza, $capa_patron, $pelo_largo, $character_tags, $esterilizado, $notas_cuidador, $estado, $foto_url) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->genero = $genero;
        $this->raza = $raza;
        $this->capa_patron = $capa_patron;
        $this->pelo_largo = $pelo_largo;
        $this->character_tags = $character_tags;
        $this->esterilizado = $esterilizado;
        $this->notas_cuidador = $notas_cuidador;
        $this->estado = $estado;
        $this->foto_url = $foto_url;
    }

    public static function calcularEdadDesdeNacimiento($fecha_nacimiento) {
        if (empty($fecha_nacimiento)) {
            return null;
        }

        try {
            $nacimiento = new DateTime($fecha_nacimiento);
            $hoy = new DateTime();
            return $hoy->diff($nacimiento)->y;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function parsePgArray($arrayString) {
        if (empty($arrayString)) {
            return [];
        }

        $trimmed = trim($arrayString, '{}');
        if ($trimmed === '') {
            return [];
        }

        return array_map(function ($item) {
            return trim($item, " \"");
        }, explode(',', $trimmed));
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
     */
    public static function obtenerHistorial($pdo, $id) {
        require_once __DIR__ . '/HistorialMedico.php'; 
        return HistorialMedico::obtenerPorGato($pdo, $id);
    }

    /**
     * Obtiene la lista de vacunas registradas para el gato.
     */
    public static function obtenerVacunas($pdo, $id) {
        require_once __DIR__ . '/HistorialMedico.php';
        return HistorialMedico::obtenerVacunasPorGato($pdo, $id);
    }

    public static function tieneVacunas($pdo, $id) {
        $vacunas = self::obtenerVacunas($pdo, $id);
        return !empty($vacunas);
    }

    public static function fromArray($data) {
        return new self(
            $data['id_gato'] ?? null,
            $data['nombre'] ?? null,
            $data['fecha_nacimiento'] ?? null,
            $data['genero'] ?? null,
            $data['raza'] ?? null,
            $data['capa_patron'] ?? null,
            $data['pelo_largo'] ?? null,
            $data['character_tags'] ?? null,
            $data['esterilizado'] ?? null,
            $data['notas_cuidador'] ?? null,
            $data['estado'] ?? null,
            $data['foto_url'] ?? null
        );
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFechaNacimiento() {
        return $this->fecha_nacimiento;
    }

    public function getEdad() {
        return self::calcularEdadDesdeNacimiento($this->fecha_nacimiento);
    }

    public function getGenero() {
        return $this->genero;
    }

    public function getRaza() {
        return $this->raza;
    }

    public function getCapaPatron() {
        return $this->capa_patron;
    }

    public function getPeloLargo() {
        return $this->pelo_largo;
    }

    public function getCharacterTags() {
        return self::parsePgArray($this->character_tags);
    }

    public function isEsterilizado() {
        return (bool)$this->esterilizado;
    }

    public function getNotasCuidador() {
        return $this->notas_cuidador;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFotoUrl() {
        return $this->foto_url;
    }
}
?>