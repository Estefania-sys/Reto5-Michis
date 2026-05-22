<?php
require_once '../Clases/Admin.php';
Admin::iniciar();
Admin::requerirAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Michis</title>
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
                <span class="traductor" data-es="Finales Felices (Blog)" data-ca="Finals Feliços (Blog)"></span>
            </a>

            <a href="../logout.php" class="dash-card logout-card">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="traductor" data-es="Cerrar Sesión" data-ca="Tancar Sessió"></span>
            </a>
        </div>
    </main>

    <?php include '../navbar/footer.php'; ?>
</body>
</html>