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
    private $numero_microchip;
    private $peso_kg;
    private $tamano;

    public function __construct($id, $nombre, $fecha_nacimiento, $genero, $raza, $capa_patron, $pelo_largo, $character_tags, $esterilizado, $notas_cuidador, $estado, $foto_url, $numero_microchip = null, $peso_kg = null, $tamano = null) {
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
        $this->numero_microchip = $numero_microchip;
        $this->peso_kg = $peso_kg;
        $this->tamano = $tamano;
    }

    public static function crear($pdo, $datos) {
    // 1. Preparamos la consulta SQL incluyendo TODAS las columnas necesarias
    // He añadido 'character_tags' que faltaba en tu INSERT anterior
    $sql = "INSERT INTO Gatos (
                nombre, raza, genero, capa_patron, pelo_largo, 
                esterilizado, estado, notas_cuidador, numero_microchip, 
                peso_kg, tamano, fecha_nacimiento, character_tags, foto_url
            ) 
            VALUES (
                :nombre, :raza, :genero, :capa_patron, :pelo_largo, 
                :esterilizado, :estado, :notas_cuidador, :numero_microchip, 
                :peso_kg, :tamano, :fecha_nacimiento, :character_tags, ''
            ) 
            RETURNING id_gato";

    // 2. Procesamos las etiquetas (tags) para el formato ARRAY de PostgreSQL
    $tagsRaw = $datos['character_tags'] ?? '';
    // Si ya viene formateado como {a,b} lo dejamos, si no, lo convertimos
    if (is_string($tagsRaw) && strpos($tagsRaw, '{') !== 0) {
        $tagsArray = array_filter(array_map('trim', explode(',', $tagsRaw)));
        $pgTags = '{' . implode(',', $tagsArray) . '}';
    } else {
        $pgTags = $tagsRaw ?: '{}';
    }

    $stmt = $pdo->prepare($sql);
    
    // 3. Ejecutamos vinculando cada marcador con su valor
    $stmt->execute([
        ':nombre'           => $datos['nombre'],
        ':raza'             => $datos['raza'],
        ':genero'           => $datos['genero'],
        ':capa_patron'      => $datos['capa_patron'],
        ':pelo_largo'       => $datos['pelo_largo'],
        // PostgreSQL prefiere booleanos reales o strings 'true'/'false'
        ':esterilizado'     => $datos['esterilizado'] ? 'true' : 'false',
        ':estado'           => strtolower($datos['estado']), // Normalizamos a minúsculas como en bd.sql
        ':notas_cuidador'   => $datos['notas_cuidador'],
        ':numero_microchip' => !empty($datos['numero_microchip']) ? $datos['numero_microchip'] : null,
        ':peso_kg'          => $datos['peso_kg'],
        ':tamano'           => $datos['tamano'],
        ':fecha_nacimiento' => $datos['fecha_nacimiento'],
        ':character_tags'   => $pgTags
    ]);

    // Retornamos el ID generado por PostgreSQL
    return $stmt->fetchColumn();
}

    public static function calcularEdadDesdeNacimiento($fecha_nacimiento) {
        if (empty($fecha_nacimiento)) {
            return "Fecha no disponible";
        }

        // Sintaxis W3Schools para manejo de fechas
        $fechaNac = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $intervalo = $hoy->diff($fechaNac);

        // Lógica de visualización inteligente
        if ($intervalo->y < 1) {
            if ($intervalo->m < 1) {
                return $intervalo->d . ($intervalo->d == 1 ? " día" : " días");
            }
            return $intervalo->m . ($intervalo->m == 1 ? " mes" : " meses");
        } else {
            return $intervalo->y . ($intervalo->y == 1 ? " año" : " años");
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
            $data['foto_url'] ?? null,
            $data['numero_microchip'] ?? null,
            $data['peso_kg'] ?? null,
            $data['tamano'] ?? null
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

    public function getNumeroMicrochip() {
        return $this->numero_microchip;
    }

    public function getPesoKg() {
        return $this->peso_kg;
    }

    public function getTamano() {
        return $this->tamano;
    }

    /**
     * Actualiza la información de un gato en la base de datos.
     */
    public static function actualizar($pdo, $id, $datos) {
        $sql = "UPDATE Gatos SET 
                nombre = :nombre,
                raza = :raza,
                genero = :genero,
                capa_patron = :capa_patron,
                pelo_largo = :pelo_largo,
                esterilizado = :esterilizado,
                estado = :estado,
                notas_cuidador = :notas_cuidador,
                numero_microchip = :numero_microchip,
                peso_kg = :peso_kg,
                tamano = :tamano,
                character_tags = :character_tags,
                fecha_nacimiento = :fecha_nacimiento
                WHERE id_gato = :id";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':raza' => $datos['raza'],
            ':genero' => $datos['genero'],
            ':capa_patron' => $datos['capa_patron'],
            ':pelo_largo' => $datos['pelo_largo'],
            ':esterilizado' => $datos['esterilizado'],
            ':estado' => $datos['estado'],
            ':notas_cuidador' => $datos['notas_cuidador'],
            ':numero_microchip' => $datos['numero_microchip'],
            ':peso_kg' => $datos['peso_kg'],
            ':tamano' => $datos['tamano'],
            ':character_tags' => $datos['character_tags'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':id' => $id
        ]);
    }

    /**
     * Actualiza únicamente la URL de la foto principal del gato.
     */
    public static function actualizarFotoUrl($pdo, $id, $fotoUrl) {
        $sql = "UPDATE Gatos SET foto_url = :foto_url WHERE id_gato = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':foto_url' => $fotoUrl,
            ':id' => $id
        ]);
    }

    /**
     * Obtiene la lista de gatos que ya han sido adoptados.
     * Ideal para desplegables de finales felices.
     */
    public static function listarAdoptados($pdo) {
        // Cambiamos "id_gato, nombre" por "*" para que el objeto tenga todos los datos
        $sql = "SELECT * FROM Gatos WHERE estado = 'adoptado' ORDER BY nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>