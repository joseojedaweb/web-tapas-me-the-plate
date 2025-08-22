<?php
/**
 * Funciones del tema hijo de tapas-theme
 *
 * @package tapas-child
 */

add_action('init', function () {
  if (!session_id()) {
    session_start();
  }
});
/********************** */

if (!function_exists('mi_tema_hijo_enqueue_styles')) {
  function mi_tema_hijo_enqueue_styles()
  {

    // Enqueue la hoja de estilos del tema padre
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue la hoja de estilos del tema hijo (opcional, pero recomendado)
    wp_enqueue_style(
      'child-style',
      get_stylesheet_directory_uri() . '/style.css',
      array('parent-style'),
      wp_get_theme()->get('Version')
    );
  }
}
add_action('wp_enqueue_scripts', 'mi_tema_hijo_enqueue_styles');

/**********************************************************************************************************/
//MI CUENTA

function modificar_menu_mi_cuenta($items)
{
  unset($items['downloads']); // Eliminar la opción "Descargas"
  //$items['mis-datos'] = 'Mis Datos Personales'; // Añadir una nueva opción

  // Guardamos el nuevo array reordenado
  $nuevos_items = [];

  foreach ($items as $key => $label) {
    $nuevos_items[$key] = $label;

    // Insertamos después de "orders"
    if ($key === 'edit-account') {
      $nuevos_items['historial-reservas'] = 'Booking history';
    }
  }

  return $nuevos_items;
}
add_filter('woocommerce_account_menu_items', 'modificar_menu_mi_cuenta');

/********************************************************************************************************** */
function agregar_endpoint_historial_reservas()
{
  // Crea un endpoint adicional llamado 'historial-reservas'
  // EP_ROOT y EP_PAGES indican que funcionará en la raíz del sitio y en páginas
  add_rewrite_endpoint('historial-reservas', EP_ROOT | EP_PAGES);
}
add_action('init', 'agregar_endpoint_historial_reservas');
// Registra el endpoint cuando se inicializa WordPress


// Asegúrate de que WooCommerce lo reconozca// Permite que WordPress reconozca 'historial-reservas' como una variable de consulta válida
function historial_reservas_query_vars($vars)
{
  $vars[] = 'historial-reservas'; // Añade el endpoint a la lista de variables reconocidas
  return $vars;
}
add_filter('query_vars', 'historial_reservas_query_vars');
// Aplica el filtro para que WooCommerce y WP entiendan la variable de URL personalizada


