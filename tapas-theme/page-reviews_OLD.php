<?php
/* Template Name: Reseñas Page */
get_header(); ?>

<!-- Contenedor para las reseñas -->
<div class="testimonials-3" style="padding: 0px;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center ml-auto mr-auto">
                <h2 class="title" id="h2_whatClientSay">
                    Reviews</h2>
                <p class="p-descripcionSecciones"><span class="resaltar-span-descripcion">The Echo of Extraordinary
                        Experiences.</span> See how our events have captivated and delighted, through the voices of
                    those who have experienced the magic of our high cuisine.</p>
            </div>
        </div>
        <div class="row">
            <?php
            //DEFINIMOS PARAMETRO PAGED PARA LA PAGINACION
            $paged = get_query_var('paged'); // Obtener el valor de 'paged'
            
            if ($paged) {
                $paged = get_query_var('paged'); // Si existe un valor, se usa tal cual
            } else {
                $paged = 1; // Si no hay valor, se asigna 1
            }


            // Argumentos de la consulta para el CPT "review"
            $args = array(
                'post_type' => 'review',  // Usamos el CPT 'review'
                'posts_per_page' => 6,   // Mostrar todas las reseñas
                'orderby' => 'date',      // Ordenar por fecha
                'order' => 'DESC',         // Orden descendente (más recientes primero)
                'paged' => $paged, // añadir el parametro 'paged'
            );

            $reviews_query = new WP_Query($args);
            // Crea una nueva consulta personalizada utilizando los argumentos definidos en $args (por ejemplo, para el CPT "review")
            // Verifica si la consulta ha devuelto resultados (es decir, si hay reseñas disponibles)
            if ($reviews_query->have_posts()):
                // Inicia el bucle para recorrer todas las reseñas encontradas
                while ($reviews_query->have_posts()):
                    $reviews_query->the_post(); // Prepara los datos del post actual para usarlos en funciones de WordPress
            
                    // Obtiene la URL de la imagen destacada del post (formato 'thumbnail')
                    $client_image = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

                    // Obtiene el valor del campo personalizado '_client_name' (nombre del cliente)
                    $client_name = get_post_meta(get_the_ID(), '_client_name', true);

                    // Obtiene el valor del campo personalizado '_review' (texto de la reseña)
                    $review_text = get_post_meta(get_the_ID(), '_review', true);

                    ?>
                    <div class="col-md-4">
                        <div class="card card-plain card-testimonial">
                            <div class="card-avatar">
                                <img class="rounded img img-raised" src="<?php echo esc_url($client_image); ?>" />
                            </div>

                            <div class="card-body">
                                <h4 class="card-title"><?php echo esc_html($client_name); ?></h4>
                                <p class="card-description"><?php echo esc_html($review_text); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                ?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="pagination">
                        <?php
                        $total_pages = $reviews_query->max_num_pages; // Obtiene el número total de páginas del objeto de consulta ($query)
                    
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
            else:
                echo '<p>No se han encontrado reseñas.</p>';
            endif;
            wp_reset_postdata();
            ?>

    </div>
</div>


<?php get_footer(); ?>