<?php
require_once 'Persona.php';

class Usuario extends Persona {
    private $rol;

    public function __construct($id, $nombres, $apellidos, $email, $rol) {
        parent::__construct($id, $nombres, $apellidos, $email);
        $this->rol = $rol;
    }

    public static function login($pdo, $email, $pass) {
        $sql = "SELECT * FROM Usuarios WHERE email = :email AND password = :pass AND rol != 'adoptante'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'pass' => $pass]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return new Usuario($res['id_usuario'], $res['nombres'], $res['apellidos'], $res['email'], $res['rol']);
        }
        return null;
    }
}
?>