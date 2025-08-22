<?php get_header(); ?>

<main class="container">
    <?php
    // Comprueba si hay posts disponibles en la consulta actual (por ejemplo, en un archivo de tipo 'review')
    if (have_posts()):
        // Si hay posts, inicia un bucle para recorrerlos
        while (have_posts()):
            the_post(); // Establece el post actual para acceder a sus datos con funciones como get_the_ID()
            // Obtener el valor del metacampo personalizado '_client_name' (nombre del cliente)
            $client_name = get_post_meta(get_the_ID(), '_client_name', true);
            // Obtener el valor del metacampo personalizado '_review' (texto de la reseña)
            $review_text = get_post_meta(get_the_ID(), '_review', true);
            ?>
            <article class="review">
                <!-- Título de la reseña -->
                <h1><?php the_title(); ?></h1>

                <!-- Imagen destacada -->
                <?php if (has_post_thumbnail()): ?>
                    <div class="review-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <!-- Nombre del cliente -->
                <?php if ($client_name): ?>
                    <h2>Opinión de: <?php echo esc_html($client_name); ?></h2>
                <?php endif; ?>

                <!-- Testimonio del cliente -->
                <?php if ($review_text): ?>
                    <blockquote class="review-text">
                        <?php echo esc_textarea($review_text); ?>
                    </blockquote>
                <?php endif; ?>

                <!-- Navegación entre reseñas -->
                <div class="review-navigation">
                    <?php
                    // Muestra un enlace al post anterior si existe, con el texto personalizado "← Reseña anterior"
                    previous_post_link('%link', '← Reseña anterior');
                    ?>

                    <?php
                    // Muestra un enlace al post siguiente si existe, con el texto personalizado "Siguiente reseña →"
                    next_post_link('%link', 'Siguiente reseña →');
                    ?>
                </div>
            </article>
            <?php
        endwhile;
    endif;
    ?>
</main>

<?php get_footer(); ?>