<?php


add_filter('rest_authentication_errors', function ($result) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_disabled', 'La API REST está desactivada para usuarios no autenticados.', array('status' => 403));
    }
    return $result;
});



// Habilitar soporte para imagenes destacadas
add_theme_support('post-thumbnails');

// Definir un tamaño personalizado para las imagenes destacadas
function personalizar_tamanos_imagen()
{
    add_image_size('custom-thumb', 800, 400, true); // 800x400 recorte exacto
    add_image_size('custom-banner', 1200, 500, true); // Tamaño más grande para banners
    add_image_size('custom-banner-mobile', 800, 333, true); // Tamaño optimizado para móviles
}
add_action('after_setup_theme', 'personalizar_tamanos_imagen');

/***************************************************************************************** */
/**CARGAR CSS PERSONALIZADO */
function mytheme_enqueue_custom_styles()
{
    // Estilo principal del tema
    wp_enqueue_style('main-style', get_stylesheet_uri());

    // Tu nuevo archivo CSS
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/custom-style.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_custom_styles');


/********************************************************************************************* */
function enqueue_all_custom_scripts()
{
    if (!is_admin()) {
        // Reemplazar jQuery por CDN
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', [], null, true);
        wp_enqueue_script('jquery');
    }

    $theme_uri = get_template_directory_uri();

    // Scripts core
    //wp_enqueue_script('popper', "$theme_uri/assets/js/core/popper.min.js", [], null, true);
    //wp_enqueue_script('bootstrap', "$theme_uri/assets/js/core/bootstrap.min.js", ['jquery'], null, true);
    wp_enqueue_script('bootstrap-bundle', "$theme_uri/assets/js/plugins/bootstrap.bundle.min.js", ['jquery'], null, true);

    // Plugins
    wp_enqueue_script('bootstrap-switch', "$theme_uri/assets/js/plugins/bootstrap-switch.js", [], null, true);
    wp_enqueue_script('nouislider', "$theme_uri/assets/js/plugins/nouislider.min.js", [], null, true);
    wp_enqueue_script('moment', "$theme_uri/assets/js/plugins/moment.min.js", [], null, true);
    wp_enqueue_script('tagsinput', "$theme_uri/assets/js/plugins/bootstrap-tagsinput.js", [], null, true);
    wp_enqueue_script('selectpicker', "$theme_uri/assets/js/plugins/bootstrap-selectpicker.js", [], null, true);
    wp_enqueue_script('datetimepicker', "$theme_uri/assets/js/plugins/bootstrap-datetimepicker.js", [], null, true);

    // Otros scripts
    wp_enqueue_script('now-ui-kit', "$theme_uri/assets/js/now-ui-kit.js?v=1.3.1", [], null, true);
    wp_enqueue_script('efectos', "$theme_uri/assets/js/efectos.js", [], null, true);

    // FontAwesome Kit
    wp_enqueue_script('fontawesome-kit', 'https://kit.fontawesome.com/d67261bbd0.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_all_custom_scripts');

/********************************************************************************** */
// CPT BANNER
function crear_cpt_banner() // Definimos una función para registrar el tipo de contenido personalizado "banner"
{
    $args = array( // Creamos un array con la configuración del CPT
        'labels' => array( // Etiquetas visibles en el panel de administración
            'name' => 'Banners', // Nombre plural que aparece en el menú y títulos
            'singular_name' => 'Banner',  // Nombre singular para formularios o pantallas
            'menu_name' => 'Banners', // Nombre que se muestra en el menú de WordPress
            'add_new' => 'Añadir nuevo', // Texto del botón para añadir uno nuevo
            'add_new_item' => 'Añadir nuevo Banner', // Texto para añadir un nuevo ítem
            'edit_item' => 'Editar Banner', // Texto para editar un banner
            'new_item' => 'Nuevo Banner', // Texto para un nuevo banner
            'view_item' => 'Ver Banner', // Texto para ver un banner
            'search_items' => 'Buscar Banners', // Texto para buscar en la lista de banners
            'not_found' => 'No se han encontrado banners', // Mensaje cuando no hay banners
            'not_found_in_trash' => 'No se han encontrado banners en la papelera', // Mensaje para papelera vacía
        ),
        'public' => true, // Hace que este tipo de contenido sea visible públicamente (en el frontend)
        'has_archive' => false, // No queremos una página tipo archivo para listar todos los banners
        'rewrite' => array('slug' => 'banners'), // Define la estructura de URL personalizada: /banners/nombre-del-banner
        'show_in_rest' => true, // Permite usar el editor de bloques (Gutenberg) y acceso vía REST API
        'supports' => array('title', 'thumbnail'), // Este CPT solo admite título e imagen destacada (sin editor de texto)
    );

    // Registrar el tipo de contenido personalizado con el nombre 'banner' y los argumentos definidos
    register_post_type('banner', $args);
}

// Ejecutar la función anterior cuando se inicialice WordPress
add_action('init', 'crear_cpt_banner');


// 1. Registrar los meta boxes para el CPT de Banner
function agregar_meta_boxes_banner()
{
    add_meta_box(
        'banner_meta', // ID del meta box
        'Campos del Banner', // Título del meta box
        'mostrar_meta_box_banner', // Función que muestra el contenido del meta box
        'banner', // Tipo de contenido (el CPT 'banner')
        'normal', // Contexto (donde se coloca el meta box, puede ser 'normal', 'side', 'advanced')
        'high' // Prioridad (qué tan alto aparece en la pantalla)
    );
}
add_action('add_meta_boxes', 'agregar_meta_boxes_banner');

// 2. Mostrar los campos en el meta box
function mostrar_meta_box_banner($post) // Función que se encarga de mostrar los campos personalizados del CPT 'banner'
{
    // Recuperamos los valores guardados previamente del post actual (si existen)
    $titulo_banner = get_post_meta($post->ID, '_titulo_banner', true); // Obtener el título personalizado del banner
    $descripcion_banner = get_post_meta($post->ID, '_descripcion_banner', true); // Obtener la descripción del banner
    $titulo_boton_enlace = get_post_meta($post->ID, '_titulo_boton_enlace', true); //obtener titulo del boton enlace
    $tipo_enlace = get_post_meta($post->ID, '_tipo_enlace', true); //obtener el tipo de enlace
    $enlace_boton_contacto = get_post_meta($post->ID, '_enlace_boton_contacto', true); // Obtener el enlace del botón

    // Agrega un campo oculto (nonce) para verificar la autenticidad del formulario al guardar
    wp_nonce_field('guardar_banner', 'banner_nonce');
    ?>

    <!-- Campo de texto para introducir o editar el título del banner -->
    <label for="titulo_banner">Título del Banner:</label>
    <input type="text" name="titulo_banner" id="titulo_banner" value="<?php echo esc_attr($titulo_banner); ?>"
        class="widefat" /> <!-- widefat aplica estilo de ancho completo en admin -->

    <!-- Área de texto para introducir o editar la descripción del banner -->
    <label for="descripcion_banner">Descripción:</label>
    <textarea name="descripcion_banner" id="descripcion_banner"
        class="widefat"><?php echo esc_textarea($descripcion_banner); ?></textarea>

    <!-- Campo de texto para introducir el título del botón -->
    <label for="titulo_boton_enlace">Título del Botón:</label>
    <input type="text" name="titulo_boton_enlace" id="titulo_boton_enlace"
        value="<?php echo esc_attr($titulo_boton_enlace); ?>" class="widefat" />

    <!-- Desplegable para seleccionar tipo de enlace -->
    <label for="tipo_enlace">Tipo de Enlace:</label>
    <select name="tipo_enlace" id="tipo_enlace" class="widefat">
        <option value="url" <?php selected($tipo_enlace, 'url'); ?>>URL (Redirección)</option>
        <option value="tel" <?php selected($tipo_enlace, 'tel'); ?>>Teléfono</option>
        <option value="mailto" <?php selected($tipo_enlace, 'mailto'); ?>>Correo</option>
        <option value="whatsapp" <?php selected($tipo_enlace, 'whatsapp'); ?>>WhatsApp</option>
    </select>

    <!-- Campo de texto para introducir o editar el enlace del botón de contacto -->
    <label for="enlace_boton_contacto">Enlace Botón de Contacto:</label>
    <input type="text" name="enlace_boton_contacto" id="enlace_boton_contacto"
        value="<?php echo esc_attr($enlace_boton_contacto); ?>" class="widefat" />

    <?php
}


// 3. Guardar los Datos de los Meta Boxes 
function guardar_meta_box_banner($post_id) // Función que guarda los datos del meta box personalizado del banner
{
    // Evitar que se ejecute durante el guardado automático (autosave de WordPress)
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // Verificar que el nonce esté presente y sea válido (para evitar envíos maliciosos)
    if (!isset($_POST['banner_nonce']) || !wp_verify_nonce($_POST['banner_nonce'], 'guardar_banner')) {
        return $post_id;
    }

    // GUARDAR TÍTULO: Si el campo 'titulo_banner' está presente en el formulario, lo guardamos
    if (isset($_POST['titulo_banner'])) {
        // Sanitiza el texto y actualiza el campo personalizado '_titulo_banner' en la base de datos
        update_post_meta($post_id, '_titulo_banner', sanitize_text_field($_POST['titulo_banner']));
    }

    // GUARDAR DESCRIPCIÓN: Si el campo 'descripcion_banner' existe, lo guardamos
    if (isset($_POST['descripcion_banner'])) {
        // Sanitiza el contenido del textarea y lo guarda en '_descripcion_banner'
        update_post_meta($post_id, '_descripcion_banner', sanitize_textarea_field($_POST['descripcion_banner']));
    }

    // GUARDAR TÍTULO DEL BOTÓN
    if (isset($_POST['titulo_boton_enlace'])) {
        update_post_meta($post_id, '_titulo_boton_enlace', sanitize_text_field($_POST['titulo_boton_enlace']));
    }

    // GUARDAR TIPO DE ENLACE
    if (isset($_POST['tipo_enlace'])) {
        update_post_meta($post_id, '_tipo_enlace', sanitize_text_field($_POST['tipo_enlace']));
    }

    // GUARDAR ENLACE DEL BOTÓN: Si el campo 'enlace_boton_contacto' existe, lo guardamos
    if (isset($_POST['enlace_boton_contacto'])) {
        // Sanitiza el texto del enlace y lo guarda en '_enlace_boton_contacto'
        update_post_meta($post_id, '_enlace_boton_contacto', sanitize_text_field($_POST['enlace_boton_contacto']));
    }

    // Devolvemos el ID del post por si se usa como referencia
    return $post_id;
}

// Registramos la función anterior para que se ejecute cuando se guarda cualquier post
add_action('save_post', 'guardar_meta_box_banner');


/** TERMINA CPT BANNER */

/***************************************************************************************************************************************************/

/**COMIENZA CPT MENU **/
//1. crear cpt menu
function crear_cpt_menu() // Definimos la función que crea el tipo de contenido personalizado "menu"
{
    $args = array( // Creamos un array con todas las configuraciones necesarias
        'labels' => array( // Etiquetas que se mostrarán en el panel de administración de WordPress
            'name' => 'Menús', // Nombre plural (ej. "Menús")
            'singular_name' => 'Menú',  // Nombre singular (ej. "Menú")
            'menu_name' => 'Menús', // Nombre que se muestra en el menú lateral del admin
            'add_new' => 'Añadir nuevo', // Texto del botón para añadir un nuevo elemento
            'add_new_item' => 'Añadir nuevo Menú', // Texto de la pantalla al crear uno nuevo
            'edit_item' => 'Editar Menú', // Texto al editar un menú existente
            'new_item' => 'Nuevo Menú', // Texto para un nuevo ítem
            'view_item' => 'Ver Menú', // Texto para ver el menú desde el admin
            'search_items' => 'Buscar Menús', // Texto del campo de búsqueda
            'not_found' => 'No se han encontrado menús', // Mensaje cuando no se encuentran entradas
            'not_found_in_trash' => 'No se han encontrado menús en la papelera', // Mensaje en papelera vacía
        ),
        'public' => true, // Hace que este tipo de contenido sea visible en el frontend
        'has_archive' => true, // Habilita la página de archivo para listar todos los menús (ej. /menus)
        'rewrite' => array('slug' => 'menus'), // Define la parte de la URL personalizada: tusitio.com/menus
        'show_in_rest' => true, // Permite usar el editor de bloques (Gutenberg) y REST API
        'supports' => array('title', 'editor', 'thumbnail'), // Activa campos: título, editor de texto e imagen destacada
    );

    // Registrar el tipo de contenido personalizado con el nombre 'menu' y los argumentos definidos
    register_post_type('menu', $args);
}

// Indica a WordPress que ejecute la función anterior cuando inicializa el sistema
add_action('init', 'crear_cpt_menu');


/** TERMINA CPT BANNER */

/***************************************************************************************************************************************************/

/**COMIENZA CPT WHAT CLIENT SAY **/

// 1. crear cpt What client say
function crear_cpt_client_say() // Función para registrar el CPT "review" (testimonios de clientes)
{
    $args = array( // Configuración del CPT
        'labels' => array( // Etiquetas que aparecerán en el panel de administración
            'name' => 'Client Testimonials', // Nombre plural (visible en el menú)
            'singular_name' => 'Client Testimonial', // Nombre singular
            'menu_name' => 'What Clients Say', // Nombre que aparece en el menú lateral
            'add_new' => 'Añadir nuevo', // Botón para añadir nueva reseña
            'add_new_item' => 'Añadir nueva reseña', // Texto al añadir una nueva entrada
            'edit_item' => 'Editar reseña', // Texto al editar
            'new_item' => 'Nueva reseña', // Texto al crear una nueva
            'view_item' => 'Ver reseña', // Texto al ver
            'search_items' => 'Buscar reseñas', // Texto del buscador
            'not_found' => 'No se han encontrado reseñas', // Si no hay entradas
            'not_found_in_trash' => 'No se han encontrado reseñas en la papelera', // Si la papelera está vacía
        ),
        'public' => true, // Visible en el frontend
        'has_archive' => false, // No necesita página de archivo
        'rewrite' => array('slug' => 'client-testimonials'), // URL base del CPT
        'show_in_rest' => true, // Compatible con el editor Gutenberg
        'supports' => array('title', 'thumbnail'), // Admite título e imagen destacada
    );

    register_post_type('review', $args); // Registrar el CPT con identificador 'review'
}
add_action('init', 'crear_cpt_client_say'); // Ejecutar la función al iniciar WordPress

// 2. Registrar los meta boxes para el CPT 'review'
function agregar_meta_boxes_client_say()
{
    add_meta_box(
        'client_say_meta', // ID del meta box
        'Detalles del Testimonio', // Título que se muestra en el panel
        'mostrar_meta_box_client_say', // Función que muestra los campos
        'review', // CPT al que se aplica
        'normal', // Ubicación (normal, side, etc.)
        'high' // Prioridad alta para que aparezca arriba
    );
}
add_action('add_meta_boxes', 'agregar_meta_boxes_client_say'); // Añadir el meta box al cargar el editor

// 3. Mostrar los campos personalizados en el meta box
function mostrar_meta_box_client_say($post)
{
    $client_name = get_post_meta($post->ID, '_client_name', true); // Obtener el nombre del cliente
    $review = get_post_meta($post->ID, '_review', true); // Obtener la reseña del cliente

    wp_nonce_field('guardar_client_say', 'client_say_nonce'); // Añadir un campo oculto para seguridad
    ?>
    <!-- Campo para introducir el nombre del cliente -->
    <label for="client_name">Nombre del Cliente:</label>
    <input type="text" name="client_name" id="client_name" value="<?php echo esc_attr($client_name); ?>" class="widefat" />

    <!-- Campo para introducir la reseña del cliente -->
    <label for="review">Reseña:</label>
    <textarea name="review" id="review" class="widefat"><?php echo esc_textarea($review); ?></textarea>
    <?php
}

// 4. Guardar los datos del meta box al guardar el post
function guardar_meta_box_client_say($post_id)
{
    // Evitar guardar automáticamente (autosave)
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // Verificar que el nonce existe y es válido
    if (!isset($_POST['client_say_nonce']) || !wp_verify_nonce($_POST['client_say_nonce'], 'guardar_client_say'))
        return $post_id;

    // Guardar el nombre del cliente si existe
    if (isset($_POST['client_name'])) {
        update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['client_name']));
    }

    // Guardar la reseña si existe
    if (isset($_POST['review'])) {
        update_post_meta($post_id, '_review', sanitize_textarea_field($_POST['review']));
    }

    return $post_id;
}
add_action('save_post', 'guardar_meta_box_client_say'); // Ejecutar al guardar el post


