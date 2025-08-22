<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76"
        href="<?php echo get_template_directory_uri(); ?>/assets/img/apple-icon.png">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/logo/favicon_negro_redimensiondo.jpg"
        type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="robots" content="index, follow">
    <meta name="description" content="Tapas Me The Plate - High-end Mediterranean catering service.">
    <meta name="author" content=" Multiplika | Codemartia SL">
    <title>
        Tapas Me The Plate
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200|Open+Sans+Condensed:700"
        rel="stylesheet">-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Square+Peg&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/now-ui-kit.css?v=1.3.1" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/assets/demo/demo.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/header-footer.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <!--fuente google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    

    <?php wp_head(); ?> <!--importante para que wordpress y los plugin funcionen-->
</head>

<body>
    <nav class="site-header navbar navbar-expand-lg navbar-light" id="navbar_top"
        data-is-front-page="<?php echo is_front_page() ? 'true' : 'false'; ?>">
        <div class="container">

            <!--esc_url limpia la URL para que sea segura antes de imprimirla en el HTML-->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="d-inline d-lg-none mobile-logo-outside-collapse">

                <!--carga archivos de forma dinamica-->
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo/logo-nobackground-black2.png"
                    class="logo responsive-logo img-fluid w-75 w-sm-50 w-lg-auto"
                    alt="luxury mediterranean soul catering mobile">
            </a>

            <div class="navbar-translate">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                    aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar top-bar"></span>
                    <span class="navbar-toggler-bar middle-bar"></span>
                    <span class="navbar-toggler-bar bottom-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" data-nav-image="./assets/img//blurred-image-1.jpg" data-color="orange"
                id="navigation">
                <div class="d-flex flex-column flex-lg-row w-100 align-items-center">
                    <!-- Columna izquierda -->
                    <div class="w-100 w-lg-33 text-center order-2 order-lg-1">
                        <ul class="navbar-nav flex-column flex-lg-row justify-content-center">
                            <li class="nav-item style-enlaces-nav <?php if (is_page('ours-services'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/ours-services/')); ?>">OUR
                                    SERVICES</a> <!-- esc_url() para que la dirección sea segura y valida -->
                            </li>
                            <li class="nav-item style-enlaces-nav <?php if (is_page('menu'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/menu/')); ?>">PLATES</a>
                            </li>
                            <li class="nav-item style-enlaces-nav <?php if (is_page('about-us'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/about-us/')); ?>">ABOUT US</a>
                            </li>
                            <li class="nav-item style-enlaces-nav <?php if (is_page('reviews'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/reviews/')); ?>">REVIEWS</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Logotipo en el centro -->
                    <div class="w-100 w-lg-33 text-center my-3 my-lg-0 order-1 order-lg-2">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="d-none d-lg-inline">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo/logo-nobackground-black2.png"
                                class="logo responsive-logo img-fluid" alt="luxury mediterranean soul catering">
                        </a>

                        <a href="<?php echo esc_url(home_url('/')); ?>" class="d-inline d-lg-none">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo/logo-nobackground-black2.png"
                                class="logo responsive-logo img-fluid w-75 w-sm-50 w-lg-auto"
                                alt="luxury mediterranean soul catering mobile">
                        </a>
                    </div>

                    <!-- Columna derecha -->
                    <div class="w-100 w-lg-33 text-center order-3 order-lg-3">
                        <ul class="navbar-nav flex-column flex-lg-row justify-content-center">
                            <li class="nav-item style-enlaces-nav <?php if (is_page('online-shop') || is_shop())
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/online-shop/')); ?>">SHOP</a>
                            </li>
                            <li class="nav-item style-enlaces-nav <?php if (is_page('blog'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/blog/')); ?>">BLOG</a>
                            </li>
                            <li class="nav-item style-enlaces-nav <?php if (is_page('contact-us'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/contact-us/')); ?>">CONTACT
                                    US</a>
                            </li>
                            <li class="nav-item style-enlaces-nav <?php if (is_page('booking'))
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php echo esc_url(home_url('/booking/')); ?>">BOOKING</a>
                            </li>

                            <li class="nav-item style-enlaces-nav <?php if (is_page('login'))
                                echo 'active'; ?> dropdown mt-5 mt-lg-0 list-unstyled">
                                <?php if (is_user_logged_in()): ?> <!-- verifica si alguien ha iniciado sesion-->
                                    <a class="area-privada-btn nav-link text-nowrap" href="#" id="userDropdown"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-user me-2"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <!-- si estoy en la pagina principal de 'Mi cuenta' pero no en ninguna de sus subsecciones -->
                                        <a class="dropdown-item <?php if (is_account_page() && !is_wc_endpoint_url())
                                            echo 'active'; ?>"
                                            href="<?php echo esc_url(home_url('/my-account/')); ?>">My Account</a>
                                        <a class="dropdown-item <?php if (is_wc_endpoint_url('orders')) //comprueba si estás viendo una sección específica de la cuenta del usuario
                                                    echo 'active'; ?>"
                                            href="<?php echo esc_url(home_url('/my-account/orders/')); ?>">Orders</a>
                                        <a class="dropdown-item <?php if (is_wc_endpoint_url('edit-address'))
                                            echo 'active'; ?>"
                                            href="<?php echo esc_url(home_url('/my-account/edit-address/')); ?>">Directions</a>
                                        <a class="dropdown-item <?php if (is_wc_endpoint_url('edit-account'))
                                            echo 'active'; ?>"
                                            href="<?php echo esc_url(home_url('/my-account/edit-account/')); ?>">Account
                                            details</a>
                                        <a class="dropdown-item <?php if (is_wc_endpoint_url('historial-reservas'))
                                            echo 'active'; ?>"
                                            href="<?php echo esc_url(home_url('my-account/historial-reservas')); ?>">Booking
                                            history</a>
                                        <a class="dropdown-item <?php if (is_wc_endpoint_url('eliminar-cuenta'))
                                            echo 'active'; ?>"
                                            href="<?php echo esc_url(home_url('/my-account/eliminar-cuenta')); ?>">Delete
                                            Account</a>
                                        <a class="dropdown-item" href="<?php echo esc_url(wc_logout_url()); ?>">Log out</a>
                                    </div>
                                <?php else: ?>
                                    <a class="area-privada-btn nav-link text-nowrap"
                                        href="<?php echo esc_url(home_url('/login/')); ?>">
                                        <span class="material-symbols-outlined">account_circle</span>
                                    </a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--MINICARRITO FUERA DEL MENU HAMBURGUESA-->
        <ul>
            <li class="nav-item cart-menu">
                <?php if (is_active_sidebar('header-cart')):  //comprueba si el widget esta activo ?>
                    <div id="header-cart-widget" class="widget-area">
                        <?php dynamic_sidebar('header-cart');  //imprime los widget activos ?>
                    </div>
                <?php endif; ?>
            </li>
        </ul>
    </nav>

    <?php
    if (!is_home()) {
        echo '<div class="divMargenSuperiorNoHome"></div>';

    } else {
        echo '<div class="divMargenSuperiorHome"></div>';
    }
    ?>