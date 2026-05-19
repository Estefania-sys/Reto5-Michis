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
        $sql = "SELECT * FROM Usuarios WHERE email = :email AND password = :pass AND rol != 'adoptante'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'pass' => $pass]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return new Usuario(
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

    public static function obtenerNombreAdmin() {
        return $_SESSION['admin'] ?? '';
    }

    public static function cerrarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Renderiza el header correcto según la sesión activa
     * @param string $basePath Ruta base del proyecto (ej: '/Reto5-Michis')
     * @param bool $esAdmin Si es true, renderiza header admin; si es false, renderiza header público
     */
    public static function renderizarHeader($basePath = '/Reto5-Michis', $esAdmin = null) {
        if ($esAdmin === null) {
            $esAdmin = self::tieneAdminActivo();
        }

        ?>
        <header class="navbar">
            <section class="logo">
                <a href="<?php echo $basePath; ?>/<?php echo $esAdmin ? 'Admin/admin-index.php' : 'index.php'; ?>">
                    <img class="logoimg" src="<?php echo $basePath; ?>/Imagenes/Items/logoplaceholder.png" height="55" width="75" alt="Logo">
                </a>
            </section>
            <nav>
                <ul>
                    <a href="<?php echo $basePath; ?>/index.php">Inicio</a>
                    <a href="<?php echo $basePath; ?>/catalogo.php">Adoptar</a>
                    <a href="<?php echo $basePath; ?>/Blog/finales.php">Blog</a>
                    <a href="<?php echo $basePath; ?>/contacto.php">Contacto</a>
                    <?php if ($esAdmin): ?>
                        <span>Bienvenid@, <?php echo htmlspecialchars(self::obtenerNombreAdmin()); ?></span>
                        <a class="logoutbtn" href="<?php echo $basePath; ?>/logout.php">Cerrar Sesión</a>
                    <?php else: ?>
                        <a href="<?php echo $basePath; ?>/login.php" class="btn-login">Admin Login</a>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>
        <?php
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
}
?>
