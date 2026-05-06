<?php
// TicketAdopcion.php (Evolución de Adopcion.php)
class TicketAdopcion {
    
    /**
     * Registra el interés inicial. 
     * Por defecto, las citas se inicializan en FALSE (0 en BD).
     */
    public static function registrarInteres($pdo, $id_gato, $nombres, $apellidos, $email, $mensaje) {
        try{// 1. Manejo del usuario adoptante (PostgreSQL ON CONFLICT)
        $sqlUser = "INSERT INTO Usuarios (nombres, apellidos, email, rol) 
                    VALUES (:nom, :ape, :em, 'adoptante') 
                    ON CONFLICT (email) DO UPDATE SET nombres = EXCLUDED.nombres 
                    RETURNING id_usuario";
        
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute(['nom' => $nombres, 'ape' => $apellidos, 'em' => $email]);
        $id_usuario = $stmtUser->fetchColumn();

        // 2. Insertamos el Ticket con los nuevos campos de seguimiento
        // Nota: Asegúrate de haber ejecutado el ALTER TABLE en tu BD para estos campos
        $sqlAdop = "INSERT INTO Adopciones (id_usuario, id_gato, observaciones, cita1_ok, cita2_ok) 
                    VALUES (:iu, :ig, :obs, CURRENT_DATE, false, false)";
        
        $stmtAdop = $pdo->prepare($sqlAdop);
        return $stmtAdop->execute([
            'iu' => $id_usuario, 
            'ig' => $id_gato, 
            'obs' => $mensaje
        ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Lista todos los tickets con información extendida para el Dashboard
     */
    public static function listarTodas($pdo) {
        $sql = "SELECT a.*, g.nombre as gato_nombre, g.estado as gato_estado, 
                       u.nombres as user_nombre, u.apellidos as user_apellido, u.email as user_email
                FROM Adopciones a 
                JOIN Gatos g ON a.id_gato = g.id_gato 
                JOIN Usuarios u ON a.id_usuario = u.id_usuario
                ORDER BY a.fecha_adopcion DESC";
        
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Método nuevo para que la Voluntaria/Admin valide las fases del ticket
     */
    public static function actualizarSeguimiento($pdo, $id_adopcion, $fase, $resultado) {
    // Determina qué columna actualizar según la fase (1 o 2)
    $columna = ($fase == 1) ? 'cita1_ok' : 'cita2_ok';
    
    // El resultado se maneja como booleano para la lógica de PostgreSQL
    $sql = "UPDATE Adopciones SET $columna = :res WHERE id_adopcion = :id";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([
        'res' => $resultado ? 'true' : 'false',
        'id'  => $id_adopcion
    ]);
    }

    // Nuevo método sugerido en Gato.php para cerrar el proceso
    public static function marcarComoAdoptado($pdo, $id_gato) {
        $sql = "UPDATE Gatos SET estado = 'adoptado' WHERE id_gato = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['id' => $id_gato]);
    }

    /**
     * Obtiene un ticket específico por su ID
     */
    public static function obtenerPorId($pdo, $id) {
        $sql = "SELECT * FROM Adopciones WHERE id_adopcion = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>