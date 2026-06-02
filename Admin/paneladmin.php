<?php
require_once '../Clases/Admin.php';
require_once '../Clases/Voluntaria.php';
require_once '../Clases/Conexion.php';

// Iniciamos la sesión una sola vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos si NO hay ninguna de las dos sesiones activas
if (!Admin::tieneAdminActivo() && !Voluntaria::tieneVoluntariaActiva()) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin-body">
    <?php include '../navbar/headeradmin.php'; ?>

    <main class="dashboard-container">
        <div class="dashboard-grid">
            <a href="../catalogo.php" class="dash-card">
                <i class="fa-solid fa-cat"></i>
                <span class="traductor" data-es="Gestionar Gatos" data-ca="Gestionar Gats"></span>
            </a>
            
            <a href="admin-index.php" class="dash-card">
                <i class="fa-solid fa-file-signature"></i>
                <span class="traductor" data-es="Solicitudes de Adopción" data-ca="Sol·licituds d'Adopció"></span>
            </a>

            <a href="../Blog/admin-blog.php" class="dash-card">
                <i class="fa-solid fa-heart"></i>
                <span class="traductor" data-es="Historias Felices (Blog)" data-ca="Històries Feliços (Blog)"></span>
            </a>
            <?php if(isset($_SESSION['admin']) && !empty($_SESSION['admin'])): ?>
            <a href="admin-users.php" class="dash-card">
                <i class="fa-solid fa-users"></i>
                <span class="traductor" data-es="Gestionar Usuarios" data-ca="Gestionar Usuaris"></span>
            </a>
            <?php endif; ?>
            <?php if(isset($_SESSION['voluntaria']) && !empty($_SESSION['voluntaria'])): ?>
            <a href="../logout.php" class="dash-card logout-card">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="traductor" data-es="Cerrar Sesión" data-ca="Tancar Sessió"></span>
            </a>
            <?php endif; ?>
        </div>
    </main>

    <?php include '../navbar/footer.php'; ?>
</body>
</html>