function contenido_historial_reservas()
{
  if (!is_user_logged_in()) {
    echo '<p>You must be logged in to view your reservation history.</p>';
    return;
  }

  global $wpdb;
  // Accede al objeto global $wpdb para realizar consultas directas a la base de datos de WordPress
  $user_id = get_current_user_id();
  // Obtiene el ID del usuario actualmente logueado
  $user = wp_get_current_user();
  // Obtiene el objeto completo del usuario logueado
  $email = $user->user_email;
  // Extrae el correo electrónico del usuario (útil si la reserva se hizo sin estar registrado)


  // Buscar reservas por user_id o por email si el user_id es NULL
  $reservas = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT * FROM wp_reservas_reservas 
             WHERE user_id = %d 
                OR (email = %s AND user_id IS NULL)
             ORDER BY fecha_reserva DESC, hora_reserva DESC",
      $user_id,
      $email
    )
  );

  // Asociar reservas con el email al user_id actual (opcional)
  $wpdb->query(
    $wpdb->prepare(
      "UPDATE wp_reservas_reservas 
             SET user_id = %d 
             WHERE email = %s AND user_id IS NULL",
      $user_id,
      $email
    )
  );

  if (empty($reservas)) {
    echo '<p>You have not made any reservations yet.</p>';
  } else {
    echo '<h2 class="h2-tabla-reservas">Your Reservations</h2>';
    echo '<table class="tabla-reservas">';
    echo '<thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Guests</th>
                    <th>Location</th>
                    <th>Update</th>
                    <th>Cancel</th>
                </tr>
              </thead><tbody>';
    foreach ($reservas as $reserva) {
      echo '<tr>';
      echo '<td>' . esc_html(date('d/m/Y', strtotime($reserva->fecha_reserva))) . '</td>';
      echo '<td>' . esc_html($reserva->hora_reserva) . '</td>';
      echo '<td>' . esc_html($reserva->nombre . ' ' . $reserva->apellidos) . '</td>';
      echo '<td>' . esc_html($reserva->email) . '</td>';
      echo '<td>' . esc_html($reserva->telefono) . '</td>';
      echo '<td>' . esc_html($reserva->num_comensales) . '</td>';
      echo '<td>' . esc_html($reserva->ubicacion) . '</td>';
      // Verificar si la reserva es futura
      $reserva_timestamp = strtotime($reserva->fecha_reserva . ' ' . $reserva->hora_reserva);
      $ahora = current_time('timestamp');

      if ($reserva_timestamp > $ahora) {
        $link_modificar = esc_url("https://tapasmetheplate.com/newversion/modify-booking?id=" . $reserva->id);
        echo '<td><a href="' . $link_modificar . '" target="_blank" style="color: #00ffff;">Modify</a></td>';
      } else {
        echo '<td>Past date</td>';
      }

      // Cancel link o texto si fecha pasada
      if ($reserva_timestamp > $ahora) {
        $link_cancelar = esc_url("https://tapasmetheplate.com/newversion/cancel-booking?id=" . $reserva->id);
        echo '<td><a href="' . $link_cancelar . '" target="_blank" style="color: #ff4d4d;">Cancel</a></td>';
      } else {
        echo '<td>Past date</td>';
      }

      echo '</tr>';
    }
    echo '</tbody></table>';
  }
}
add_action('woocommerce_account_historial-reservas_endpoint', 'contenido_historial_reservas');

/******************************************************************************************************** */
//DASHBOARD -> mensaje de bienvenida
add_action('woocommerce_account_dashboard', 'agregar_mensaje_bienvenida');

function agregar_mensaje_bienvenida()
{
  $user = wp_get_current_user();
  $nombre = $user->display_name;

  echo '<div class="woocommerce-message" style="margin-bottom:20px;">';
  echo '👋 ¡Welcome back, <strong>' . esc_html($nombre) . '</strong>! We\'re glad to have you here.';
  echo '</div>';
}
/********************************************************************************************************** */

//Quitar titulo "Mi cuenta" de my account
add_action('wp_head', 'ocultar_titulo_en_my_account');

function ocultar_titulo_en_my_account()
{
  if (is_account_page()) { // Esto detecta la página "Mi cuenta" de WooCommerce
    echo '<style>
            h2.title {
                display: none !important;
            }
        </style>';
  }
}
/****************************************************************************************************** */
//mostrar un mensaje personalizado tras el registro del usuario
add_action('user_register', 'registrar_nueva_sesion_para_mensaje', 15, 1);
function registrar_nueva_sesion_para_mensaje($user_id)
{
  $_SESSION['registro_exitoso'] = true; // Establece una variable de sesión para indicar que el registro fue exitoso
}
/******************************************************************************************************** */

//Este código crea un shortcode que muestra el formulario de inicio de sesión de WooCommerce si el usuario no está logueado, 
// y si ya está logueado, carga y muestra el dashboard de usuario de WooCommerce.
function solo_formulario_login_woocommerce()
{
  if (is_user_logged_in()) {
    // Si el usuario ya está logueado, lo redirige a la página "Mi Cuenta"
    wp_redirect(home_url('/my-account/'));
    exit; // Para evitar que el código posterior se ejecute
  }

  ob_start();
  $_GET['view'] = 'login'; // Forzamos vista login
  wc_get_template('myaccount/form-login.php');
  return ob_get_clean();
}
add_shortcode('solo_login_wc', 'solo_formulario_login_woocommerce');

// Redirigir después de iniciar sesión
function redirigir_despues_de_login($redirect, $user)
{
  // Si el usuario está logueado correctamente, redirige a la página 'Mi Cuenta'
  return home_url('/my-account/');
}
add_filter('woocommerce_login_redirect', 'redirigir_despues_de_login', 10, 2);

/************************************************************************************ */

