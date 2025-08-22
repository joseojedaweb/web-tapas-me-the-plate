<?php
get_header();

if (!defined('ABSPATH')) {
    exit;
}

if (isset($_GET['id'])) {
    $reserva_id = intval($_GET['id']);
    global $wpdb;

    $reserva = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM wp_reservas_reservas WHERE id = %d",
            $reserva_id
        )
    );

    if ($reserva) {
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_modificar']) &&
            isset($_POST['nombre']) && !empty($_POST['nombre']) &&
            isset($_POST['apellidos']) && !empty($_POST['apellidos']) &&
            isset($_POST['email']) && !empty($_POST['email']) &&
            isset($_POST['telefono']) && !empty($_POST['telefono']) &&
            isset($_POST['fecha_reserva']) && !empty($_POST['fecha_reserva']) &&
            isset($_POST['hora_reserva']) && !empty($_POST['hora_reserva']) &&
            isset($_POST['num_comensales']) && !empty($_POST['num_comensales']) &&
            isset($_POST['ubicacion']) && !empty($_POST['ubicacion'])
        ) {
            // Sanitización
            $nombre = sanitize_text_field($_POST['nombre']);
            $apellidos = sanitize_text_field($_POST['apellidos']);
            $email = sanitize_email($_POST['email']);
            $telefono = sanitize_text_field($_POST['telefono']);
            $fecha_reserva = sanitize_text_field($_POST['fecha_reserva']);
            $hora_reserva = sanitize_text_field($_POST['hora_reserva']);
            $num_comensales = intval($_POST['num_comensales']);
            $ubicacion = sanitize_text_field($_POST['ubicacion']);

            // Validación de fecha y hora
            $reserva_datetime = strtotime($fecha_reserva . ' ' . $hora_reserva);
            $ahora = time();
            $hoy = strtotime(date('Y-m-d'));

            if ($reserva_datetime < $ahora) {
                echo '<p class="text-center" style="background: red; color: white;">❌ No puedes seleccionar una fecha u hora pasada.</p>';
            } elseif (strtotime($fecha_reserva) == $hoy && ($reserva_datetime - $ahora) < 10800) {
                echo '<p class="text-center" style="background: red; color: white;">⏰ Para hoy, las reservas deben hacerse al menos con 3 horas de antelación.</p>';
            } else {
                $wpdb->update(
                    'wp_reservas_reservas',
                    [
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'email' => $email,
                        'telefono' => $telefono,
                        'fecha_reserva' => $fecha_reserva,
                        'hora_reserva' => $hora_reserva,
                        'num_comensales' => $num_comensales,
                        'ubicacion' => $ubicacion,
                    ],
                    ['id' => $reserva_id]
                );
                
                // Actualizar el título del CPT asociado
                $post_cpt = get_posts([
                    'post_type' => 'reservas',
                    'meta_key' => 'reserva_id',
                    'meta_value' => $reserva_id,
                    'numberposts' => 1,
                ]);

                if (!empty($post_cpt)) {
                    $post_id = $post_cpt[0]->ID;
                    wp_update_post([
                        'ID' => $post_id,
                        'post_title' => 'Reserva de ' . $nombre . ' ' . $apellidos,
                    ]);
                }


                $asunto_cliente = 'Tu reserva ha sido modificada';
                //fecha formateada
                $reserva_fecha_formateada = date('d/m/Y', strtotime($fecha_reserva));

                $mensaje_cliente = "Hola {$nombre} {$apellidos},\n\nTu reserva ha sido actualizada:\n\nEmail: {$email}\nTeléfono: {$telefono}\nFecha: {$reserva_fecha_formateada}\nHora: {$hora_reserva}\nComensales: {$num_comensales}\nUbicación: {$ubicacion}\n\nSi quieres volver a modificarla registrate o inicia sesión para poder hacerlo";

                wp_mail($email, $asunto_cliente, $mensaje_cliente);

                $admin_email = get_option('admin_email');
                $asunto_admin = 'Una reserva ha sido modificada';
                $mensaje_admin = "Reserva modificada por {$nombre} {$apellidos}:\n\nEmail: {$email}\nTeléfono: {$telefono}\nFecha: {$reserva_fecha_formateada}\nHora: {$hora_reserva}\nComensales: {$num_comensales}\nUbicación: {$ubicacion}\nID: {$reserva_id}";

                wp_mail($admin_email, $asunto_admin, $mensaje_admin);

                echo '<p class="text-center" style="background: green; color:white;">¡Tu reserva se ha actualizado exitosamente!</p>';
            }
        }
        ?>

        <h1 id="h1_booking" class="text-center">Modificar Reserva</h1>
        <form method="post" id="booking-form">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo esc_attr($reserva->nombre); ?>" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" value="<?php echo esc_attr($reserva->apellidos); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo esc_attr($reserva->email); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" id="telefono" value="<?php echo esc_attr($reserva->telefono); ?>" required>

            <label for="fecha_reserva">Fecha de Reserva:</label>
            <input type="date" name="fecha_reserva" id="fecha_reserva" value="<?php echo esc_attr($reserva->fecha_reserva); ?>"
                required>

            <label for="hora_reserva">Hora de Reserva:</label>
            <input type="time" name="hora_reserva" id="hora_reserva" value="<?php echo esc_attr($reserva->hora_reserva); ?>"
                required>

            <label for="num_comensales">Número de Comensales:</label>
            <input type="number" name="num_comensales" id="num_comensales"
                value="<?php echo esc_attr($reserva->num_comensales); ?>" required>

            <label for="ubicacion">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" value="<?php echo esc_attr($reserva->ubicacion); ?>" required>

            <button type="submit" name="submit_modificar">Actualizar Reserva</button>
        </form>

        <?php
    } else {
        echo '<p>No se encontró la reserva.</p>';
    }
} else {
    echo '<p>ID de reserva no válido.</p>';
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputFecha = document.getElementById('fecha_reserva');
        const inputHora = document.getElementById('hora_reserva');

        // Bloquear fechas pasadas
        const today = new Date().toISOString().split('T')[0];
        inputFecha.setAttribute('min', today);

        // Validar hora al enviar
        document.getElementById('booking-form').addEventListener('submit', function (e) {
            const fecha = inputFecha.value;
            const hora = inputHora.value;

            if (!fecha || !hora) return;

            const now = new Date();
            const selectedDateTime = new Date(`${fecha}T${hora}`);

            if (selectedDateTime < now) {
                e.preventDefault();
                alert('❌ No puedes seleccionar una fecha u hora pasada.');
                return;
            }

            const isToday = selectedDateTime.toDateString() === now.toDateString();
            if (isToday) {
                const diffMs = selectedDateTime - now;
                const diffH = diffMs / (1000 * 60 * 60);

                if (diffH < 3) {
                    e.preventDefault();
                    alert('⏰ Para hoy, las reservas deben hacerse con al menos 3 horas de antelación.');
                }
            }
        });
    });
</script>

<?php get_footer(); ?>