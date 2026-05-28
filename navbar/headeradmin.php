<header class="navbaradmin">
    <section class="logo">
        <a href="/Reto5-Michis/Admin/paneladmin.php"><img class="logoimg" src="/Reto5-Michis/Imagenes/Items/logo.png" height="55" width="55" alt="Logo"></a>
    </section>
    <section class="cambio_idioma_admin">
        <button class="btn-lang" data-lang="es"><img src="/Reto5-Michis/Imagenes/Items/banderaespaña.png" alt="Español" height="35" width="55"></button>
        <button class="btn-lang" data-lang="ca"><img src="/Reto5-Michis/Imagenes/Items/banderacataluna.png" alt="Catalán" height="35" width="55"></button>
    </section>
    <input id="menu-checkbox-admin" class="menu-checkbox" type="checkbox" aria-label="Abrir menú">
    <label for="menu-checkbox-admin" class="menu-icon" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
    </label>
    <nav>
        <ul>
            <li><a href="/Reto5-Michis/Admin/paneladmin.php" class="traductor" data-es="Inicio" data-ca="Inici"></a></li>
            <li><a href="/Reto5-Michis/catalogo.php" class="traductor" data-es="Adoptar" data-ca="Adoptar"></a></li>
            <li><a href="/Reto5-Michis/Blog/finales.php" class="traductor" data-es="Blog" data-ca="Blog"></a></li>
            <li><a href="/Reto5-Michis/contacto.php" class="traductor" data-es="Contacto" data-ca="Contacte"></a></li>

            <?php if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])): ?>
                <span class="traductor" data-es="Bienvenid@, <?php echo htmlspecialchars($_SESSION['admin']); ?>" data-ca="Benvingut/da, <?php echo htmlspecialchars($_SESSION['admin']); ?>"></span>
                <li><a class="logoutbtn traductor" href="/Reto5-Michis/logout.php" data-es="<i class='fa-solid fa-right-from-bracket'></i> Cerrar Sesión" data-ca="<i class='fa-solid fa-right-from-bracket'></i> Tancar Sessió"></a></li>
            <?php elseif (isset($_SESSION['voluntaria']) && !empty($_SESSION['voluntaria'])): ?>
                <span class="traductor" data-es="Bienvenid@, <?php echo htmlspecialchars($_SESSION['voluntaria']); ?>" data-ca="Benvingut/da, <?php echo htmlspecialchars($_SESSION['voluntaria']); ?>"></span>
                <li><a class="logoutbtn traductor" href="/Reto5-Michis/logout.php" data-es="<i class='fa-solid fa-right-from-bracket'></i> Cerrar Sesión" data-ca="<i class='fa-solid fa-right-from-bracket'></i> Tancar Sessió"></a></li>
            <?php else: ?>
                <li><a href="/Reto5-Michis/login.php" class="btn-login traductor" data-es="Iniciar Sesión" data-ca="Iniciar Sesió"></a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script src="/Reto5-Michis/traduccionscript.js"></script>