//Este código crea un shortcode que muestra el formulario de registro de WooCommerce
// Shortcode personalizado para mostrar el formulario de registro
// Define una función personalizada que solo mostrará el formulario de registro de WooCommerce
function solo_formulario_registro_woocommerce()
{
  // Si el usuario ya ha iniciado sesión
  if (is_user_logged_in()) {
    // Obtiene el ID del usuario actual
    $user_id = get_current_user_id();

    // Obtiene la meta personalizada 'user_activation_token' asociada al usuario
    $activation_key = get_user_meta($user_id, 'user_activation_token', true);

    // Obtiene la clave de activación del núcleo de WordPress (usada por defecto en activaciones)
    $core_key = get_userdata($user_id)->user_activation_key;

    // Si no hay claves de activación pendientes, significa que ya está activado
    if (empty($activation_key) && empty($core_key)) {
      // Muestra un mensaje indicando que ya tiene una cuenta y debe cerrar sesión para registrar otra
      return '<p>Ya tienes cuenta. <br> Cierra sesión para crear una cuenta nueva</p>';
    }
  }

  // Inicia el almacenamiento en búfer para capturar la salida HTML
  ob_start();

  // Si existe la variable de sesión que indica registro exitoso
  if (!empty($_SESSION['registro_exitoso'])) {
    // Muestra un mensaje de éxito tras el registro
    echo '
      <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>✅ Registro exitoso:</strong> Tu cuenta se ha creado correctamente. <br>
        Consulta tu correo electrónico para activar la cuenta.
      </div>';

    // Elimina la variable de sesión para que el mensaje no vuelva a mostrarse en el futuro
    unset($_SESSION['registro_exitoso']);
  }

  // Forzamos que la vista activa sea "register" (registro), no login
  $_GET['view'] = 'register';

  // Carga la plantilla de login/registro de WooCommerce
  // Esto mostrará solo el formulario de registro porque hemos forzado $_GET['view'] arriba
  wc_get_template('myaccount/form-login.php');

  // Devuelve todo el contenido generado en el búfer y lo limpia
  return ob_get_clean();
}

// Registra un shortcode para insertar este formulario donde se desee usando: [solo_register_wc]
add_shortcode('solo_register_wc', 'solo_formulario_registro_woocommerce');

// Desactiva el inicio de sesión automático tras el registro en WooCommerce
// Esto es útil si se requiere que el usuario active su cuenta antes de iniciar sesión
add_filter('woocommerce_registration_auth_new_customer', '__return_false');


/*******************************************************************************************************/

// ✅ Generar token y enviar email de activación tras el registro
add_action('user_register', 'generar_token_y_enviar_email_activacion', 20, 1);
function generar_token_y_enviar_email_activacion($user_id) {
    $user = get_userdata($user_id);

    // Generar token aleatorio
    $token = bin2hex(random_bytes(16));

    // Guardar el token en el meta del usuario
    update_user_meta($user_id, 'user_activation_token', $token);

    // Generar la URL de activación (ajusta el slug si tu página se llama diferente)
    $activation_url = add_query_arg([
        'user_id' => $user_id,
        'token' => $token
    ], home_url('/confirmar-registro')); // 👈 asegúrate que este es el slug correcto

    // Asunto y cuerpo del email
    $subject = 'Activa tu cuenta en Tapas Me The Plate';
    $message = "Hola {$user->display_name},\n\n";
    $message .= "Gracias por registrarte. Por favor, haz clic en el siguiente enlace para activar tu cuenta:\n\n";
    $message .= $activation_url . "\n\n";
    $message .= "Si no solicitaste esta cuenta, puedes ignorar este mensaje.";

    // Enviar correo
    wp_mail($user->user_email, $subject, $message);
}

// Desactivar el correo de nueva cuenta de WooCommerce
add_filter('woocommerce_email_enabled_customer_new_account', '__return_false');

/********************************************* */
// Bloquear login si el usuario no ha activado su cuenta
// Prioridad: 30 (se ejecuta después de la autenticación básica de usuario y contraseña)
// Acepta 3 argumentos: $user (objeto WP_User o WP_Error), $username y $password
add_filter('authenticate', 'bloquear_login_si_no_activado', 30, 3);

