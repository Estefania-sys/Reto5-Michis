<?php
session_start();
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
    <?php include __DIR__ . '/../navbar/header.php'; ?>
    
    <main class="container">
        <header class="blog-header">
            <div class="blog-header-top">
                <div>
                    <h1>🐾 Finales Felices</h1>
                    <p>Historias de gatos que encontraron su hogar ideal gracias al voluntariado.</p>
                </div>
                <?php if (!empty($_SESSION['admin'])): ?>
                    <div class="blog-admin-info">
                        <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin']); ?></span>
                        <div class="blog-admin-actions">
                            <a href="admin-blog.php" class="btn-primary" style="max-width: 220px; margin-top: 20px; display: inline-block;">Publicar historia</a>
                            <a href="../logout.php" class="btn-secondary" style="max-width: 220px; margin-top: 20px; display: inline-block;">Cerrar sesión</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        
        <section class="grid-blog">
            <?php if (!empty($errorBlog)): ?>
                <p class="mensaje-error"><?php echo htmlspecialchars($errorBlog); ?></p>
            <?php endif; ?>

            <?php if (empty($errorBlog) && !empty($posts)): ?>
                <?php foreach ($posts as $p): ?>
                    <article class="blog-card">
                        <div class="blog-card-img">
                            <img src="../Imagenes/Gatos/<?php echo htmlspecialchars($p['foto']); ?>" alt="Foto final feliz">
                        </div>
                        <div class="blog-card-info">
                            <h3><?php echo htmlspecialchars($p['titulo']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($p['contenido'])); ?></p>
                            <?php if (!empty($p['fecha'])): ?>
                                <time>Publicado el: <?php echo htmlspecialchars($p['fecha']->toDateTime()->format('d/m/Y')); ?></time>
                            <?php endif; ?>
                        </div>
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