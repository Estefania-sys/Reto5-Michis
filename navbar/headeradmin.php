<header class="navbar">
        <section class="logo">
            <a href="index.php"><img class="logoimg" src="Imagenes/Items/logoplaceholder.png" height="55" width="75"></a>
        </section>
        <nav>
            <ul>
                    <span>Bienvenid@, <?php echo $_SESSION['admin']; ?></span>
                    <a class="logoutbtn" href="logout.php">Cerrar Sesión</a>
            </ul>
        </nav>
    </header>