// Define la función que se ejecutará en el filtro de autenticación
function bloquear_login_si_no_activado($user, $username, $password) {

    // Si ya hay un error (por ejemplo, usuario incorrecto o contraseña fallida), no hacemos nada
    if (is_wp_error($user)) {
        return $user;
    }

    // Si no existe un ID de usuario válido, salimos (caso muy raro, pero es una validación extra)
    if (!isset($user->ID)) {
        return $user;
    }

    // Recupera el token de activación personalizado del usuario desde sus metadatos
    // Si existe este token, significa que la cuenta aún no ha sido activada
    $token = get_user_meta($user->ID, 'user_activation_token', true);

    // Si el token de activación no está vacío, la cuenta no ha sido activada
    if (!empty($token)) {
        // Impide el login devolviendo un error personalizado
        return new WP_Error('account_not_activated', __('<strong>Error:</strong> Tu cuenta aún no ha sido activada. Revisa tu correo.'));
    }

    // Si todo está bien (no hay token), se permite el login normalmente
    return $user;
}

// Mostrar el mensaje de error personalizado en el login
add_filter('login_errors', 'mostrar_error_activacion_cuenta');
function mostrar_error_activacion_cuenta($error) {
    if (strpos($error, 'account_not_activated') !== false) {
        return '<strong>❌ Cuenta no activada:</strong> Debes activar tu cuenta antes de iniciar sesión. Consulta tu email.';
    }
    return $error;
}

/********************************************************************************************** */
// Cambiar el nombre y el correo del remitente por defecto
add_filter('wp_mail_from', function ($original_email_address) {
  return 'info@tapasmetheplate.com';
});

add_filter('wp_mail_from_name', function ($original_email_from) {
  return 'Tapas Me The Plate';
});

/************************************************************************************************/
//CUANDO USUARIO CIERRA SESION REDIRIGE A HOME
add_action('wp_logout', 'custom_redirect_after_logout');

function custom_redirect_after_logout()
{
  wp_redirect(home_url()); // Redirige a la página principal
  exit();
}

/**********************************************************************************************/

// Persistir carrito después de cerrar sesión
function custom_preserve_cart_after_logout()
{
  if (is_user_logged_in()) {
    return;
  }

  // Obtener los datos del carrito
  $cart = WC()->cart->get_cart();

  // Guardar el carrito en una cookie
  setcookie('user_cart', serialize($cart), time() + 3600 * 24 * 30, COOKIEPATH, COOKIE_DOMAIN); // 30 días
}
add_action('wp_logout', 'custom_preserve_cart_after_logout');

// Restaurar el carrito cuando el usuario inicie sesión

/*********************************************************** */
add_action('wp_ajax_resend_order_email', 'reenviar_email_confirmacion_pedido');
add_action('wp_ajax_nopriv_resend_order_email', 'reenviar_email_confirmacion_pedido');

function reenviar_email_confirmacion_pedido()
{
  if (!isset($_GET['order_id']) || !isset($_GET['_wpnonce'])) {
    wp_die('Petición inválida.');
  }

  $order_id = absint($_GET['order_id']);

  if (!wp_verify_nonce($_GET['_wpnonce'], 'resend_order_email_' . $order_id)) {
    wp_die('Nonce inválido.');
  }

  $order = wc_get_order($order_id);
  if (!$order) {
    wp_die('Pedido no encontrado.');
  }

  // Reenviar el email de confirmación
  WC()->mailer()->emails['WC_Email_Customer_Processing_Order']->trigger($order_id);

  echo 'ok';
  wp_die();
}
/***************************************** */

/* ELIMINACION DE CUENTA */
// ✅ REGISTRO DEL ENDPOINT PERSONALIZADO "eliminar-cuenta"
/*
add_action('init', function () {
  add_rewrite_endpoint('eliminar-cuenta', EP_ROOT | EP_PAGES);
});

add_filter('query_vars', function ($vars) {
  $vars[] = 'eliminar-cuenta';
  return $vars;
});

add_filter('woocommerce_account_menu_items', function ($items) {
  $items['eliminar-cuenta'] = 'Eliminar cuenta';
  return $items;
});

add_action('woocommerce_account_eliminar-cuenta_endpoint', 'mostrar_formulario_eliminar_cuenta');
*/

