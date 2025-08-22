<?php

/* Template Name: Página Mi Cuenta Personalizada */
get_header(); ?>

<style>
    
/* ------------------- ESTILOS GENERALES ------------------- */
.woocommerce-MyAccount-navigation {
    max-width: 100%;
    width: 100%;
}
.woocommerce-MyAccount-navigation ul {
    list-style-type: none; /* Elimina los puntos de la lista */
    padding: 0; /* Elimina el padding por defecto de la lista */
    margin: 0; /* Elimina el margen por defecto de la lista */
}

.woocommerce-MyAccount-navigation ul li {
    overflow: hidden; /* Evita que los elementos hijos se desborden */
    width: 100%; /* Asegura que el <li> no crezca más que su contenedor */
}

.woocommerce-MyAccount-navigation ul li a {
    display: block; /* Hace que el enlace ocupe todo el ancho del li */
    padding: 10px 15px; /* Añade padding para que parezca un botón */
    color: #fff; /* Texto blanco */
    text-decoration: none; /* Elimina el subrayado de los enlaces */
    width: 100%;
    box-sizing: border-box; /* Incluye el padding dentro del ancho */
    overflow: hidden;
}

.woocommerce-MyAccount-navigation ul li a:hover {
    background-color: rgba(253, 101, 8, 0.7); /* Cambia el color al pasar el ratón */
}

.woocommerce-MyAccount-navigation ul li.is-active a {
    background-color: rgba(230, 189, 146, 0.7); /* Fondo blanco para el enlace activo */
    color: #333; /* Texto oscuro para el enlace activo */
}

/* Estilos para los iconos de Feather */
.feather {
    width: 16px;
    height: 16px;
    vertical-align: text-bottom;
}


/* ------------------- ESTILOS BARRA LATERAL ------------------- */
.sidebar {
    padding: 0;
    position: fixed; /* Sigue siendo fijo en la pantalla */
    top: 80px;
    bottom: 0; /* Ocupa toda la altura */
    left: 0; /* Pegado a la izquierda */
    z-index: 100; /* Por encima de la mayoría del contenido */
}

/* Estilos para el contenido de WooCommerce */
.woocommerce-MyAccount-content {
    padding: 15px; /* Añade un poco de espacio alrededor del contenido */
}

/* ------------------- RESPONSIVE (MÓVILES) ------------------- */
/* Media query para pantallas pequeñas (móviles) */
@media (max-width: 767px) {
    .sidebar {
        display: none; /* Oculta el menú lateral en móviles */
    }

    .woocommerce-MyAccount-navigation {
        display: none; /* Por si acaso hay navegación en otro lugar */
    }

    main {
        margin-left: 0;
        margin-top: 10px;
    }
}


</style>

<div class="container-fluid">
    <div class="row">
        <!-- Menú lateral -->
        <nav class="col-12 col-md-2 bg-dark sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <?php
                    /**
                     * My Account navigation.
                     *
                     * @since 2.6.0
                     */
                    do_action( 'woocommerce_account_navigation' ); ?>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main role="main" class="col-md-10 ml-sm-auto col-lg-10 px-1">
            <div class="woocommerce">
                <div class="woocommerce-MyAccount-content">
                    <?php do_action( 'woocommerce_account_content' ); ?>
                </div>
            </div>
        </main>
    </div>
</div>


<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>


<?php get_footer('clean'); ?>
