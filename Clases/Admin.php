<?php
require_once 'Persona.php';

class Admin extends Persona {
    private $rol;

    public function __construct($id, $nombres, $apellidos, $email, $rol, $dni = null, $fecha_nacimiento = null, $direccion = null, $poblacion = null, $cp = null, $telefono = null) {
        parent::__construct($id, $nombres, $apellidos, $email, $dni, $fecha_nacimiento, $direccion, $poblacion, $cp, $telefono);
        $this->rol = $rol;
    }

    public function getRol() {
        return $this->rol;
    }

    public static function login($pdo, $email, $pass) {
        $sql = "SELECT * FROM Usuarios WHERE email = :email AND password = :pass AND rol = 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'pass' => $pass]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            $_SESSION['admin'] = $res['nombres'] . ' ' . $res['apellidos'];
            return new Admin(
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
    
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function tieneAdminActivo() {
        return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
    }

     /**
     * Verifica si el usuario es admin, si no redirige a login
     * @param string $redirectPath Ruta a la que redirigir si no es admin
     */
    public static function requerirAdmin($redirectPath = '/Reto5-Michis/login.php') {
        if (!self::tieneAdminActivo()) {
            header("Location: $redirectPath");
            exit;
        }
    }

    public static function obtenerNombreAdmin() {
        return $_SESSION['admin'] ?? '';
    }

    public static function cerrarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['admin']);
        unset($_SESSION['voluntaria']);
        $_SESSION = [];
        session_destroy();
    }

}
?>