/******************************************************************************************* */

// Habilitar soporte de WooCommerce en el tema
function my_theme_setup()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'my_theme_setup');

/************************************************************************************************ */

function register_header_cart_widget_area() // Declaramos la función que crea el área de widget
{
    register_sidebar(array( // Función que registra un área de widgets en WordPress
        'name' => 'Header Cart', // Nombre que verá el usuario en el panel de administración
        'id' => 'header-cart', // Identificador único para esta área (se usa en el código)
        'before_widget' => '<div class="widget">', // HTML que se añade antes de cada widget insertado
        'after_widget' => '</div>', // HTML que se añade después de cada widget
        'before_title' => '<h3 class="widget-title">', // HTML antes del título del widget
        'after_title' => '</h3>', // HTML después del título del widget
    ));
}

// Hook que indica que la función debe ejecutarse al inicializar los widgets
add_action('widgets_init', 'register_header_cart_widget_area');


/************************************************************************************************************** */
/**COMIENZA CPT RESERVAS (BOOKING) **/
// Crear el Custom Post Type (CPT) para reservas
function crear_cpt_reservas() // Función para crear el Custom Post Type 'reservas'
{
    $labels = array( // Etiquetas que se mostrarán en el panel de administración
        'name' => 'Reservas', // Nombre plural
        'singular_name' => 'Reserva', // Nombre singular
        'menu_name' => 'Reservas', // Nombre en el menú de administración
        'name_admin_bar' => 'Reserva', // Nombre en la barra superior del admin
        'add_new' => 'Añadir Nueva', // Texto del botón "Añadir nueva"
        'add_new_item' => 'Añadir Nueva Reserva', // Texto al añadir una nueva reserva
        'new_item' => 'Nueva Reserva', // Texto para nueva entrada
        'edit_item' => 'Editar Reserva', // Texto para editar
        'view_item' => 'Ver Reserva', // Texto para ver
        'all_items' => 'Todas las Reservas', // Texto para la lista de entradas
        'search_items' => 'Buscar Reservas', // Texto del buscador
        'not_found' => 'No se encontraron reservas', // Texto si no hay reservas
        'not_found_in_trash' => 'No se encontraron reservas en la papelera', // Texto si la papelera está vacía
        'parent_item_colon' => '', // No se usa, pero es obligatorio en versiones anteriores
    );

    $args = array( // Opciones de configuración del CPT
        'labels' => $labels, // Se asignan las etiquetas definidas arriba
        'public' => false, // El CPT no será visible públicamente en el frontend
        'show_ui' => true, // Mostrará la interfaz en el admin de WordPress
        'show_in_menu' => true, // Mostrará el CPT en el menú del admin
        'menu_position' => 5, // Posición en el menú (cerca de entradas/páginas)
        'menu_icon' => 'dashicons-calendar', // Icono de calendario en el menú
        'supports' => array('title'), // Solo admite el campo título
    );

    register_post_type('reservas', $args); // Se registra el CPT con el nombre 'reservas' y la configuración
}

