<?php
/* Template Name: Booking Page */
get_header(); // Incluye el encabezado del tema

global $wpdb; // Accedemos al objeto global $wpdb para hacer consultas a la base de datos de WordPress

$errores = []; // Array para almacenar errores de validación o del sistema
$mensaje_exito = ''; // Variable para almacenar mensaje de éxito al guardar la reserva

// Inicializamos las variables de los campos
$nombre = $apellidos = $email = $telefono = $ubicacion = '';

// Si el usuario está logueado, rellenamos automáticamente los datos con su información de facturación
if (is_user_logged_in()) {
    $user_id = get_current_user_id(); // Obtener el ID del usuario actual

    // Recuperamos los datos desde los metacampos del usuario
    $nombre = get_user_meta($user_id, 'billing_first_name', true);
    $apellidos = get_user_meta($user_id, 'billing_last_name', true);
    $email = get_userdata($user_id)->user_email;
    $telefono = get_user_meta($user_id, 'billing_phone', true);
    $ubicacion = get_user_meta($user_id, 'billing_address_1', true);
}

// Si se ha enviado el formulario por POST y el botón submit_booking fue presionado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {

    // Verificamos el campo nonce para prevenir ataques CSRF
    if (!isset($_POST['reserva_nonce']) || !wp_verify_nonce($_POST['reserva_nonce'], 'guardar_reserva')) {
        $errores[] = 'Verificación de seguridad fallida.';
    } else {
        // Limpiamos y asignamos las entradas del formulario
        $nombre = sanitize_text_field($_POST['nombre']);
        $apellidos = sanitize_text_field($_POST['apellidos']);
        $email = sanitize_email($_POST['email']);
        $telefono = sanitize_text_field($_POST['telefono']);
        $fecha_reserva = sanitize_text_field($_POST['fecha_reserva']);
        $hora_reserva = sanitize_text_field($_POST['hora_reserva']);
        $num_comensales = intval($_POST['num_comensales']);
        $ubicacion = sanitize_text_field($_POST['ubicacion']);

        // Validaciones básicas
        if (empty($nombre))
            $errores[] = 'You must enter a name.';
        if (empty($apellidos))
            $errores[] = 'You must enter the last name.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errores[] = 'Invalid email.';
        if (empty($fecha_reserva))
            $errores[] = 'You must enter a date.';
        if (empty($hora_reserva))
            $errores[] = 'You must enter a time.';
        if ($num_comensales <= 0)
            $errores[] = 'The number of diners must be greater than zero.';
        if (empty($ubicacion))
            $errores[] = 'You must enter a location.';

        // Validación de fecha y hora
        $reserva_timestamp = strtotime($fecha_reserva . ' ' . $hora_reserva);
        $ahora = current_time('timestamp');

        if ($reserva_timestamp < $ahora) {
            $errores[] = '❌ You cannot book for a past date and time.';
        } else {
            // Validar si es hoy y faltan menos de 3 horas
            $fecha_actual = date('Y-m-d', $ahora);
            if ($fecha_reserva === $fecha_actual) {
                $diferencia_segundos = $reserva_timestamp - $ahora;
                if ($diferencia_segundos < 3 * 3600) {
                    $errores[] = '⏰ For today, bookings must be made at least 3 hours in advance.';
                }
            }
        }

        // Si no hay errores, insertamos la reserva en la base de datos personalizada
        if (empty($errores)) {
            $user_id = is_user_logged_in() ? get_current_user_id() : null; // Guardamos el user_id si está logueado

            $resultado = $wpdb->insert( // Insertar los datos en la tabla personalizada wp_reservas_reservas
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
                    'user_id' => $user_id,
                ]
            );

            if ($resultado !== false) { // Si la inserción fue exitosa
                $reserva_id = $wpdb->insert_id; // Obtenemos el ID de la reserva recién creada

                // Creamos una entrada en el Custom Post Type 'reservas'
                $post_id = wp_insert_post([
                    'post_title' => 'Reserva de ' . $nombre . ' ' . $apellidos,
                    'post_type' => 'reservas',
                    'post_status' => 'publish',
                ]);

                if ($post_id) { // Si el post fue creado correctamente
                    // Guardamos el ID de la reserva como metadato
                    update_post_meta($post_id, 'reserva_id', $reserva_id);
                    update_post_meta($post_id, 'telefono', $telefono);

                    $mensaje_exito = 'Booking made successfully, Thank you for your booking!'; // Mensaje de éxito

                    // Preparamos el contenido del email para el cliente
                    $reserva_fecha_formateada = date('d/m/Y', strtotime($fecha_reserva));

                    $asunto = 'Booking Confirmation at our Tapas Catering Me The Plates';
                    $cuerpo = "
                     ¡Hello $nombre $apellidos!

                     Thank you for making a reservation with our catering service. Here are your reservation details:

                     Name: $nombre $apellidos
                     Email: $email
                     Phone: $telefono
                     Reservation Date: $reserva_fecha_formateada
                     Reservation Time: $hora_reserva
                     Number of Guests: $num_comensales
                     Location: $ubicacion

                     If you need to modify or cancel your reservation, you can do so through the following links:

                     Modify Reservation: <a href='https://tapasmetheplate.com/newversion/modify-booking?id=$reserva_id'>Modify</a>
                     Cancel Reservation: <a href='https://tapasmetheplate.com/newversion/cancel-booking?id=$reserva_id'>Cancel</a>

                     We'll make sure everything is ready for your arrival. We hope to see you soon!

                     Best regards,
                     The Tapas Me The Plates Catering Team
                    ";

                    // Encabezados del correo
                    $headers = [
                        'Content-Type: text/plain; charset=UTF-8',
                        'From: Catering Nombre <info@tapasmetheplate.com>'
                    ];

                    // Enviamos el correo al cliente
                    wp_mail($email, $asunto, $cuerpo, $headers);

                    // También enviamos una notificación al administrador
                    $admin_email = get_option('admin_email');
                    $asunto_admin = '📥 Nueva reserva en tu negocio de catering';
                    $cuerpo_admin = "Hola,\n\nHas recibido una nueva reserva desde la web.\n\nDetalles:\n" .
                        "Nombre: $nombre $apellidos\n" .
                        "Email: $email\n" .
                        "Teléfono: $telefono\n" .
                        "Fecha de reserva: $reserva_fecha_formateada\n" .
                        "Hora: $hora_reserva\n" .
                        "Número de comensales: $num_comensales\n" .
                        "Ubicación: $ubicacion\n\n" .
                        "Puedes gestionarla desde el panel de administración:\n" .
                        admin_url('edit.php?post_type=reservas') . "\n\n" .
                        "— Tapas Me The Plate";

                    wp_mail($admin_email, $asunto_admin, $cuerpo_admin, $headers); // Enviar correo al admin
                } else {
                    $errores[] = 'The entry for the reservation could not be created.'; // Error al crear el post
                }
            } else {
                $errores[] = 'An error occurred while saving the reservation to the database.'; // Error en la base de datos
            }
        }
    }
}
?>


