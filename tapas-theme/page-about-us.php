<?php get_header(); ?>

<!--     *********    ABOUT US     *********      -->
<div class="about-main">
  <div class="about-office">
    <div class="container">
      <div class="row ">
        <div class="col-md-12 ml-auto mr-auto">
          <h2 class="title text-center">About Us</h2>
          <div class="col-md-6 text-center ml-auto mr-auto">
            <p class="p-descripcionSecciones"><span class="resaltar-span-descripcion">The passion that drives
                excellence.</span> We’re not just serving food — we’re creating moments. Every dish we serve is a piece
              of who we are: Mediterranean heart, British home, and a whole lot of love.</p>
          </div>
          <div class="row mt-5">
            <div class="col-md-12">
              <h6 class="description" id="about-content"> <?php the_content(); ?></h6>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <img class="rounded img-raised" alt="Raised Image"
            src="<?php echo get_template_directory_uri(); ?>/assets/img/img_bajaresolucion/albondigas-salsa-tomate-receta-casera.jpg">
        </div>
        <div class="col-md-4">
          <img class="rounded img-raised" alt="Raised Image"
            src="<?php echo get_template_directory_uri(); ?>/assets/img/img_bajaresolucion/tortilla-patatas-receta-tradicional-española.jpg">
        </div>
        <div class="col-md-4">
          <img class="rounded img-raised mb-4" alt="Raised Image"
            src="<?php echo get_template_directory_uri(); ?>/assets/img/img_bajaresolucion/pimientos-padron-receta-tradicional-española.jpg">
        </div>
        <div class="col-md-6">
          <img class="rounded img-raised" alt="Raised Image"
            src="<?php echo get_template_directory_uri(); ?>/assets/img/img_bajaresolucion/ensalada-patatas-receta-verano-fresca.jpg">
        </div>
        <div class="col-md-6">
          <img class="rounded img-raised" alt="Raised Image"
            src="<?php echo get_template_directory_uri(); ?>/assets/img/img_bajaresolucion/gilda-pintxo-anchoa-oliva-guindilla.jpg">
        </div>
      </div>
    </div>
  </div>
</div>
<!--     *********    END ABOUT US      *********      -->

<?php get_footer(); ?>