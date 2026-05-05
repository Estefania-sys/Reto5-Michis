<?php
class Adopcion {
    public static function registrarInteres($pdo, $id_gato, $nombres, $apellidos, $email, $mensaje) {
        // Primero, si no existe el usuario 'adoptante', lo creamos
        $sqlUser = "INSERT INTO Usuarios (nombres, apellidos, email, rol) 
                    VALUES (:nom, :ape, :em, 'adoptante') 
                    ON CONFLICT (email) DO UPDATE SET nombres = EXCLUDED.nombres 
                    RETURNING id_usuario";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute(['nom' => $nombres, 'ape' => $apellidos, 'em' => $email]);
        $id_usuario = $stmtUser->fetchColumn();

        // Insertamos la adopción
        $sqlAdop = "INSERT INTO Adopciones (id_usuario, id_gato, observaciones) VALUES (:iu, :ig, :obs)";
        $stmtAdop = $pdo->prepare($sqlAdop);
        return $stmtAdop->execute(['iu' => $id_usuario, 'ig' => $id_gato, 'obs' => $mensaje]);
    }

    public static function listarTodas($pdo) {
        $sql = "SELECT a.*, g.nombre as gato_nombre, u.nombres as user_nombre 
                FROM Adopciones a 
                JOIN Gatos g ON a.id_gato = g.id_gato 
                JOIN Usuarios u ON a.id_usuario = u.id_usuario";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>