<div class="booking-main">

    <h2 class="title text-center">Bookings</h2>
    <div class="section-booking">
        <div class="col-md-6 text-center ml-auto mr-auto">
            <p class="p-descripcionSecciones"><span class="resaltar-span-descripcion">Secure the Date for Your
                    Exceptional
                    Event.</span> The first step to ensuring the excellence of our high cuisine is the star of your
                celebration.
            </p>
            <p class="p-descripcionSecciones"><span style="font-weight: bold;">Let's Begin Creating Something
                    Unique.</span>
                Your
                vision and our expertise unite here to design the perfect gastronomic experience for your next event.
            </p>
        </div>
        <?php if (!empty($errores)): ?>
            <!-- Si hay errores almacenados en el array $errores, se muestra este bloque -->
            <div
                style="color: red; background: #ffeaea; padding: 10px; border: 1px solid #cc0000; max-width: 600px; margin: 0 auto 20px;">
                <!-- Contenedor con estilo rojo para mostrar errores de validación -->

                <?php foreach ($errores as $error): ?>
                    <!-- Recorre cada error en el array $errores -->

                    <p><?php echo esc_html($error); ?></p>
                    <!-- Muestra el error escapado para evitar problemas de seguridad XSS -->

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- Fin del bloque de errores -->


        <?php if ($mensaje_exito): ?>
            <!-- Si existe un mensaje de éxito, se muestra este bloque -->
            <p
                style="color: green; background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; max-width: 600px; margin: 0 auto 20px;">
                <?php echo esc_html($mensaje_exito); ?>
                <!-- Muestra el mensaje de éxito escapado -->
            </p>
        <?php endif; ?>
        <!-- Fin del bloque de mensaje de éxito -->



        <div id="booking-steps" class="section-booking">

            <!-- Paso 1: Selección de fecha -->

            <style>
                /* Aquí irá el CSS del calendario (abreviado para no duplicar aquí) */
                /* Puedes copiar el bloque entero de estilos que me diste y pegarlo en style.css o directamente aquí */
            </style>

            <!-- CALENDARIO PERSONALIZADO -->
            <div class="calendar" id="custom-calendar">
                <!-- Opciones de mes y año -->
                <div class="calendar__opts">
                    <select name="calendar__month" id="calendar__month">
                        <option>Jan</option>
                        <option>Feb</option>
                        <option>Mar</option>
                        <option>Apr</option>
                        <option selected>May</option>
                        <option>Jun</option>
                        <option>Jul</option>
                        <option>Aug</option>
                        <option>Sep</option>
                        <option>Oct</option>
                        <option>Nov</option>
                        <option>Dec</option>
                    </select>

                    <?php
                    // Obtener el año actual (por ejemplo: 2025)
                    $current_year = date('Y');
                    // Calcular el año siguiente (por ejemplo: 2026)
                    $next_year = $current_year + 1;
                    ?>
                    <!-- Selector desplegable de años -->
                    <select name="calendar__year" id="calendar__year">
                        <!-- Opción para el año actual, seleccionada por defecto -->
                        <option value="<?php echo $current_year; ?>" selected>
                            <?php echo $current_year; ?>
                        </option>
                        <!-- Opción para el año siguiente -->
                        <option value="<?php echo $next_year; ?>">
                            <?php echo $next_year; ?>
                        </option>
                    </select>
                </div>

                <!-- Días de la semana -->
                <div class="calendar__body">
                    <div class="calendar__days">
                        <div>M</div>
                        <div>T</div>
                        <div>W</div>
                        <div>T</div>
                        <div>F</div>
                        <div>S</div>
                        <div>S</div>
                    </div>

                    <!-- Fechas -->
                    <div class="calendar__dates">
                        <!-- Las fechas se generarían dinámicamente con JS, aquí hay contenido de muestra -->
                        <div class="calendar__date calendar__date--grey"><span>27</span></div>
                        <div class="calendar__date calendar__date--grey"><span>28</span></div>
                        <div class="calendar__date"><span>1</span></div>
                        <div class="calendar__date"><span>2</span></div>
                        <div class="calendar__date"><span>3</span></div>
                    </div>
                </div>

            </div>

            <script>
                // Mostrar paso 2 automáticamente cuando se pulse "Apply" en el calendario
                document.getElementById('calendar-apply').addEventListener('click', function () {
                    document.getElementById('step-1').style.display = 'none';
                    document.getElementById('step-2').style.display = 'block';
                });
            </script>

            <div id="step-1" class="booking-step">

            </div>

            <!-- Paso 2: Selección de hora y comensales -->
            <div id="step-2" class="booking-step" style="display:none;">
                <form id="booking-form">
                    <h2>Select time and number of guests</h2>

                    <div class="step2-inline-fields">
                        <div class="field">
                            <label for="hora_reserva_h">Hour:</label>
                            <select id="hora_reserva_h" name="hora_reserva_h">
                                <?php
                                // Bucle que genera opciones para seleccionar horas entre las 12 y las 23 (formato 24h)
                                for ($i = 12; $i <= 23; $i++):
                                    ?>
                                    <!-- Cada opción representa una hora -->
                                    <option value="<?php echo sprintf('%02d', $i); ?>">
                                        <?php echo sprintf('%02d', $i); ?>
                                        <!-- Muestra el número de la hora con dos dígitos (ej. 12, 13, ..., 23) -->
                                    </option>

                                <?php endfor; ?>

                            </select>
                        </div>

                        <div class="field">
                            <label for="hora_reserva_m">Minutes:</label>
                            <select id="hora_reserva_m" name="hora_reserva_m">
                                <?php
                                // Bucle que genera opciones de minutos en intervalos de 5 (0, 5, 10, ..., 55)
                                for ($i = 0; $i <= 55; $i += 5):
                                    ?>
                                    <!-- Cada opción representa un valor de minutos -->
                                    <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>">
                                        <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                        <!-- Muestra los minutos con dos dígitos (ej. 00, 05, 10, ..., 55) -->
                                    </option>

                                <?php endfor; ?>

                            </select>
                        </div>

                        <div class="field">
                            <label for="num_comensales">Guests:</label>
                            <select id="num_comensales" name="num_comensales">
                                <?php
                                // Bucle que genera opciones numéricas del 1 al 20 (para seleccionar número de comensales, por ejemplo)
                                for ($i = 1; $i <= 20; $i++):
                                    ?>
                                    <!-- Cada opción representa un número de personas -->
                                    <option value="<?php echo $i; ?>">
                                        <?php echo $i; ?> <!-- Muestra el número actual en pantalla -->
                                    </option>

                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <button type="button" id="to-step-3" class="booking-next-button">Next</button>
                </form>
            </div>


            <!-- Paso 3: Datos personales -->
            <div id="step-3" class="booking-step" style="display:none;">
                <h2 class="text-center">Personal Details</h2>
                <form method="post" id="booking-form">
                    <?php wp_nonce_field('guardar_reserva', 'reserva_nonce'); ?>
                    <input type="hidden" name="fecha_reserva" id="input_fecha" required>
                    <input type="hidden" name="hora_reserva" id="input_hora" required>
                    <input type="hidden" name="num_comensales" id="input_comensales" required>

                    <input type="text" name="nombre" placeholder="First Name" value="<?php echo esc_attr($nombre); ?>"
                        required>
                    <input type="text" name="apellidos" placeholder="Last Name"
                        value="<?php echo esc_attr($apellidos); ?>" required>
                    <input type="email" name="email" placeholder="Email" value="<?php echo esc_attr($email); ?>"
                        required>
                    <input type="tel" name="telefono" placeholder="Phone" value="<?php echo esc_attr($telefono); ?>"
                        required>
                    <input type="text" name="ubicacion" placeholder="Location" required>

                    <button type="submit" name="submit_booking">Submit Reservation</button>
                </form>
            </div>
        </div>

        <script>
            // Inicialización del calendario
            function generateCalendar(year, month) {
                const datesContainer = document.querySelector('.calendar__dates');
                datesContainer.innerHTML = '';

                const firstDay = new Date(year, month, 1);
                const lastDate = new Date(year, month + 1, 0).getDate();
                const startDay = (firstDay.getDay() + 6) % 7;

                for (let i = 0; i < startDay; i++) {
                    const greyCell = document.createElement('div');
                    greyCell.classList.add('calendar__date', 'calendar__date--grey');
                    greyCell.innerHTML = '<span></span>';
                    datesContainer.appendChild(greyCell);
                }

                for (let day = 1; day <= lastDate; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.classList.add('calendar__date');

                    const today = new Date();
                    const selectedDate = new Date(year, month, day);
                    if (selectedDate < today.setHours(0, 0, 0, 0)) {
                        dayCell.classList.add('calendar__date--grey');
                    }

                    dayCell.innerHTML = '<span>' + day + '</span>';
                    dayCell.dataset.date = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');

                    dayCell.addEventListener('click', function () {
                        if (this.classList.contains('calendar__date--grey')) return;

                        document.querySelectorAll('.calendar__date').forEach(el => el.classList.remove('calendar__date--selected'));
                        this.classList.add('calendar__date--selected');

                        selectedReservationDate = this.dataset.date;
                        document.getElementById('input_fecha').value = selectedReservationDate;
                        document.getElementById('step-2').style.display = 'block';
                    });

                    datesContainer.appendChild(dayCell);
                }
            }

            let selectedReservationDate = null;

            document.getElementById('calendar__month').addEventListener('change', function () {
                const month = this.selectedIndex;
                const year = parseInt(document.getElementById('calendar__year').value);
                generateCalendar(year, month);
            });

            document.getElementById('calendar__year').addEventListener('change', function () {
                const month = document.getElementById('calendar__month').selectedIndex;
                const year = parseInt(this.value);
                generateCalendar(year, month);
            });

            document.addEventListener('DOMContentLoaded', function () {
                const currentDate = new Date();
                document.getElementById('calendar__month').selectedIndex = currentDate.getMonth();

                const yearOptions = document.getElementById('calendar__year').options;
                for (let i = 0; i < yearOptions.length; i++) {
                    if (parseInt(yearOptions[i].value) === currentDate.getFullYear()) {
                        yearOptions[i].selected = true;
                        break;
                    }
                }
                generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            });

            // Validación del paso 2 (hora y comensales)
            document.getElementById('to-step-3').addEventListener('click', function () {
                const date = document.getElementById('input_fecha').value;
                const hour = document.getElementById('hora_reserva_h').value;
                const min = document.getElementById('hora_reserva_m').value;
                const comensales = document.getElementById('num_comensales').value;

                if (!date || !hour || !min || !comensales) {
                    alert('Please complete all fields.');
                    return;
                }

                const now = new Date();
                const reservaDate = new Date(date + 'T' + hour + ':' + min);

                const esHoy = reservaDate.toDateString() === now.toDateString();

                if (esHoy) {
                    const diffMs = reservaDate - now;
                    const diffH = diffMs / (1000 * 60 * 60);

                    if (diffH < 0) {
                        alert('⚠️ You cannot select a past hour for today.');
                        return;
                    }

                    if (diffH < 3) {
                        alert('⚠️ Reservations for today must be made at least 3 hours in advance.');
                        return;
                    }
                }

                if (reservaDate < now) {
                    alert('⚠️ You cannot select a past date and time.');
                    return;
                }

                document.getElementById('input_fecha').value = date;
                document.getElementById('input_hora').value = hour + ':' + min;
                document.getElementById('input_comensales').value = comensales;

                document.getElementById('custom-calendar').style.display = 'none';
                document.getElementById('step-2').style.display = 'none';
                document.getElementById('step-3').style.display = 'block';
            });
        </script>
    </div>
</div>

<?php get_footer(); ?>