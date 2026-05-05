<<<<<<< HEAD
<?php // Inicio del apartado PHP
session_start(); // Inicio de sesión
session_destroy(); // Destruimos la sesión para cerrar la sesión del usuario
header('Location: index.php'); // Redirigimos al usuario a la página de inicio después de cerrar sesión
exit(); 
?> <!-- Fin del apartado PHP -->
=======
<?php
session_start();
session_destroy();
header("Location: index.php");
?>
>>>>>>> 23bd54a6c5a4df2625e157506d53bf2467609aa2
