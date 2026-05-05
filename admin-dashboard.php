<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Clases/Conexion.php';
require_once 'Clases/Adopcion.php';

$pdo = (new Conexion())->getConnection();
$solicitudes = Adopcion::listarTodas($pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="admin-nav">
        <span>Bienvenido, <?php echo $_SESSION['admin']; ?></span>
        <a href="logout.php">Cerrar Sesión</a>
    </header>
    <main class="container">
        <h2>Solicitudes Pendientes</h2>
        <table>
            <tr><th>Gato</th><th>Interesado</th><th>Mensaje</th></tr>
            <?php foreach($solicitudes as $s): ?>
            <tr>
                <td><?php echo $s['gato_nombre']; ?></td>
                <td><?php echo $s['user_nombre']; ?></td>
                <td><?php echo $s['observaciones']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>