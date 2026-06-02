<?php
require_once 'Persona.php';
class Voluntaria extends Persona {
    private $rol;

    public function __construct($id, $nombres, $apellidos, $email, $rol, $dni = null, $fecha_nacimiento = null, $direccion = null, $poblacion = null, $cp = null, $telefono = null) {
    // Usamos el constructor de la clase madre Persona
    parent::__construct($id, $nombres, $apellidos, $email, $dni, $fecha_nacimiento, $direccion, $poblacion, $cp, $telefono);
    $this->rol = $rol;
  }

    public function getRol() { return $this->rol; }

    public static function login($pdo, $email, $pass) {
        $sql = "SELECT * FROM Usuarios WHERE email = :email AND password = :pass AND rol = 'voluntario'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'pass' => $pass]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            // Se establece sesión de voluntaria, NO de admin para restringir paneladmin.php
            $_SESSION['voluntaria'] = $res['nombres'] . ' ' . $res['apellidos'];
            return new Voluntaria(
                $res['id_usuario'],
                $res['nombres'],
                $res['apellidos'],
                $res['email'],
                $res['rol'],
                $res['dni'] ?? null,
                $res['fecha_nacimiento'] ?? null,
                $res['direccion'] ?? null,
                $res['poblacion'] ?? null,
                $res['cp'] ?? null,
                $res['telefono'] ?? null
            );
        }
        return null;
    }
}
?>