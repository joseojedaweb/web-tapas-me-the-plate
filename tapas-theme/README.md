# Tapas Theme (parent) – WordPress

Tema **personalizado** para el sitio “Tapas Me The Plate”. Incluye plantillas de páginas (Booking, Contacto, Menú, Blog, Reviews, etc.), cabecera/footers propios y recursos estáticos (Bootstrap, Now UI Kit, CSS/JS y fuentes).


## 📂 Estructura destacada
- `functions.php`, `header.php`, `footer.php`, `index.php`, `page.php`
- Páginas personalizadas: `page-booking.php`, `page-modify-booking.php`, `page-menu.php`, `page-about-us.php`, `page-contact-us.php`, `page-ours-services.php`, `page-reviews.php`, etc.
- Tipos individuales: `single-menu.php`, `single-reservas.php`, `single-review.php`
- Assets: `assets/css/*`, `assets/js/*`, `assets/img/*`, `assets/fonts/*`

## 🔧 Requisitos
- WordPress 6.x
- PHP 8.x
- WooCommerce (si se usan las plantillas de tienda desde el tema hijo)

## 🚀 Instalación (solo tema padre)
1. Copia la carpeta **`tapas-theme/`** dentro de `wp-content/themes/`.
2. En el panel de WordPress → Apariencia → Temas, **activa “Tapas Theme”**.
3. (Opcional) Instala el tema hijo `tapas-child` para sobreescrituras de WooCommerce y estilos adicionales.

## 🧩 Notas
- El **tema hijo** `tapas-child` contiene overrides de WooCommerce y estilos específicos. Si vas a usar tienda o personalizaciones del área de cuenta, te conviene activarlo.
- Revisa `assets/` por si necesitas optimizar imágenes para producción.

## 🧾 Licencia
MIT (ver archivo LICENSE del repositorio).
