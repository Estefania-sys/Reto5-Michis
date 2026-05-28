<?php
require_once '../Clases/Admin.php';
require_once '../Clases/Conexion.php';

Admin::iniciar();
Admin::requerirAdmin();

$db = (new Conexion())->getConnection();
$message = '';
$error = '';

// ELIMINAR usuario
if (isset($_POST['action'], $_POST['id_usuario']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id_usuario'];
    $db->prepare("DELETE FROM Usuarios WHERE id_usuario = ?")->execute([$id]);
    $message = "Usuario eliminado.";
}

// CREAR usuario
if (($_POST['action'] ?? '') === 'create') {
    $nombres   = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $email     = $_POST['email'] ?? '';
    $password  = $_POST['password'] ?? '';
    $rol       = $_POST['rol'] ?? 'adoptante';

    if (!$nombres || !$apellidos || !$email || !$rol) {
        $error = "Completa nombres, apellidos, email y rol.";
    } else {
        try {
            $sql = "INSERT INTO Usuarios (nombres,apellidos,email,password,rol) VALUES (?,?,?,?,?)";
            $db->prepare($sql)->execute([$nombres,$apellidos,$email,$password,$rol]);
            $message = "Usuario creado.";
        } catch (PDOException $e) {
            $error = "El email ya está registrado. Usa otro correo.";
        }
    }
}

// LISTAR usuarios
$users = $db->query("SELECT * FROM Usuarios ORDER BY id_usuario ASC")->fetchAll(PDO::FETCH_ASSOC);
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

        <?php if ($message) echo "<p class='success-message'>$message</p>"; ?>
        <?php if ($error)   echo "<p class='error-message'>$error</p>"; ?>
    </div>

    <!-- TABLA de usuarios -->
    <section class="usuarios-listado">
        <h2>Usuarios existentes</h2>
        <table border="1">
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acción</th></tr>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id_usuario'] ?></td>
                <td><?= $u['nombres'] . ' ' . $u['apellidos'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['rol'] ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('¿Eliminar?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Espaciador visual exclusivo para móviles -->
    <br class="mobile-break" aria-hidden="true">

    <section class="usuarios-crear">
        <h2>Agregar usuario</h2>
        <form method="post" class="form-crear">
            <input type="hidden" name="action" value="create">
            Nombres:    <input type="text"  name="nombres"          required><br>
            Apellidos:  <input type="text"  name="apellidos"        required><br>
            Email:      <input type="email" name="email"            required><br>
            Contraseña: <input type="text"  name="password"><br>
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