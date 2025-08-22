<?php
/**
 * The template for displaying all pages
 *
 * @package tapas-child
 */

get_header(); ?>

<div class="about-office">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-10 ml-auto mr-auto">
                <!--<h2 class="title" style="font-size:70px;font-weight:700;font-family:'Square Peg', cursive;">
                    <?php the_title(); ?>
                </h2>-->
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="description" id="about-content">
                            <?php
                            while ( have_posts() ) :
                                the_post();
                                the_content();
                            endwhile; // End of the loop.
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            // Puedes añadir aquí lógica para mostrar imágenes u otro contenido común a tus páginas si lo deseas
            ?>
        </div>
    </div>
</div>

