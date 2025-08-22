<?php get_header(); ?>

<style>
  .alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 1rem;
    border-radius: 5px;
  }
</style>

<?php
if (get_transient('flash_mensaje_cuenta_eliminada')) {
  ?>
  <div class="container mt-4">
    <div class="alert alert-success text-center">
      ✅ Tu cuenta ha sido eliminada correctamente. Gracias por haber formado parte de nuestra comunidad.
    </div>
  </div>
  <?php
  delete_transient('flash_mensaje_cuenta_eliminada');
}
?>

<div class="index-main">

  <div class="hero position-relative overflow-hidden">
    <div class="hero-overlay position-relative w-100 h-100">
      <div id="carouselExampleIndicators" class="carousel slide h-100 w-100">
        <ol class="carousel-indicators">
          <?php
          //Configura la consulta para obtener todos los banners CPT
          $args = array(
            'post_type' => 'banner',
            'posts_per_page' => -1, //para obtener todos los banners que hay -1
          );
          $query = new WP_Query($args); // Ejecuta la consulta
          $i = 0; // contador para el slide activo
          
          while ($query->have_posts()):
            $query->the_post(); //avanza al siguiente banner
            ?>
            <!-- Crea un indicador para cada slide del carrusel -->
            <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i; ?>"
              class="<?php echo ($i == 0) ? 'active' : ''; ?>"></li> <!-- Marca el primer como activo -->
            <?php $i++; endwhile;
          wp_reset_postdata(); // Resetea los datos del post global para evitar conflictos ?>
        </ol>

        <div class="carousel-inner h-100">
          <?php
          $i = 0; //reinicia contador para el contenido del carrusel
          $query = new WP_Query($args); //repite la consulta para cargar el contenido
          
          while ($query->have_posts()):
            $query->the_post();
            ?>
            <!-- Contenedor de cada slide del carrusel -->
            <div class="carousel-item <?php echo ($i == 0) ? 'active' : ''; ?> h-100">
              <div class="header-filter page-header h-100">
                <!-- Imagen de fondo del banner con la imagen destacada del post -->
                <div class="page-header-image h-100 w-100"
                  style="background-size: cover; background-position: center; background-repeat: no-repeat;
                                        background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>');">
                  <!--Usa ese ID para obtener la URL de la imagen destacada del post-->
                </div>

                <!-- Contenido centrado dentro del banner -->
                <div class="container h-100 d-flex align-items-center justify-content-center">
                  <div class="row justify-content-center">
                    <div class="col-md-7 text-center">

                      <!-- Título personalizado del banner -->
                      <h2 class="title fade-in">
                        <?php echo esc_html(get_post_meta(get_the_ID(), '_titulo_banner', true)); ?>
                      </h2>

                      <!-- Descripción personalizada del banner -->
                      <p class="descripcion mt-3 fade-in-2 px-5 text-justify">
                        <?php echo esc_html(get_post_meta(get_the_ID(), '_descripcion_banner', true)); ?>
                      </p>

                      <!-- Botón con enlace al correo personalizado -->
                      <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-6">
                          <?php
                          $tipo_enlace = get_post_meta(get_the_ID(), '_tipo_enlace', true);
                          $valor_enlace = get_post_meta(get_the_ID(), '_enlace_boton_contacto', true);
                          $titulo_boton = get_post_meta(get_the_ID(), '_titulo_boton_enlace', true);

                          // Construir el href según el tipo
                          switch ($tipo_enlace) {
                            case 'tel':
                              $href = 'tel:' . preg_replace('/\s+/', '', $valor_enlace); // sin espacios
                              break;
                            case 'mailto':
                              $href = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . $valor_enlace;
                              break;
                            case 'whatsapp':
                              $numero = preg_replace('/\D/', '', $valor_enlace); // solo números
                              $href = 'https://wa.me/' . $numero;
                              break;
                            case 'url':
                            default:
                              $href = esc_url($valor_enlace);
                              break;
                          }
                          ?>

                          <a href="<?php echo esc_url($href); ?>"
                            class="botonEnlace btn btn-lg btn-primary text-white flex-fill fade-in-3" target="_blank">
                            <?php echo esc_html($titulo_boton ?: 'Más información'); ?>
                          </a>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php $i++; endwhile;
          wp_reset_postdata(); // Limpia después del loop personalizado (restaura a su estado inicial) ?>
        </div>

        <!--botones control banner-->
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <i class="arrows-1_minimal-left now-ui-icons"></i>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <i class="arrows-1_minimal-right now-ui-icons"></i>
        </a>
      </div>
    </div>
  </div>

  <?php wp_reset_postdata(); //restaura a su estado inicial ?>

  <div class="col-md-5 text-center ml-auto mr-auto my-5">
    <img
      src="<?php echo get_template_directory_uri(); ?>/assets/img/logo/logo-nobackground-black2.png"
      class="w-75 img-animada" alt="luxury mediterraean soul catering">
  </div>



  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-4 my-auto py-3"><!-- Columna izquierda vacía -->
        <p>Ready to transform your event into an unforgettable experience?
          At Tapas Me The Plate, every detail is designed to surprise, delight, and excite. Whether it's an intimate
          celebration, a dream wedding, or a stylish corporate event, our catering combines the best of Mediterranean
          and
          British cuisine with impeccable presentation.</p>
        <p>Make your reservation today and allow us to create moments your guests will remember with every sense.</p>
      </div>

      <div class="col-md-4 my-auto py-3">
        <section class="bloque-video">
          <video class="video" autoplay loop muted playsinline id="miVideo">
            <source
              src="<?php echo get_template_directory_uri(); //carga archivos que estan dentro del tema ?>/assets/video/VideoWeb_comprimido.mp4"
              type="video/mp4">
            Tu navegador no soporta la reproducción de vídeo.
          </video>
        </section>
      </div>

      <div class="col-md-4 my-auto py-3 text-left "><!-- Columna derecha vacía -->
        <p>This isn’t just catering. This is Tapas Me The Plate — where Mediterranean magic meets British flair, and
          every
          bite tells a story.</p>
        <p>Here, we don't serve plates: we create experiences. From the warmth of olive oil to the intensity of Iberian
          ham, each event becomes a sensorial journey that celebrates the best of both worlds. Tapas Me The Plate is not
          just a gastronomic choice; it's a statement of style, flavor, and authenticity.</p>
      </div>

    </div>
  </div>

  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-4 my-auto mx-auto text-center py-3">
        <h2 class="font-weight-bold">Our services</h2>
      </div>
      <div class="col-md-12 text-center">
        <div class="row">
          <div class="col-md-3">
            <span class="material-symbols-outlined" style="font-size:80px">dinner_dining</span>
            <h4>PRIVATE DINING</h4>
            <p>Enjoy handcrafted tapas at home with our chef-led experience.</p>
          </div>
          <div class="col-md-3">
            <span class="material-symbols-outlined" style="font-size:80px">business_center</span>
            <h4>CORPORATE EVENTS</h4>
            <p>Elevate your business meetings with premium Mediterranean catering.</p>
          </div>
          <div class="col-md-3">
            <span class="material-symbols-outlined" style="font-size:80px">chef_hat</span>
            <h4>HOME CHEF SERVICE</h4>
            <p>Our chefs come to your home and cook everything fresh on site.</p>
          </div>
          <div class="col-md-3">
            <span class="material-symbols-outlined" style="font-size:80px">room_service</span>
            <h4>THEMED MENUS</h4>
            <p>Share the joy of a traditional paella, freshly made pasta, a Greek menu or a bespoke menu of your favourite cuisine.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12 text-center py-3">
        <h2 class="font-weight-bold">Plates we love</h2>
      </div>
    </div>

    <div class="row justify-content-center">
      <?php
      // Definir los parámetros para consultar el CPT 'menu'
      $menu_args = array(
        'post_type' => 'menu',
        'posts_per_page' => 3, // Mostrar solo 3 entradas
        'orderby' => 'date',  // Ordenar por fecha de publicación
        'order' => 'DESC',  // Mostrar los más recientes primero
      );

      // ejecutamos la consulta
      $menu_query = new WP_Query($menu_args);

      // verificar si hay entradas que mostrar
      if ($menu_query->have_posts()):

        // bucle para recorrer cada entrada del CPT 'menu'
        while ($menu_query->have_posts()):
          $menu_query->the_post(); // cargar los datos del post encontrado
          ?>
          <div class="col-md-4">
            <div class="card card-blog card-plain mb-4">
              <a href="<?php the_permalink(); ?>"> <!-- Enlace a la seccion menu -->
                <div class="card-image">
                  <?php
                  // Comprobar si la entrada tiene imagen destacada
                  if (has_post_thumbnail()) {
                    // Mostrar la imagen destacada en tamaño medio
                    echo '<img class="img img-raised rounded" src="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')) . '" alt="' . esc_attr(get_the_title()) . '" />';
                  } else {
                    // Si no hay imagen, mostrar una imagen por defecto
                    echo '<img class="img img-raised rounded" src="' . get_template_directory_uri() . '/assets/img/card-blog2.jpg" alt="Default Image">';
                  }
                  ?>
                </div>
                <div class="card-body">
                  <h5 class="card-title"><?php the_title(); ?></h5> <!-- mostrar el título del menú -->
                  <p class="card-description"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                  <!-- mostrar la descripcion menú (solo 15 palabras)-->
                </div>
              </a>
            </div>
          </div>
        <?php endwhile;

        // restaurar el contexto global de WordPress tras el bucle personalizado
        wp_reset_postdata();
      else:

        // si no hay entradas, mostrar un mensaje al usuario
        echo '<p class="text-center">No menu entries found.</p>';
      endif;
      ?>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-4 text-center">
        <a href="<?php echo get_permalink(get_page_by_path('menu')); ?>" class="btn btn-primary">See Full Menu</a>
      </div>
    </div>
  </div>

  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12 py-3">
        <h2 class="font-weight-bold text-center">Blog</h2>
      </div>
    </div>

    <div class="row">
      <?php
      // Definimos los parámetros para la consulta del blog
      $blog_args = array(
        'post_type' => 'post',         // Tipo de contenido: entradas del blog (posts)
        'posts_per_page' => 3,         // Número máximo de entradas a mostrar: 3
        'orderby' => 'date',           // Ordenar por fecha
        'order' => 'DESC',             // Orden descendente (de más reciente a más antiguo)
      );

      // Ejecutamos la consulta personalizada con los argumentos definidos
      $blog_query = new WP_Query($blog_args);

      // Comprobamos si hay entradas que mostrar
      if ($blog_query->have_posts()):

        // Bucle que recorre las entradas encontradas
        while ($blog_query->have_posts()):
          $blog_query->the_post(); // Prepara los datos del post actual para usar funciones como the_title(), the_permalink(), etc.
          ?>

          <div class="col-md-4">
            <div class="card card-blog">
              <!-- Enlace al detalle del post -->
              <a href="<?php the_permalink(); ?>" style="text-decoration: none;">
                <div class="card-image">

                  <?php if (has_post_thumbnail()): ?>
                    <!-- Si la entrada tiene imagen destacada, la mostramos en tamaño medio -->
                    <img class="img rounded" src="<?php the_post_thumbnail_url('medium'); ?>">
                  <?php else: ?>
                    <!-- Si no tiene imagen destacada, mostramos una imagen por defecto del tema -->
                    <img class="img rounded" src="<?php echo get_template_directory_uri(); ?>/assets/img/card-blog2.jpg">
                  <?php endif; ?>

                </div>
                <div class="card-body">
                  <!-- Título del post -->
                  <h5 class="card-title"><?php the_title(); ?></h5>

                  <!-- Muestra un resumen del contenido del post (15 palabras) -->
                  <p class="card-description"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>

                  <div class="card-footer">
                    <div class="stats">
                      <!-- Texto de enlace para continuar leyendo -->
                      <span style="font-size: small; text-decoration: underline;">Read more...</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>

        <?php endwhile;

        // Restablece los datos globales del post original, necesarios si hay más bucles después
        wp_reset_postdata();

      else:
        // Si no hay entradas, mostramos un mensaje al usuario
        echo '<p class="text-center">No blog entries found.</p>';

      endif;
      ?>
    </div>


    <div class="row justify-content-center">
      <div class="col-md-4 text-center">
        <a href="<?php echo get_permalink(get_page_by_path('blog')); ?>" class="btn btn-primary">See All Blog Posts</a>
      </div>
    </div>
  </div>

</div>

<script>
  // Espera a que todo el contenido de la página esté completamente cargado
  document.addEventListener("DOMContentLoaded", function () {

    // Selecciona el elemento de video con el ID "miVideo"
    const video = document.getElementById("miVideo");

    // Escucha cuando el usuario hace clic sobre el video
    video.addEventListener("click", function () {

      // Si el video está pausado, lo reproduce
      if (video.paused) {
        video.play();

        // Si el video ya está reproduciéndose, lo pausa
      } else {
        video.pause();
      }
    });
  });
</script>


<?php get_footer(); ?>