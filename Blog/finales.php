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
    <title>Finales Felices - Michis</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar/headeradmin.php'; ?>
    
    <main class="container">
        <header class="blog-header">
            <section class="blog-header-top">
                <section>
                    <h1>🐾 Historias Felices</h1>
                    <p class="traductor" lang="es">Historias de gatos que encontraron su hogar ideal gracias al voluntariado.</p>
                    <p class="traductor" lang="ca">Històries de gats que van trobar la seva llar ideal gràcies al voluntariat.</p>
                </section>
                </section>
                <?php if (Admin::tieneAdminActivo()): ?>
                    <section class="blog-admin-info">
                        <section class="blog-admin-actions">
                            <a href="admin-blog.php" class="btn-primary btn-publicar-historia">Publicar historia</a>
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

        echo "Nombre del Adoptante: " . $datos['name'] . "<br>";
        echo "Nombre del Gato: " . $datos['catname'] . "<br>";
        echo "Historia: " . $datos['historia'] . "<br>";
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
                                <time>Publicado el: <?php echo htmlspecialchars($p['fecha']->toDateTime()->format('d/m/Y')); ?></time>
                            <?php endif; ?>
                        </section>
                    </article>
                <?php endforeach; ?>
            <?php elseif (empty($errorBlog)): ?>
                <p>No hay historias publicadas todavía. Vuelve más tarde para conocer los finales felices.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../navbar/footer.php'; ?>
</body>
</html>