// ✅ FUNCIÓN PARA ELIMINAR CONTENIDO ASOCIADO AL USUARIO
function eliminar_contenido_asociado_usuario($user_id)
{
  // Eliminar pedidos WooCommerce
  $pedidos = get_posts([
    'post_type' => 'shop_order',
    'post_status' => 'any',
    'numberposts' => -1,
    'meta_key' => '_customer_user',
    'meta_value' => $user_id,
  ]);
  foreach ($pedidos as $pedido) {
    wp_delete_post($pedido->ID, true);
  }

  // Eliminar reservas (CPT)
  $reservas = get_posts([
    'post_type' => 'reservas',
    'post_status' => 'any',
    'numberposts' => -1,
    'meta_key' => 'user_id',
    'meta_value' => $user_id,
  ]);
  foreach ($reservas as $reserva) {
    wp_delete_post($reserva->ID, true);
  }

  // Eliminar reservas de tabla personalizada
  global $wpdb;
  $wpdb->delete('wp_reservas_reservas', ['user_id' => $user_id]);
}

// ✅ FORMULARIO Y LÓGICA DE ELIMINACIÓN
function mostrar_formulario_eliminar_cuenta()
{
  if (!is_user_logged_in()) {
    echo '<p>No has iniciado sesión.</p>';
    return;
  }

  $user_id = get_current_user_id();
  $user = wp_get_current_user();

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $password = $_POST['password'] ?? '';

    if (wp_check_password($password, $user->user_pass, $user_id)) {

      // 🔹 Eliminar contenido asociado (reservas, pedidos, tabla personalizada)
      eliminar_contenido_asociado_usuario($user_id);

      // 🔹 Cargar funciones necesarias y eliminar usuario ANTES de cerrar sesión
      require_once ABSPATH . 'wp-admin/includes/user.php';

      $resultado = wp_delete_user($user_id);

      if ($resultado) {
        error_log("✅ Usuario $user_id eliminado correctamente.");
        set_transient('cuenta_eliminada_' . $user_id, true, 30);
        set_transient('flash_mensaje_cuenta_eliminada', true, 30);
        wp_redirect(home_url());
        exit;
      } else {
        error_log("❌ Fallo al eliminar usuario $user_id con wp_delete_user(). Ejecutando SQL de respaldo.");

        // Eliminar manualmente (solo si falla)
        global $wpdb;
        $wpdb->delete($wpdb->users, ['ID' => $user_id]);
        $wpdb->delete($wpdb->usermeta, ['user_id' => $user_id]);

        set_transient('cuenta_eliminada_' . $user_id, true, 30);
        wp_redirect(home_url('/'));
        exit;
      }

    } else {
      echo '<div style="color:red;">❌ Contraseña incorrecta. No se eliminó la cuenta.</div>';
    }
  }

  // Paso 1: Confirmación
  if (!isset($_POST['confirm_step'])) {
    ?>
    <h2>¿Estás seguro de que quieres eliminar tu cuenta?</h2>
    <form method="post">
      <button type="submit" name="confirm_step" class="button" style="background-color: red; color: white;">
        Sí, quiero eliminarla
      </button>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="button">No</a>
    </form>
    <?php
    return;
  }

  // Paso 2: Pedir contraseña
  ?>
  <h2>Introduce tu contraseña para confirmar</h2>
<form method="post" id="form-eliminar-cuenta">
  <p>
    <label for="password">Contraseña actual:</label><br>
    <input type="password" name="password" required>
  </p>
  <button type="submit" name="confirm_delete" class="button" style="background-color: red; color: white;">
    Eliminar mi cuenta definitivamente
  </button>
</form>

<script>
  // Redirección inmediata al enviar el formulario
  document.getElementById('form-eliminar-cuenta').addEventListener('submit', function (e) {
    setTimeout(function () {
      window.location.href = "<?php echo esc_url(home_url('/')); ?>";
    }, 1000); // Espera 1 segundo (permite ejecutar PHP)
  });
</script>

  <?php
}

/********************************************************************************* */

