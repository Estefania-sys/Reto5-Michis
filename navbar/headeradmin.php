<header class="navbar">
    <section class="logo">
        <a href="/Reto5-Michis/Admin/admin-index.php"><img class="logoimg" src="/Reto5-Michis/Imagenes/Items/logoplaceholder.png" height="55" width="75" alt="Logo"></a>
    </section>
    <nav>
        <ul>
            <a href="/Reto5-Michis/index.php">Inicio</a>
            <a href="/Reto5-Michis/catalogo.php">Adoptar</a>
            <a href="/Reto5-Michis/Blog/finales.php">Blog</a>
            <a href="/Reto5-Michis/contacto.php">Contacto</a>
            <span>Bienvenid@, <?php echo $_SESSION['admin']; ?></span>
            <a class="logoutbtn" href="/Reto5-Michis/logout.php">Cerrar Sesión</a>
        </ul>
    </nav>
</header>
