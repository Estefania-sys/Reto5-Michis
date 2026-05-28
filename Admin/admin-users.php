<?php
// ── Arrancar sesión y comprobar que el usuario es admin ──
require_once '../Clases/Admin.php';
require_once '../Clases/Conexion.php';

Admin::iniciar();
Admin::requerirAdmin();

// ── Conectar a la base de datos ──
$db = (new Conexion())->getConnection();

$mensaje = '';
$error   = '';

// ── ELIMINAR usuario ──
if (isset($_POST['action']) && $_POST['action'] == 'delete') {

    $id = (int)$_POST['id_usuario'];
    $db->prepare("DELETE FROM Usuarios WHERE id_usuario = ?")->execute([$id]);
    $mensaje = "Usuario eliminado.";

}

// ── CREAR usuario ──
if (isset($_POST['action']) && $_POST['action'] == 'create') {

    $nombres   = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $rol       = $_POST['rol'];

    // Comprobar que los campos obligatorios no están vacíos
    if (empty($nombres) || empty($apellidos) || empty($email) || empty($rol)) {
        $error = "Completa nombres, apellidos, email y rol.";

    } else {
        try {
            $sql = "INSERT INTO Usuarios (nombres, apellidos, email, password, rol)
                    VALUES (?, ?, ?, ?, ?)";

            $db->prepare($sql)->execute([$nombres, $apellidos, $email, $password, $rol]);
            $mensaje = "Usuario creado correctamente.";

        } catch (PDOException $e) {
            // Código 23000 = email duplicado en la base de datos
            if ($e->getCode() == '23000') {
                $error = "Ese email ya está registrado. Usa otro correo.";
            } else {
                $error = "Error al crear usuario: " . $e->getMessage();
            }
        }
    }
}

// ── LISTAR todos los usuarios de la base de datos ──
$usuarios = $db->query("SELECT * FROM Usuarios ORDER BY id_usuario ASC")
               ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar/headeradmin.php'; ?>

<main class="pagina-usuarios">

    <div class="usuarios-panel-header">
        <h1>Administrar Usuarios</h1>

        <?php if ($mensaje) echo "<p class='success-message'>$mensaje</p>"; ?>
        <?php if ($error)   echo "<p class='error-message'>$error</p>";   ?>
    </div>

    <!-- ── Tabla con los usuarios existentes ── -->
    <section class="usuarios-listado">
        <h2>Usuarios existentes</h2>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>

            <?php foreach ($usuarios as $u) { ?>
            <tr>
                <td><?php echo $u['id_usuario']; ?></td>
                <td><?php echo $u['nombres'] . ' ' . $u['apellidos']; ?></td>
                <td><?php echo $u['email']; ?></td>
                <td><?php echo $u['rol']; ?></td>
                <td>
                    <!-- Botón para eliminar ese usuario -->
                    <form method="post" onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?')">
                        <input type="hidden" name="action"     value="delete">
                        <input type="hidden" name="id_usuario" value="<?php echo $u['id_usuario']; ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php } ?>

        </table>
    </section>

    <br class="mobile-break" aria-hidden="true">

    <!-- ── Formulario para crear un nuevo usuario ── -->
    <section class="usuarios-crear">
        <h2>Agregar usuario</h2>

        <form method="post" class="form-crear">
            <input type="hidden" name="action" value="create">

            Nombres:    <input type="text"     name="nombres"   required><br>
            Apellidos:  <input type="text"     name="apellidos" required><br>
            Email:      <input type="email"    name="email"     required><br>
            Contraseña: <input type="password" name="password"><br>

            Rol:
            <select name="rol">
                <option>admin</option>
                <option>voluntario</option>
                <option selected>adoptante</option>
            </select>

            <button type="submit">Crear usuario</button>
        </form>
    </section>

</main>

<?php include '../navbar/footer.php'; ?>

</body>
</html>