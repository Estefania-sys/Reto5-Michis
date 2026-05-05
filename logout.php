<?php // Inicio del apartado PHP
session_start(); // Inicio de sesión
session_destroy(); // Destruimos la sesión para cerrar la sesión del usuario
header('Location: index.php'); // Redirigimos al usuario a la página de inicio después de cerrar sesión
exit(); 
?> <!-- Fin del apartado PHP -->
