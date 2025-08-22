<?php
/**
 * WooCommerce Single Product Template
 */
defined('ABSPATH') || exit;

global $product;

// Empieza el contenedor principal
?>

<div class="row">
	<!-- Columna para la imagen del producto -->
	<div class="col-md-4">
		<div class="product-images">
			<?php
			// Mostrar la imagen principal del producto
			echo '<div class="mb-3">';
			echo woocommerce_get_product_thumbnail();
			echo '</div>';

			// Obtener la primera imagen de la galería del producto
			$product = wc_get_product(get_the_ID()); // Obtener el objeto del producto
			$attachment_ids = $product->get_gallery_image_ids(); // Obtener los IDs de la galería
			
			if ($attachment_ids) {
				$first_gallery_image_url = wp_get_attachment_url($attachment_ids[0]); // Obtener la URL de la primera imagen de la galería
				echo '<div class="">';
				echo '<img src="' . esc_url($first_gallery_image_url) . '" alt="' . esc_attr(get_post_meta($attachment_ids[0], '_wp_attachment_image_alt', true)) . '" class="img-fluid" />';
				echo '</div>';
			}
			?>
		</div>

	</div>

	<!-- Columna para la descripción del producto -->
	<div class="col-md-8">
		<div class="product-details">
			<h1 class="product-title"><?php the_title(); ?></h1>

			<!-- Mostrar el precio -->
			<p class="product-price"><?php echo $product->get_price_html(); ?></p>

			<!-- Mostrar la descripción corta -->
			<p class="short-description">
				<?php echo $product->get_short_description(); ?>
			</p>

			<!-- Mostrar la descripción larga -->
			<p class="card-description">
				<?php echo wp_trim_words($product->get_short_description()); ?>
			</p>

			<!-- Botón de añadir al carrito -->
			<div class="add-to-cart-container">
				<?php
				// Genera y muestra el botón "Añadir al carrito" para un producto dentro de un bucle (como en un grid de productos)
				
				?>
				<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
					data-quantity="1"
					class="<?php echo esc_attr(implode(' ', array_filter(array(
						'btn',
						'btn-primary',
						'btn-sm',
						'add_to_cart_button',
						'ajax_add_to_cart',
						'product_type_' . $product->get_type()
					)))); ?>"
					data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
					rel="nofollow">
					<i class="fas fa-shopping-cart"></i> Add to cart
				</a>
				<?php
				/*
				echo apply_filters(
					'woocommerce_loop_add_to_cart_link', // Filtro para permitir modificar el HTML del enlace antes de que se muestre
				
					sprintf(
						'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
						// Estructura del botón <a> con atributos dinámicos usando sprintf()
				
						esc_url($product->add_to_cart_url()),
						// URL segura para añadir el producto al carrito
				
						esc_attr(isset($quantity) ? $quantity : 1),
						// Cantidad a añadir, si no está definida se usa 1
				
						esc_attr(implode(' ', array_filter(array(
							// Clases CSS para el botón, se combinan en un string separados por espacio
							'btn', // Clase genérica de botón
							'btn-primary', // Clase para color principal (puede venir de Bootstrap)
							'btn-sm', // Tamaño pequeño del botón
							'add_to_cart_button', // Clase que activa funciones JS de WooCommerce
							'ajax_add_to_cart', // Habilita AJAX (evita recarga de página)
							'product_type_' . $product->get_type() // Clase dinámica según tipo de producto (simple, variable, etc.)
						)))),

						wc_implode_html_attributes(array(
							// Atributos adicionales en formato clave => valor
							'data-product_id' => $product->get_id(), // ID del producto
							'data-product_sku' => $product->get_sku(), // SKU del producto
							'aria-label' => $product->add_to_cart_description(), // Descripción accesible para lectores de pantalla
							'rel' => 'nofollow', // Previene que los bots sigan el enlace
						)),

						esc_html($product->add_to_cart_text())
						// Texto visible dentro del botón (como "Añadir al carrito")
					)
				);
				*/
				?>

			</div>
		</div>
	</div>
</div>

<?php
// Aquí puedes agregar más secciones del producto, como las pestañas de descripción, opiniones, etc.
