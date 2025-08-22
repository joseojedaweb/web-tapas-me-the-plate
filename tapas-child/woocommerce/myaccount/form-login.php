<?php
/**
 * Login Form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form');

// Detectar si hay un parámetro en la URL (o inyectado con $_GET desde un shortcode)
$view = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'both'; // 'login', 'register' o 'both'

?>

<div class="row" id="customer_login">

<!-- LOGIN -->
    <?php if ($view === 'login' || $view === 'both') : ?>
    <div class="col-md-6">
        <div class="u-column1 col-12">

            <?php if ('no' === get_option('woocommerce_enable_login_form') || 'yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

                <h2>Login</h2>

                <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

                    <?php do_action('woocommerce_login_form_start'); ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="username">Username or email address&nbsp;<span
                                class="required" aria-hidden="true">*</span><span
                                class="screen-reader-text">Required</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                            id="username" autocomplete="username"
                            value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                            required aria-required="true" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password">Password&nbsp;<span class="required"
                                aria-hidden="true">*</span><span
                                class="screen-reader-text">Required</span></label>
                        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password"
                            id="password" autocomplete="current-password" required aria-required="true" />
                    </p>

                    <?php do_action('woocommerce_login_form'); ?>

                    <p class="form-row">
                        <label
                            class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
                                type="checkbox" id="rememberme" value="forever" /> <span>Remember me</span>
                        </label>
                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                        <button type="submit"
                            class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                            name="login"
                            value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">Log in</button>
                    </p>
                    <p class="woocommerce-LostPassword lost_password">
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">Lost your password?</a>
                    </p>

                    <!-- enlace para registro -->
                    <p class="woocommerce-RegisterNow">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('register')); ?>">
                            <?php esc_html_e('Not registered yet? Click here to register.', 'woocommerce'); ?>
                        </a>
                    </p>


                    <?php do_action('woocommerce_login_form_end'); ?>

                </form>

            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>
    <!--FIN LOGIN-->

    <!--REGISTRO-->
    <?php if (($view === 'register' || $view === 'both') && 'yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
    <div class="col-md-6" id="customer_registro">
        <div class="u-column2 col-12">

            <h2><?php esc_html_e('Register', 'woocommerce'); ?></h2>

            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required"
                                aria-hidden="true">*</span><span
                                class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                            id="reg_username" autocomplete="username"
                            value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                            required aria-required="true" />
                    </p>
                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span
                            class="required" aria-hidden="true">*</span><span
                            class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                        id="reg_email" autocomplete="email"
                        value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>"
                        required aria-required="true" />
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required"
                                aria-hidden="true">*</span><span
                                class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password"
                            id="reg_password" autocomplete="new-password" required aria-required="true" />
                    </p>
                <?php else : ?>
                    <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
                <?php endif; ?>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocommerce-form-row form-row">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit"
                        class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit"
                        name="register"
                        value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>

        </div>
    </div>
    <?php endif; ?>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>

<?php get_footer('clean'); ?>