add_action('init', 'crear_cpt_reservas'); // Se ejecuta la función al inicializar WordPress


// Mostrar los datos de la reserva en el panel de administración
function mostrar_datos_reserva_meta_box($post) // Función para mostrar los detalles de una reserva
{
    global $wpdb; // Accedemos al objeto global de base de datos de WordPress

    $id_reserva = get_post_meta($post->ID, 'reserva_id', true); // Obtenemos el ID de la reserva desde los metadatos del post

    if (!empty($id_reserva)) { // Si existe un ID
        $reserva = $wpdb->get_row( // Obtenemos la fila completa con los datos de la tabla personalizada
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}reservas_reservas WHERE id = %d", $id_reserva)
        );

        if ($reserva) { // Si se encuentran datos de la reserva
            echo '<p><strong>Nombre:</strong> ' . esc_html($reserva->nombre) . '</p>'; // Muestra el nombre
            echo '<p><strong>Apellidos:</strong> ' . esc_html($reserva->apellidos) . '</p>'; // Muestra los apellidos
            echo '<p><strong>Email:</strong> ' . esc_html($reserva->email) . '</p>'; // Muestra el email
            echo '<p><strong>Teléfono:</strong> ' . esc_html($reserva->telefono) . '</p>'; // Muestra el teléfono
            echo '<p><strong>Fecha de Reserva:</strong> ' . esc_html($reserva->fecha_reserva) . '</p>'; // Muestra la fecha
            echo '<p><strong>Hora de Reserva:</strong> ' . esc_html($reserva->hora_reserva) . '</p>'; // Muestra la hora
            echo '<p><strong>Número de Comensales:</strong> ' . esc_html($reserva->num_comensales) . '</p>'; // Muestra comensales
            echo '<p><strong>Ubicación:</strong> ' . esc_html($reserva->ubicacion) . '</p>'; // Muestra ubicación
        } else {
            echo '<p>No se encontraron datos para esta reserva.</p>'; // Si no hay datos en la tabla
        }
    } else {
        echo '<p>No se ha encontrado el ID de la reserva en los metadatos del post.</p>'; // Si no hay ID guardado
    }
}

