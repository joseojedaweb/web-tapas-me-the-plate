<?php get_header(); ?>

<div class="single-menu-main">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <?php if (have_posts()): ?>
                    <!-- Comprueba si hay entradas (posts) disponibles en la consulta principal de WordPress -->
                    <?php while (have_posts()): ?>
                        <!-- Mientras haya entradas disponibles, ejecuta el bucle -->
                        <?php the_post(); ?>
                        <!-- Establece el post actual para poder usar funciones como the_title(), the_content(), etc. -->

                        <div class="post-single">
                            <!-- Mostrar imagen destacada -->
                            <?php if (has_post_thumbnail()): ?>
                                <div class="post-thumbnail">
                                    <?php the_post_thumbnail('custom-thumb'); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Título del post -->
                            <h2 class="post-title text-center mt-4 mb-3"><?php the_title(); ?></h2>

                            <!-- Contenido del post -->
                            <div class="post-content px-3">
                                <?php the_content(); ?>
                            </div>

                            <!-- Imagen personalizada del menú -->
                            <?php
                            // Obtener el valor del campo personalizado '_imagen_menu_1' del post actual
                            $imagen_menu_1 = get_post_meta(get_the_ID(), '_imagen_menu_1', true);

                            // Si existe una imagen definida en ese campo, la mostramos
                            if ($imagen_menu_1): ?>
                                <div class="menu-images text-center my-4">
                                    <!-- Contenedor para mostrar la imagen con clases de centrado y márgenes -->

                                    <img src="<?php echo esc_url($imagen_menu_1); ?>" alt="Imagen del menú 1"
                                        class="img-fluid rounded" />
                                    <!-- Muestra la imagen usando la URL del campo personalizado, la hace responsiva y con bordes redondeados -->
                                </div>
                            <?php endif; ?>


                            <!-- Categorías y etiquetas del post -->
                            <div class="post-footer mt-4 px-3">
                                <!-- Contenedor del pie del post con margen superior (mt-4) y padding horizontal (px-3) -->

                                <div class="categories mb-2">
                                    <?php the_category(', '); ?>
                                    <!-- Muestra las categorías del post, separadas por comas -->
                                </div>

                                <div class="tags">
                                    <?php the_tags(); ?>
                                    <!-- Muestra las etiquetas del post si existen -->
                                </div>
                            </div>
                        </div>
                        
                    <?php endwhile; else: ?>
                    <p class="text-center">No hay contenido disponible.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>