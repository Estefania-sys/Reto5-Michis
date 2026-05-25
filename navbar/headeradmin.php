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
            <a href="/Reto5-Michis/index.php" class="traductor" data-es="Inicio" data-ca="Inici"></a>
            <a href="/Reto5-Michis/catalogo.php" class="traductor" data-es="Adoptar" data-ca="Adoptar"></a>
            <a href="/Reto5-Michis/Blog/finales.php" class="traductor" data-es="Blog" data-ca="Blog"></a>
            <a href="/Reto5-Michis/contacto.php" class="traductor" data-es="Contacto" data-ca="Contacte"></a>
            
            <?php if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])): ?>
                <span class="traductor" data-es="Bienvenid@, <?php echo htmlspecialchars($_SESSION['admin']); ?>" data-ca="Benvingut/da, <?php echo htmlspecialchars($_SESSION['admin']); ?>"></span>
                <a class="logoutbtn traductor" href="/Reto5-Michis/logout.php" data-es="Cerrar Sesión" data-ca="Tancar Sessió"></a>
            <?php else: ?>
                <a href="/Reto5-Michis/login.php" class="btn-login traductor" data-es="Admin Login" data-ca="Admin Login"></a>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script src="/Reto5-Michis/traduccionscript.js"></script>