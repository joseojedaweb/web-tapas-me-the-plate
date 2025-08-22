# Tapas Child Theme – WordPress (WooCommerce overrides)

Tema **hijo** para “Tapas Theme”. Sobrescribe plantillas de **WooCommerce** (carrito, checkout, mi cuenta, single product, archivo de productos), añade estilos específicos y lógica mínima.

## 📂 Estructura destacada
- `style.css` (cabecera de tema hijo y estilos base)
- `functions.php` (enqueue y ajustes)
- `css/cart-style.css` (estilos personalizados del carrito/checkout)
- `footer-clean.php`
- WooCommerce overrides: `woocommerce/`  
  - `cart/cart.php`
  - `checkout/form-checkout.php`, `form-shipping.php`, `thankyou.php`, `order-received.php`
  - `myaccount/*` (dashboard, login, orders, addresses, etc.)
  - `single-product.php`, `archive-product.php`, `content-single-product.php`

## 🔧 Requisitos
- WordPress 6.x
- PHP 8.x
- **Tapas Theme (padre)** instalado y activo
- WooCommerce

## 🚀 Instalación (tema hijo)
1. Copia la carpeta **`tapas-child/`** dentro de `wp-content/themes/` (junto a `tapas-theme/`).
2. En Apariencia → Temas, **activa “Tapas Child”**. WordPress usará el padre automáticamente.
3. Comprueba que WooCommerce esté activo para que se apliquen las plantillas.

## 🧩 Notas
- Este tema hijo prioriza los **overrides de WooCommerce** y estilos de carrito/checkout/área de cuenta.
- Si necesitas más velocidad, considera minificar `css/cart-style.css` y activar caché en producción.

## 🧾 Licencia
MIT (ver archivo LICENSE del repositorio).
