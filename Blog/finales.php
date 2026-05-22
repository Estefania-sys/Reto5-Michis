<?php
require_once __DIR__ . '/../Clases/Admin.php';
Admin::iniciar();
require_once __DIR__ . '/BlogMichis.php';
$blog = new BlogMichis();
$errorBlog = $blog->getErrorMessage();
$posts = $blog->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title class="traductor" data-es="Historias Felices" data-ca="Històries Felices"></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar/headeradmin.php'; ?>
    
    <main class="container">
        <header class="blog-header">
            <section class="blog-header-top">
                <section>
                    <h1 class="traductor" data-es="🐾 Historias Felices" data-ca="🐾 Històries Felices"></h1>
                    <p class="traductor" data-es="Historias de gatos que encontraron su hogar ideal gracias al voluntariado." data-ca="Històries de gats que van trobar la seva llar ideal gràcies al voluntariat."></p>
                </section>
                </section>
                <?php if (Admin::tieneAdminActivo()): ?>
                    <section class="blog-admin-info">
                        <section class="blog-admin-actions">
                            <a href="admin-blog.php" class="btn-primary btn-publicar-historia traductor" data-es="Publicar historia" data-ca="Publicar història"></a>
                        </section>
                    </section>
                <?php endif; ?>
            </section>
        </header>

        <?php
        // Ruta del archivo
        $rutaArchivo = 'blog.json';

        // Leer el contenido del archivo como una cadena de texto (string)
        $contenidoJson = file_get_contents($rutaArchivo);

        // Convertir el string JSON a un Array Asociativo de PHP
        $datos = json_decode($contenidoJson, true);

        // Textos del JSON traducidos mediante contenedores span
        echo "<span class='traductor' data-es='Nombre del Adoptante: ' data-ca='Nom de l\'Adoptant: '></span>" . $datos['name'] . "<br>";
        echo "<span class='traductor' data-es='Nombre del Gato: ' data-ca='Nom del Gat: '></span>" . $datos['catname'] . "<br>";
        echo "<span class='traductor' data-es='Historia: ' data-ca='Història: '></span>" . $datos['historia'] . "<br>";
        ?>
        
        <section class="grid-blog">
            <?php if (!empty($errorBlog)): ?>
                <p class="mensaje-error"><?php echo htmlspecialchars($errorBlog); ?></p>
            <?php endif; ?>

            <?php if (empty($errorBlog) && !empty($posts)): ?>
                <?php foreach ($posts as $p): ?>
                    <article class="blog-card">
                        <section class="blog-card-img">
                            <img src="../Imagenes/Gatos/<?php echo htmlspecialchars($p['foto']); ?>" alt="Foto final feliz">
                        </section>
                        <section class="blog-card-info">
                            <h3><?php echo htmlspecialchars($p['titulo']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($p['contenido'])); ?></p>
                            <?php if (!empty($p['fecha'])): ?>
                                <time><span class="traductor" data-es="Publicado el: " data-ca="Publicat el: "></span><?php echo htmlspecialchars($p['fecha']->toDateTime()->format('d/m/Y')); ?></time>
                            <?php endif; ?>
                        </section>
                    </article>
                <?php endforeach; ?>
            <?php elseif (empty($errorBlog)): ?>
                <p class="traductor" data-es="No hay historias publicadas todavía. Vuelve más tarde para conocer los finales felices." data-ca="No hi ha històries publicades encara. Torna més tard per conèixer els finals feliços."></p>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../navbar/footer.php'; ?>
</body>
</html>