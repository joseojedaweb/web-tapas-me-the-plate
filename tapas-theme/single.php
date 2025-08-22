<?php get_header(); ?>

<div class="single-main">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <?php
                if (have_posts()):
                    // Verifica si hay publicaciones disponibles en la consulta actual de WordPress
                    while (have_posts()):
                        // Si hay publicaciones, inicia un bucle para recorrer cada una
                        the_post();
                        // Establece el post actual. Esto permite usar funciones como the_title(), the_content(), etc.
                        ?>
                        
                        <div class="post-single">
                            <!-- configuracion tamanio imagen 
                        LO COLOCAMOS DEBAJO DEL TITULO PARA QUE NO SALGA LA IMAGEN DESTACADA EN LA NOTICIA 
                        -->
                            <?php if (has_post_thumbnail()): ?>
                                <!-- Verifica si el post actual tiene una imagen destacada asignada -->
                                <div class="post-thumbnail">
                                    <!-- Contenedor para mostrar la miniatura (puedes aplicar estilos CSS aquí) -->
                                    <?php
                                    if (get_the_ID()) {
                                        // Si el post tiene un ID válido, se muestra la imagen destacada
                                        the_post_thumbnail('custom-thumb');
                                        // Muestra la imagen destacada en el tamaño registrado como 'custom-thumb'
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <!--
                        thumbnail = 150x150 px (por defecto).tamaño miniatura
                        medium =  por defecto 300 px de ancho (altura proporcional).tamaño mediano.
                        medium_large =   por defecto 768 px de ancho (altura proporcional).
                        large =  por defecto 1024 px de ancho (altura proporcional). tamaño grande.
                        full =  tamaño original, sin modificaciones.
                        -->

                            <h2 class="post-title mt-4"><?php the_title(); ?></h2>
                            <div class="post-meta">
                                <span class="post-author"><?php the_author(); ?></span>
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                            </div>
                            <div class="post-content">
                                <?php the_content(); ?>
                            </div>


                            <!-- Mostrar imágenes personalizadas del CPT banner -->
                            <div class="banner-images">
                                <?php
                                // Obtiene el valor del campo personalizado '_imagen_banner_1' del post actual
                                $imagen_banner_1 = get_post_meta(get_the_ID(), '_imagen_banner_1', true);

                                // Si existe una imagen guardada en ese campo personalizado
                                if ($imagen_banner_1):
                                    // Muestra la imagen en pantalla con la clase CSS 'banner-img' y escapando la URL por seguridad
                                    echo '<img class="banner-img" src="' . esc_url($imagen_banner_1) . '" alt="Imagen de Banner 1" />';
                                endif;
                                ?>
                            </div>


                            <div class="post-footer">
                                <div class="tags">
                                    <?php the_tags(); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                else:
                    echo '<p>No hay contenido disponible.</p>';
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>