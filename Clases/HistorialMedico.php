<?php
/**
 * Clase HistorialMedico
 * Representa las revisiones médicas y vacunas asociadas a un gato.
 */
class HistorialMedico {
    private $id_historial;
    private $id_gato;
    private $fecha_revision;
    private $diagnostico;
    private $id_vacuna;

    public function __construct($id_historial, $id_gato, $fecha_revision, $diagnostico, $id_vacuna = null) {
        $this->id_historial = $id_historial;
        $this->id_gato = $id_gato;
        $this->fecha_revision = $fecha_revision;
        $this->diagnostico = $diagnostico;
        $this->id_vacuna = $id_vacuna;
    }

    /**
     * Obtiene todo el historial de un gato específico, incluyendo el nombre de la vacuna.
     * Este método complementa al que ya tienes en Gato.php.
     */
    public static function obtenerPorGato($pdo, $id_gato) {
        $sql = "SELECT h.*, v.nombre_vacuna 
                FROM Historial_Medico h 
                LEFT JOIN Vacunas v ON h.id_vacuna = v.id_vacuna 
                WHERE h.id_gato = :id_gato 
                ORDER BY h.fecha_revision DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_gato' => $id_gato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene la lista de vacunas registradas para un gato.
     */
    public static function obtenerVacunasPorGato($pdo, $id_gato) {
        $sql = "SELECT v.nombre_vacuna, v.fecha_vacuna, h.fecha_revision 
                FROM Historial_Medico h 
                JOIN Vacunas v ON h.id_vacuna = v.id_vacuna 
                WHERE h.id_gato = :id_gato 
                ORDER BY h.fecha_revision DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_gato' => $id_gato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Registra una nueva entrada en el historial (Útil para la Voluntaria/Admin)
     */
    public static function agregarEntrada($pdo, $id_gato, $diagnostico, $id_vacuna = null) {
        $sql = "INSERT INTO Historial_Medico (id_gato, fecha_revision, diagnostico, id_vacuna) 
                VALUES (:idg, CURRENT_DATE, :diag, :vac)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'idg'  => $id_gato,
            'diag' => $diagnostico,
            'vac'  => $id_vacuna
        ]);
    }

    // Getters
    public function getIdHistorial() {
        return $this->id_historial;
        }
        
    public function getFecha() { 
        return $this->fecha_revision; 
        }

    public function getDiagnostico() { 
        return $this->diagnostico; 
        }
}