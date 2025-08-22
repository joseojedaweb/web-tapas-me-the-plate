<?php
/**
 * Template Name: Confirmar Registro
 */

get_header();

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
$mensaje = '';

if ($user_id && $token) {
    $guardado = get_user_meta($user_id, 'user_activation_token', true);

    if ($guardado && $guardado === $token) {
        // Borrar token y permitir establecer nueva contraseña
        delete_user_meta($user_id, 'user_activation_token');

        $reset_key = get_password_reset_key(get_user_by('id', $user_id));
        $reset_url = wp_lostpassword_url() . '?key=' . $reset_key . '&login=' . urlencode(get_userdata($user_id)->user_login);

        $mensaje = '<p class="alert alert-success">Tu cuenta ha sido confirmada correctamente. <a href="' . esc_url($reset_url) . '">Haz clic aquí para establecer tu contraseña</a>.</p>';
    } else {
        $mensaje = '<p class="alert alert-danger">El enlace de confirmación no es válido o ya ha sido utilizado.</p>';
    }
} else {
    $mensaje = '<p class="alert alert-warning">Faltan datos para validar la cuenta.</p>';
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php echo $mensaje; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
