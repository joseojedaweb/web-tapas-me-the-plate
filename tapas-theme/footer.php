<div class="footer-main">


  <div class="mt-5 text-center" style="padding: 40px 0;"> <!-- Sección con margen superior y padding vertical -->
    <h2 class="font-weight-bold">What clients says</h2>
      
    </h2>

    <?php echo do_shortcode('[trustindex no-registration=google]');?>

  </div>


  <div class="subscribe-line subscribe-line-image"
    style="min-height: 500px;background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2025/03/mesa-comedor-preparada-cena-grupo.jpeg');background-position: 55% 10%;">
    <div class="container">
      <div class="row">
        <div class="col-md-6 my-5 ml-auto mr-auto">
          <div class="text-center">
            <h2 class="text-white"><b>Spanish cuisine from Birmingham to England & Wales</b></h2>
            <p class="description"></p>
          </div>
        </div>
        <div class="col-10 mx-auto">
          <div class="row text-center">
            <div class="col-md-4">
              <a href="example@email.com" target="_blank" class="btn btn-white bg-transparent mx-2">
                <i class="far fa-envelope" style="font-size:40px"></i>
                <h4>example@email.com</h4>
              </a>
            </div>
            <div class="col-md-4">
              <a href="tel:+00000000000" class="btn btn-white bg-transparent mx-2">
                <i class="fas fa-phone" style="font-size:40px"></i>
                <h4>+00 000 000</h4>
              </a>
            </div>
            <div class="col-md-4">
              <a href="https://wa.me/+00000000" target="_blank" class="btn btn-white bg-transparent mx-2">
                <i class="fab fa-whatsapp" style="font-size:40px"></i>
                <h4>+00 000 000</h4>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-main">
    <footer class="footer footer-big">
      <div class="container my-5">
        <div class="content">
          <div class="row">
            <div class="col-md-5 my-auto py-4 d-flex flex-column justify-content-center align-items-center">
              <img
                src="<?php echo get_template_directory_uri(); ?>/assets/img/logo/logo-nobackground-black2.png"
                class="w-100 logo" alt="luxury mediterraean soul catering">

              <div class="social-line social-line-big-icons" style="padding:0px;">
                <div class="row d-flex justify-content-center align-items-start">
                  <div class="col-6 col-md-6 d-flex justify-content-center">
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@tapasmetheplate.com" target="_blank" class="btn btn-dark btn-footer btn-icon btn-outline-default mx-2">
                      <i class="far fa-envelope" style="font-size:25px"></i>
                    </a>
                    <a href="tel:+447979967859" class="btn btn-dark btn-footer btn-icon btn-outline-default mx-2">
                      <i class="fas fa-phone" style="font-size:25px"></i>
                    </a>
                    <a href="https://wa.me/+447979967859" target="_blank" class="btn btn-dark btn-footer btn-icon btn-outline-default mx-2">
                      <i class="fab fa-whatsapp"></i>
                    </a>
                    <!--<a href="" class="btn btn-dark btn-footer btn-icon btn-outline-default mx-2">
                      <i class="fab fa-facebook"></i>
                    </a>-->
                    <a href="https://www.instagram.com/tapasmetheplate/" target="_blank" class="btn btn-dark btn-footer btn-icon btn-outline-default mx-2">
                      <i class="fab fa-instagram"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4 mb-5 my-auto py-4">
              <div class="mx-4">
                <div class="mb-3 text-uppercase"><b>Mediterranean soul catering</b></div>
                <p id="p-footer">A unique gastronomic experience, where Mediterranean tradition merges with culinary
                  innovation. Bringing the finest of Mediterranean cuisine directly to your table.</p>
              </div>
            </div>

            <div class="col-md-3 px-5 my-auto py-4">
              <div class="lista_enlaces">
                <div class="mb-3"><b>LINKS</b></div>
                <ul class="text-left">
                  <li><a href="<?php echo esc_url(home_url('/menu/')); ?>"><span>Plates we love</span></a></li>
                  <li><a href="<?php echo esc_url(home_url('/ours-services/')); ?>"><span>Ours Services</span></a></li>
                  <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><span>Blog</span></a></li>
                  <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>"><span>About Us</span></a></li>
                  <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>"><span>Contact Us</span></a></li>
                  <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>"><span>Booking</span></a></li>
                </ul>
              </div>
            </div>
          </div> <!-- Fin .row -->
        </div> <!-- Fin .content -->
      </div> <!-- Fin .container -->

      <div class="copyright d-flex justify-content-center text-center py-3 bg-dark" id="copyright">
        <div class="row justify-content-center px-3">
          <div class="col-auto mx-5">
            <p class="mb-0">
              <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>"
                class="text-decoration-none text-secondary" target="_blank"><span>Terms and Conditions</span></a> |
              <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="text-decoration-none text-secondary"
                target="_blank"><span>Privacy Policy</span></a> |
              <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>" class="text-decoration-none text-secondary"
                target="_blank"><span>Cookie Policy</span></a>
            </p>
          </div>
          <div class="col-auto mx-5">
            <p class="mb-0" id="p-multiplika">
              Copyright &copy; <?php echo date('Y'); ?>
              Developed by <a href="https://multiplika.es/" id="a-enlace-multiplika"><b>Multiplika</b></a> | Codemartia
              SL. All rights reserved.
            </p>
          </div>
        </div>
      </div>
    </footer>
  </div> <!-- Fin .footer-main -->
</div>

<script>
  // Espera a que todo el contenido del DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {

  // Selecciona el contenedor del carrusel de reseñas (con clase .reviews-slider)
  var swiperContainer = document.querySelector('.reviews-slider');

  // Si existe el contenedor, inicializa el slider Swiper
  if (swiperContainer) {
    var mySwiper = new Swiper(swiperContainer, {
      direction: 'horizontal', // El deslizador se moverá horizontalmente
      loop: true, // El carrusel se repetirá infinitamente
      slidesPerView: 1, // Se muestra una diapositiva por vista
      spaceBetween: 0, // Sin espacio entre diapositivas

      // Configuración de los puntos de paginación
      pagination: {
        el: '.swiper-pagination', // Elemento HTML que contiene los puntos
        clickable: true, // Permite hacer clic en los puntos para cambiar de slide
      },

      // Configuración de los botones de navegación (anterior / siguiente)
      navigation: {
        nextEl: '.swiper-button-next', // Selector del botón "siguiente"
        prevEl: '.swiper-button-prev', // Selector del botón "anterior"
      },

      // Configuración responsive según el tamaño de la pantalla
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 0 },   // Móviles pequeños
        768: { slidesPerView: 1, spaceBetween: 0 },   // Tablets
        1024: { slidesPerView: 1, spaceBetween: 0 }   // Escritorio
      }
    });
  }
});

// Usamos jQuery para ejecutar una función al cargar el documento
$(document).ready(function () {
  // Llamamos a la función del Now UI Kit que muestra el mapa de contacto
  nowuiKit.initContactUsMap(); // Asegúrate de que esta función esté disponible y la librería esté cargada
});

</script>

<?php include get_template_directory() . '/codecookies.php'; ?> <!--insertar archivo para ejecutar cookies-->

<?php wp_footer(); ?> <!--para ejecutar acciones y cargar scripts justo antes del cierre de </body>-->
</body>

</html>