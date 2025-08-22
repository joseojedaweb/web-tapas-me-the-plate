<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order">

	<?php
	if ($order):

		do_action('woocommerce_before_thankyou', $order->get_id());
		?>

		<?php if ($order->has_status('failed')): ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
				<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
			</p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>"
					class="button pay"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
				<?php if (is_user_logged_in()): ?>
					<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
						class="button pay"><?php esc_html_e('My account', 'woocommerce'); ?></a>
				<?php endif; ?>
			</p>

		<?php else: ?>

			<?php wc_get_template('checkout/order-received.php', array('order' => $order)); ?>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e('Order number:', 'woocommerce'); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e('Date:', 'woocommerce'); ?>
					<strong><?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()): ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e('Email:', 'woocommerce'); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e('Total:', 'woocommerce'); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ($order->get_payment_method_title()): ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e('Payment method:', 'woocommerce'); ?>
						<strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
		<?php do_action('woocommerce_thankyou', $order->get_id()); ?>

		<!--MENSAJE DE CONFIRMACION DE REENVIO DE CORREO-->
		<?php
		// Obtener el pedido actual
		$order = wc_get_order(get_query_var('order-received'));

		if ($order):
			// Construir la URL de AJAX segura con nonce
			$resend_url = wp_nonce_url(
				add_query_arg(array(
					'action' => 'resend_order_email',
					'order_id' => $order->get_id(),
				), admin_url('admin-ajax.php')),
				'resend_order_email_' . $order->get_id()
			);
			?>

			<!-- BOTÓN PARA REENVIAR CORREO SIN REDIRECCIÓN -->
			<div class="mt-4">
				<!-- Usamos <a> como botón visual y aplicamos interceptación JS -->
				<a href="<?php echo esc_url($resend_url); ?>" id="reenviar-link" class="button alt"
					style="background-color: red;">
					Reenviar correo de confirmación
				</a>

				<!-- Mensaje de éxito o error al reenviar el correo -->
				<p id="mensaje-reenvio" style="margin-top: 10px; color: green;"></p>
			</div>

			<!-- BOTÓN PARA VOLVER A LA TIENDA -->
			<div class="mt-4">
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="boton-seguir-comprando">
					🛒 Seguir comprando
				</a>
			</div>

		<?php endif; ?>

	<?php else: ?>

		<?php wc_get_template('checkout/order-received.php', array('order' => false)); ?>

	<?php endif; ?>

</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {
	// Cuando se hace clic en el enlace de reenviar
	document.getElementById('reenviar-link')?.addEventListener('click', function (e) {
		e.preventDefault(); // Evita que el enlace redirija a otra página

		const url = this.href; // Obtiene la URL AJAX generada por PHP
		const mensaje = document.getElementById('mensaje-reenvio'); // Elemento para mostrar el resultado

		// Enviar la solicitud AJAX a admin-ajax.php
		fetch(url)
			.then(res => res.text())
			.then(() => {
				// Mostrar mensaje de éxito
				mensaje.textContent = 'Correo de confirmación reenviado correctamente.';
				mensaje.style.color = 'green';
			})
			.catch(() => {
				// Mostrar mensaje de error
				mensaje.textContent = 'Error al reenviar el correo.';
				mensaje.style.color = 'red';
			});
	});

});
</script>