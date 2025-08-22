<?php get_header(); ?>

<!--     *********    BLOG     *********      -->
<div class="blog-main">
    <div class="projects-1" id="blog" style="padding:0;">
        <!--     *********    END BLOGS 5      *********      -->
        <div class="blogs-5" style="padding: 10px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 ml-auto mr-auto">
                        <h2 class="title text-center">Blog
                        </h2>
                        <div class="col-md-6 text-center ml-auto mr-auto">
                            <p class="p-descripcionSecciones"><span class="resaltar-span-descripcion">Culinary
                                    Inspiration
                                    and Visions.</span> Step into our space for ideas, trends, and reflections that
                                shape
                                our gastronomic world.</p>
                        </div>
                        <div class="row">
                            <?php
                            $paged = get_query_var('paged'); // Obtener el valor de 'paged'
                            
                            if ($paged) {
                                $paged = get_query_var('paged'); // Si existe un valor, se usa tal cual
                            } else {
                                $paged = 1; // Si no hay valor, se asigna 1
                            }

                            // Definir los parametros de la consulta
                            $args = array(
                                'posts_per_page' => 9, // entrada por pagina
                                'post_status' => 'publish', // Solo muestra las publicaciones activas
                                'paged' => $paged, // añadir el parametro 'paged'
                            );
                            $query = new WP_Query($args); // se crea un objeto query. WP_Query permite hacer consultas personalizadas
                            
                            //compruba si hay post disponibles
                            if ($query->have_posts()):
                                // si hay post, se ejcuta un while pra recorrerlos
                                while ($query->have_posts()):
                                    $query->the_post(); //have_post comprueba si hay mas post en la consulta. the_post muestra el siguiente post
                                    ?>
                                    <div class="col-md-4">

                                        <div class="card card-blog">
                                            <a href="<?php the_permalink(); ?>" style="text-decoration: none;">
                                                <div class="card-image">

                                                    <?php if (has_post_thumbnail()): ?><!--comprueba si tiene una imagen destacada-->
                                                        <!--muestra la imagen destacada-->
                                                        <img class="img rounded" src="<?php the_post_thumbnail_url('medium'); ?>">
                                                    <?php else: ?>
                                                        <!--si el post no tiene imagen muestra una por defecto-->
                                                        <img class="img rounded"
                                                            src="<?php echo get_template_directory_uri(); ?>/assets/img/card-blog2.jpg">
                                                    <?php endif; ?>

                                                </div>
                                                <div class="card-body">
                                                    <!--<h6 class="category text-primary">
                                                    <?php
                                                    //$category = get_the_category();
                                                    //obtiene la primera categoria del post y la muestra
                                                    //print_r($category[0]->name);
                                                    ?>
                                                </h6>-->
                                                    <h5 class="card-title">
                                                        <?php the_title(); ?>
                                                        <!--titulo del post-->
                                                    </h5>
                                                    <p class="card-description">
                                                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                                    </p>
                                                    <div class="card-footer">
                                                        <div class="stats stats-right ml-auto">
                                                            <span style="font-size: small; text-decoration: underline;">Read
                                                                more...</span><!--Enlace a la noticia completa-->
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </a>
                                    </div>
                                    <?php
                                endwhile;
                                ?>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pagination">
                                        <?php
                                        $total_pages = $query->max_num_pages; // Obtiene el número total de páginas del objeto de consulta ($query)
                                    
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
</div>
<!--     *********    END BLOG      *********      -->

<?php get_footer(); ?>