// Añadir la meta box al CPT 'reservas'
function agregar_meta_boxes_reservas() // Función para registrar la meta box personalizada
{
    add_meta_box(
        'informacion_reserva', // ID del meta box
        'Información de la Reserva', // Título que se muestra en el editor
        'mostrar_datos_reserva_meta_box', // Función que muestra el contenido
        'reservas', // CPT al que se aplica
        'normal', // Posición: "normal" es debajo del editor
        'high' // Prioridad alta para que aparezca arriba
    );
}

add_action('add_meta_boxes', 'agregar_meta_boxes_reservas'); // Se ejecuta al agregar meta boxes

/******************************************************************************************************************************* */

function my_theme_enqueue_swiper_assets() // Función para cargar los estilos y scripts de Swiper
{
    // Cargar el archivo CSS de Swiper desde CDN (hoja de estilos)
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0', 'all');

    // Cargar el archivo JavaScript principal de Swiper desde CDN
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);

    // Cargar un script personalizado de inicialización que depende de Swiper y jQuery
    wp_enqueue_script(
        'my-theme-swiper-init', // Nombre del script
        get_template_directory_uri() . '/assets/js/swiper-init.js', // Ruta al archivo local de tu tema
        array('swiper-js', 'jquery'), // Dependencias que deben cargarse antes
        '1.0', // Versión del script
        true // Cargar en el footer para mejorar rendimiento
    );
}

