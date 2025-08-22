<?php
get_header();  // Incluye el encabezado del tema

// Asegúrate de que el post sea del tipo 'reservas'
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        
        // Obtener el ID de la reserva desde los metadatos del post
        $reserva_id = get_post_meta(get_the_ID(), 'reserva_id', true);
        
        if ($reserva_id) {
            // Realiza la consulta para obtener los datos de la reserva desde la tabla 'reservas_reservas'
            global $wpdb;
            $reserva = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}reservas_reservas WHERE id = %d", $reserva_id);

            if ($reserva) {
                // Muestra los detalles de la reserva
                echo '<h1>Detalles de la Reserva</h1>';
                echo '<p><strong>Nombre:</strong> ' . esc_html($reserva->nombre) . '</p>';
                echo '<p><strong>Apellidos:</strong> ' . esc_html($reserva->apellidos) . '</p>';
                echo '<p><strong>Email:</strong> ' . esc_html($reserva->email) . '</p>';
                echo '<p><strong>Fecha de Reserva:</strong> ' . esc_html($reserva->fecha_reserva) . '</p>';
                echo '<p><strong>Hora de Reserva:</strong> ' . esc_html($reserva->hora_reserva) . '</p>';
                echo '<p><strong>Número de Comensales:</strong> ' . esc_html($reserva->num_comensales) . '</p>';
                echo '<p><strong>Ubicación:</strong> ' . esc_html($reserva->ubicacion) . '</p>';
            } else {
                echo '<p>No se encontraron detalles para esta reserva.</p>';
            }
        } else {
            echo '<p>No se pudo obtener la ID de la reserva.</p>';
        }

    endwhile;
endif;

get_footer();  // Incluye el pie de página del tema
?>
