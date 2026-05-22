<?php
class Voluntaria {
    private $id;
    private $nombres;
    private $apellidos;
    private $email;
    private $dni;
    private $fecha_nacimiento;
    private $direccion;
    private $poblacion;
    private $cp;
    private $telefono;
    private $rol;

    public function __construct($id, $nombres, $apellidos, $email, $rol, $dni = null, $fecha_nacimiento = null, $direccion = null, $poblacion = null, $cp = null, $telefono = null) {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->dni = $dni;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->direccion = $direccion;
        $this->poblacion = $poblacion;
        $this->cp = $cp;
        $this->telefono = $telefono;
        $this->rol = $rol;
    }

    public function getId() { return $this->id; }
    public function getNombreCompleto() { return $this->nombres . " " . $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getDni() { return $this->dni; }
    public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getDireccion() { return $this->direccion; }
    public function getPoblacion() { return $this->poblacion; }
    public function getCp() { return $this->cp; }
    public function getTelefono() { return $this->telefono; }
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