// Hook que ejecuta la función cuando WordPress carga los scripts del frontend
add_action('wp_enqueue_scripts', 'my_theme_enqueue_swiper_assets');

/*************************************************************** */
//CAMBIAR NOMBRE DE SECCION DE WORDPRESS
function cambiar_nombre_entradas_por_blog() {
    global $menu, $submenu;

    // Cambiar nombre principal en el menú lateral
    foreach ($menu as $key => $value) {
        if ($menu[$key][2] == 'edit.php') {
            $menu[$key][0] = 'Blog';
        }
    }

    // Cambiar submenús (como "Todas las entradas", "Añadir nueva", etc.)
    if (isset($submenu['edit.php'])) {
        $submenu['edit.php'][5][0] = 'Todas las entradas del Blog'; // Antes: Entradas
        $submenu['edit.php'][10][0] = 'Añadir entrada al Blog';     // Antes: Añadir nueva
        $submenu['edit.php'][15][0] = 'Categorías del Blog';        // Antes: Categorías
        $submenu['edit.php'][16][0] = 'Etiquetas del Blog';         // Antes: Etiquetas
    }
}
add_action('admin_menu', 'cambiar_nombre_entradas_por_blog');

/**************************************************************** */
//OCULTAR SECCIONES PANEL DE WORDPRESS
/*function ocultar_menus_para_cliente()
{
    remove_menu_page('edit.php?post_type=page');          // Páginas
    remove_menu_page('wpcf7');                             // Contact Form 7 (Contacto)
    remove_menu_page('marketing');                         // Marketing
    remove_menu_page('wpforms-overview');                  // WPForms
    remove_menu_page('themes.php');                        // Apariencia
    remove_menu_page('plugins.php');                       // Plugins
    remove_menu_page('tools.php');                         // Herramientas
    remove_menu_page('options-general.php');               // Ajustes
    remove_menu_page('wp-mail-smtp');                      // WP Mail SMTP
}
add_action('admin_menu', 'ocultar_menus_para_cliente', 999);*/

?>