<style>
    /* Estilo para el contenedor del mensaje de confirmación */
.confirmation-message {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 30px auto;
    text-align: center;
    font-family: 'Arial', sans-serif;
}

/* Estilo para el texto del mensaje */
.confirmation-message p {
    font-size: 18px;
    color: #333;
    line-height: 1.5;
    margin-bottom: 20px;
}

/* Estilo para el botón de cancelación */
#cancel-button {
    background-color: #dc3545;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold; /* Fuente en negrita */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#cancel-button:hover {
    background-color: #c82333;
}

/* Estilo para el botón "No, volver a la reserva" */
#close-button {
    background-color: #f8f9fa; /* Color de fondo gris claro */
    color: #495057; /* Color de texto gris oscuro */
    border: 1px solid #ced4da; /* Borde gris suave */
    padding: 10px 20px; /* Espaciado alrededor del texto */
    font-size: 16px; /* Tamaño de fuente */
    font-weight: bold; /* Fuente en negrita */
    cursor: pointer; /* Cambiar el cursor al pasar sobre el botón */
    border-radius: 5px; /* Bordes redondeados */
    transition: all 0.3s ease; /* Efecto de transición suave */
}

/* Estilo cuando el botón es hover (pasar el ratón por encima) */
#close-button:hover {
    background-color: #e9ecef; /* Fondo ligeramente más oscuro */
    color: #007bff; /* Cambiar el color del texto a azul */
    border-color: #007bff; /* Cambiar el color del borde a azul */
}

/* Estilo cuando el botón es clickeado */
#close-button:active {
    background-color: #dae0e5; /* Fondo más oscuro cuando se hace clic */
    color: #0056b3; /* Color más oscuro del texto */
    border-color: #0056b3; /* Borde más oscuro */
}


/* Estilo para el enlace de vuelta */
.confirmation-message a {
    font-size: 16px;
    color: #007bff;
    text-decoration: none;
    margin-top: 10px;
    display: inline-block;
}

.confirmation-message a:hover {
    text-decoration: underline;
}

/* Estilo para el título de la ventana emergente SweetAlert */
.swal2-title {
    font-family: 'Arial', sans-serif;
    font-weight: bold;
    font-size: 20px;
}

/* Estilo para el texto de la ventana emergente SweetAlert */
.swal2-content {
    font-family: 'Arial', sans-serif;
    font-size: 16px;
}

/* Personalización de los botones SweetAlert */
.swal2-confirm {
    background-color: #dc3545 !important;
    color: white !important;
    border-radius: 5px;
    font-size: 16px;
}

.swal2-cancel {
    background-color: #6c757d !important;
    color: white !important;
    border-radius: 5px;
    font-size: 16px;
}

</style>
<?php
get_header();

// Asegúrate de que este archivo se cargue solo en la página correcta
if (!defined('ABSPATH')) {
    exit; // Evitar el acceso directo al archivo
}

// Verificar si se pasó el ID de la reserva en la URL
if (isset($_GET['id'])) {
    $reserva_id = intval($_GET['id']);

    // Conectar a la base de datos
    global $wpdb;

    // Obtener la reserva de la base de datos
    $reserva = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM wp_reservas_reservas WHERE id = %d",
            $reserva_id
        )
    );

    if ($reserva) {
        // Mostrar el mensaje de confirmación solo si no se ha confirmado
        if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
            // Eliminar la reserva de la base de datos
            $eliminado = $wpdb->delete('wp_reservas_reservas', ['id' => $reserva_id]);

            // Si la reserva se eliminó correctamente, eliminar el CPT
            if ($eliminado !== false) {
                $post_id = get_post_id_by_reserva_id($reserva_id);
                if ($post_id) {
                    wp_delete_post($post_id, true); // Eliminar el post asociado
                }

                echo '<div class="confirmation-message"><p>¡Tu reserva ha sido cancelada exitosamente!</p></div>';
            } else {
                echo '<div class="confirmation-message"><p>No se pudo cancelar la reserva. Por favor, intenta nuevamente más tarde.</p></div>';
            }
        } else {
            // Mostrar mensaje de confirmación de cancelación
            echo '<div class="confirmation-message"><p>Estás a punto de cancelar tu reserva. ¿Estás seguro?</p>';
            echo '<button id="cancel-button">Sí, cancelar reserva</button>  <button id="close-button">No, volver a la reserva</button></div>';
        }
    } else {
        echo '<div class="confirmation-message"><p>No se encontró la reserva que intentas cancelar.</p></div>';
    }
} else {
    echo '<div class="confirmation-message"><p>ID de reserva no válido.</p></div>';
}

// Función para obtener el ID del post CPT basado en la reserva
function get_post_id_by_reserva_id($reserva_id) {
    // Accedemos al objeto global de la base de datos de WordPress
    global $wpdb;

    // Ejecutamos una consulta para obtener el post_id que tenga como metadato 'reserva_id' con el valor proporcionado
    $post_id = $wpdb->get_var( // Recupera un único valor (la primera columna de la primera fila)
        $wpdb->prepare( // Prepara la consulta de forma segura para evitar inyecciones SQL
            "SELECT post_id FROM wp_postmeta WHERE meta_key = 'reserva_id' AND meta_value = %d",
            $reserva_id // %d representa un número entero (ID de la reserva)
        )
    );
    // Retornamos el ID del post encontrado (o null si no existe)
    return $post_id;
}
?>


<!-- Agregar el script para SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">
    document.getElementById('cancel-button').addEventListener('click', function(event) {
        // Evitar que el botón recargue la página al hacer clic
        event.preventDefault();

        // Mostrar la ventana emergente de confirmación con SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Una vez que se cancele, no podrás recuperar la reserva!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar reserva',
            cancelButtonText: 'No, volver atrás',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir a la misma URL con el parámetro de confirmación
                window.location.href = "?id=<?php echo $reserva_id; ?>&confirm=yes";
            }
        });
    });

     // Funcionalidad para el botón "No, volver a la reserva"
     document.getElementById('close-button').addEventListener('click', function(event) {
        // Cerrar la ventana actual
        window.close();
    });
</script>

<?php get_footer(); ?>
