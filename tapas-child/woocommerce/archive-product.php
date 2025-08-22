<?php

get_header(); ?>

<style>
    .section {
        background: none;
        padding: 70px 0;
        position: relative;
    }

    .section-title {
        position: absolute;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: slideInFromLeft 1s ease-out;
    }

    @keyframes slideInFromLeft {
        0% {
            left: -100%;
            opacity: 0;
        }

        100% {
            left: 50%;
            opacity: 1;
        }
    }

    .card-image img {
        width: 100%;
        height: auto;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 50px;
            /* Reducir tamaño del título en pantallas pequeñas */
        }

        .collapse-panel {
            margin-bottom: 20px;
        }

        .col-md-3,
        .col-md-9 {
            padding: 0 10px;
        }

        .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-md-9 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .card-product {
            margin-bottom: 30px;
        }

        .btn-primary.btn-round {
            width: 100%;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .add-to-cart-container {
            display: flex;
            justify-content: center;
        }
    }

    .custom-cart-btn {
        width: 160px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        /* color opcional */
        border-radius: 6px;
        /* bordes redondeados opcional */
        padding: 5px;
    }

    /**icono boton carrito */
    .custom-cart-btn .material-symbols-outlined {
        font-size: 24px;
    }

    /* Ajuste para tamaños muy pequeños */
    @media (max-width: 480px) {
        .section-title {
            font-size: 40px;
            /* Reducir aún más el tamaño del título en pantallas muy pequeñas */
        }

        .card-body.text-center {
            padding: 15px;
        }

        .card-title {
            font-size: 18px;
        }

        .price-container {
            margin-bottom: 10px;
        }

        .card-description {
            font-size: 14px;
        }

        .btn-primary.btn-round {
            padding: 10px 0;
        }
    }
</style>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Título y descripción de la tienda -->
            <h2 class="title text-center">Online Shop</h2>
            <div class="col-md-6 text-center ml-auto mr-auto mb-4">
                <p class="p-descripcionSecciones">
                    <span class="resaltar-span-descripcion">High Cuisine Just a Click Away.</span>
                    Access a selection of our exclusive delicacies and products, bringing the quality and style of
                    Tapas Me The Plate to your home.
                </p>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="collapse-panel">
                        <div class="card-body">
                            <div class="card card-refine card-plain">
                                <h4 class="card-title">Filter</h4>
                            </div>
                            <div class="card card-refine card-plain">
                                <div class="card-header" role="tab" id="headingTwo">
                                    <h6>
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
                                            aria-expanded="true" aria-controls="collapseTwo">
                                            Category
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseTwo" class="collapse show" role="tabpanel"
                                    aria-labelledby="headingTwo">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" checked>
                                                <span class="form-check-sign"></span>
                                                Homemade sauces
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <?php
                        if (!function_exists('wc_get_products')) {
                            echo '<div class="col-md-12"><p>WooCommerce no está activo.</p></div>';
                            return;
                        }

                        $args = array(
                            'status' => 'publish',
                            'limit' => -1,
                        );
                        $products = wc_get_products($args);

                        if ($products):
                            foreach ($products as $product):
                                ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card card-product card-plain">
                                        <div class="card-image">
                                            <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                                <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                                            </a>
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                                <h4 class="card-title"><?php echo esc_html($product->get_name()); ?></h4>
                                                <div class="price-container">
                                                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                                                </div>
                                            </a>
                                            <p class="card-description">
                                                <?php echo wp_trim_words($product->get_short_description(), 10); ?>
                                            </p>

                                            <div class="card-footer">
                                                <div class="add-to-cart-container">
                                                    <?php
                                                    echo apply_filters(
                                                        'woocommerce_loop_add_to_cart_link',
                                                        sprintf(
                                                            '<a href="%s" data-quantity="%s" class="%s custom-cart-btn" %s><span class="material-symbols-outlined">shopping_cart</span></a>',
                                                            esc_url($product->add_to_cart_url()),
                                                            esc_attr(isset($quantity) ? $quantity : 1),
                                                            esc_attr(implode(' ', array_filter(array(
                                                                'btn',
                                                                'btn-primary',
                                                                'btn-sm',
                                                                'add_to_cart_button',
                                                                'ajax_add_to_cart',
                                                                'product_type_' . $product->get_type()
                                                            )))),
                                                            wc_implode_html_attributes(array(
                                                                'data-product_id' => $product->get_id(),
                                                                'data-product_sku' => $product->get_sku(),
                                                                'aria-label' => $product->add_to_cart_description(),
                                                                'rel' => 'nofollow',
                                                            )),
                                                            esc_html($product->add_to_cart_text())
                                                        )
                                                    );
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else:
                            echo '<div class="col-md-12"><p>No se encontraron productos.</p></div>';
                        endif;
                        ?>
                        <div class="col-md-3 ml-auto mr-auto">

                            <?php
                            if ($products && count($products) > 6):
                                echo '<button rel="tooltip" class="btn btn-primary btn-round" data-original-title="" title="">Load more...</button>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>