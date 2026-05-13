<?php
require_once 'Clases/BlogMichis.php';
$blog = new BlogMichis();
$posts = $blog->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finales Felices - Michis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar/header.php' ?>
    
    <main class="container">
        <header class="blog-header">
            <h1>🐾 Finales Felices</h1>
            <p>Historias de gatos que encontraron su hogar ideal gracias al voluntariado.</p>
        </header>
        
        <section class="grid-blog">
            <?php foreach($posts as $p): ?>
                <article class="blog-card">
                    <div class="blog-card-img">
                        <img src="Imagenes/Gatos/<?php echo $p['foto']; ?>" alt="Foto final feliz">
                    </div>
                    <div class="blog-card-info">
                        <h3><?php echo $p['titulo']; ?></h3>
                        <p><?php echo $p['contenido']; ?></p>
                        <time>Publicado el: <?php echo $p['fecha']->toDateTime()->format('d/m/Y'); ?></time>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <?php include 'navbar/footer.php' ?>
</body>
</html>