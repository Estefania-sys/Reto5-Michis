<header class="navbar">
    <section class="logo">
        <a href="/Reto5-Michis/Admin/admin-index.php"><img class="logoimg" src="/Reto5-Michis/Imagenes/Items/logo.png" height="55" width="55" alt="Logo"></a>
    </section>
    <section class="cambio_idioma">
        <button class="btn-lang" data-lang="es"><img src="/Reto5-Michis/Imagenes/Items/banderaespaña.png" alt="Español" height="35" width="55"></button>
        <button class="btn-lang" data-lang="ca"><img src="/Reto5-Michis/Imagenes/Items/banderacataluna.png" alt="Catalán" height="35" width="55"></button>
    </section>
    <nav>
        <ul>
            <a href="/Reto5-Michis/index.php">Inicio</a>
            <a href="/Reto5-Michis/catalogo.php">Adoptar</a>
            <a href="/Reto5-Michis/Blog/finales.php">Blog</a>
            <a href="/Reto5-Michis/contacto.php">Contacto</a>
            <?php if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])): ?>
                <span>Bienvenid@, <?php echo htmlspecialchars($_SESSION['admin']); ?></span>
                <a class="logoutbtn" href="/Reto5-Michis/logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="/Reto5-Michis/login.php" class="btn-login">Admin Login</a>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script src="/Reto5-Michis/traduccionscript.js"></script> <!-- Cambio de idioma -->