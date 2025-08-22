<?php get_header(); ?>

<!-- *********     MENU     *********      -->
<div class="menu-main">
    <div class="blogs-2" id="menu" style="padding:0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center ml-auto mr-auto">
                    <div class="d-inline-flex align-items-center justify-content-center">

                        <h2 class="title" id="animacion-h2-page-menu">Menu</h2>
                        <div class="col-md-6 text-center ml-auto mr-auto">
                            <p class="p-descripcionSecciones"><span class="resaltar-span-descripcion">A Universe of
                                    Select
                                    Flavours.</span> Explore our palette of creations — each dish a brushstroke of art
                                and passion, crafted to awaken the senses and elevate every celebration.</p>
                        </div>
                    </div>


                    <div class="row mt-5 justify-content-center">

                        <?php
                        $paged = get_query_var('paged'); // Obtener el valor de 'paged'
                        
                        if ($paged) {
                            $paged = get_query_var('paged'); // Si existe un valor, se usa tal cual
                        } else {
                            $paged = 1; // Si no hay valor, se asigna 1
                        }


                        // Consulta personalizada para obtener los CPT "menu"
                        $args = array(
                            'post_type' => 'menu', // Tipo de post CPT
                            'posts_per_page' => 6, // Mostrar todos los elementos
                            'paged' => $paged, // Página actual para paginación
                            'orderby' => 'date', // Ordenar por fecha
                            'order' => 'ASC', // Orden ascendente
                        );
                        $menu_query = new WP_Query($args);
                        // Se crea una nueva consulta personalizada de WordPress usando los argumentos definidos previamente en $args
                        
                        if ($menu_query->have_posts()):
                            // Comprueba si la consulta tiene resultados (es decir, si hay posts que mostrar)
                            while ($menu_query->have_posts()):
                                // Mientras haya posts disponibles en la consulta, se inicia el bucle
                                $menu_query->the_post();
                                // Establece el post actual dentro del bucle (necesario para usar funciones como the_title(), the_content(), etc.)
                                ?>

                                <div class="col-md-4">
                                    <div class="card card-blog card-plain">
                                        <a href="<?php echo get_the_permalink(); ?>">
                                            <div class="card-image">

                                                <?php
                                                // Comprobamos si hay una imagen destacada
                                                if (has_post_thumbnail()) {
                                                    // Si tiene imagen destacada, mostramos la imagen
                                                    echo '<img class="w-100 rounded img img-raised" src="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')) . '" alt="' . esc_attr(get_the_title()) . '" />';
                                                } else {
                                                    // Si no tiene imagen destacada, mostramos una imagen por defecto
                                                    echo '<img class="w-100 rounded img img-raised" src="' . get_template_directory_uri() . '/assets/img/card-blog2.jpg" alt="Imagen por defecto" />';
                                                }
                                                ?>

                                            </div>

                                            <div class="card-body text-left">
                                                <!-- <h6 class="text-info category"><?php echo esc_html($tipo_plato); ?></h6>-->
                                                <!-- Mostramos solo el título y el contenido -->
                                                <h5 class="card-title resaltar-span-descripcion">
                                                    <?php the_title(); ?>
                                                </h5>
                                                <p class="card-description">
                                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                                </p>
                                                <div class="card-footer">
                                                    <span>See more...</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            <?php endwhile; ?>



                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pagination d-flex justify-content-end">
                                    <?php
                                    $total_pages = $menu_query->max_num_pages; // Obtiene el número total de páginas del objeto de consulta ($query)
                                
                                    if ($total_pages > 1) {
                                        // Obtiene la página actual; si no se encuentra, se establece en 1
                                        $current_page = max(1, get_query_var('paged'));

                                        // Genera los enlaces de paginación utilizando la función paginate_links()
                                        echo paginate_links(array(
                                            'base' => get_pagenum_link(1) . '%_%', // Estructura base de los enlaces
                                            'format' => '/page/%#%', // Formato de la URL para las páginas
                                            'current' => $current_page, // Página actual resaltada
                                            'total' => $total_pages, // Número total de páginas
                                            'prev_text' => __('&laquo; Previous'), // Texto del botón "Anterior"
                                            'next_text' => __('Next &raquo;'), // Texto del botón "Siguiente"
                                        ));
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        wp_reset_postdata(); //Restaura la consulta original de WordPress.
                        else:
                            echo '<p>No hay publicaciones recientes.</p>'; //si no hay post muestra
                        endif;
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- *********    END MENU      *********      -->

<?php get_footer(); ?>