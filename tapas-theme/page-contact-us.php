<?php get_header(); ?>


<!-- CONTACT US-->
<div class="cd-section">
  <div class="contact-content my-5">
    <div class="container text-center pt-5" id="contactus">
      <h2 class="font-weight-bold">Contact us</h2>
      <!--<h3 class="p-descripcionSecciones"><span style="font-weight: bold;">Let's Begin Creating Something Unique.</span> Your vision and our expertise unite here to design the perfect gastronomic experience for your next event.</h3>-->
      <div class="row">
        <div class="col-md-5 ml-auto mr-auto mt-4">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/imgCatering/chef-alta-cocina.jpg"
            class="img-fluid rounded shadow" alt="Chef en alta cocina"
            style="box-shadow: 0px 10px 25px 10px rgba(0, 0, 0, 0.3);">
        </div>
        <div class="col-md-5 ml-auto mr-auto" >
          <h3 class="mt-3">Send us a message</h3>
          <p class="description">
            You can contact us with anything related to our Products. We'll get in touch with you
            as soon as possible.
            <br><br>
          </p>
          <?php echo do_shortcode('[contact-form-7 id="bf622c0" title="Formulario de contacto 1"]'); ?>
        </div>
      </div> <!-- Fin row -->
    </div> <!-- Fin container -->
  </div> <!-- Fin contact-content -->
</div> <!-- Fin cd-section -->


<?php get_footer(); ?>