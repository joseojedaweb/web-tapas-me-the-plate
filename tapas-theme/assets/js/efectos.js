document.addEventListener("DOMContentLoaded", function () {
  /*******************************************************************
   * MOSTRAR/OCULTAR EL WIDGET DEL CARRITO SEGÚN EL SCROLL Y LA PÁGINA
   * EN LA HOME: SE MUESTRA AL HACER SCROLL. EN OTRAS PÁGINAS: SIEMPRE VISIBLE.*/

  // Selecciona el elemento con la clase 'navbar' y el widget del carrito
  const navbar = document.querySelector(".navbar");
  const cartWidget = document.querySelector("#header-cart-widget");

  // Obtiene el valor del atributo 'data-is-front-page' del elemento navbar y verifica si es 'true'
  const isFrontPage = navbar.getAttribute("data-is-front-page") === "true"; // Leer el valor del atributo data-is-front-page de header.php

  // Define el umbral de desplazamiento para cambiar el estilo (10px)
  const scrollThreshold = 10;

  // Si estamos en la página principal (isFrontPage es true)
  if (isFrontPage) {
    // Escucha el evento de desplazamiento (scroll) en la ventana
    window.addEventListener("scroll", function () {
      const hasScrolled = window.scrollY > scrollThreshold;

      // Mostrar u ocultar el widget del carrito en la página principal
      if (cartWidget) {
        if (hasScrolled) {
          cartWidget.classList.add("mostrar-cart-widget");
        } else {
          cartWidget.classList.remove("mostrar-cart-widget");
        }
      }
    });
  } else {
    // En todas las páginas que no son la principal, mostramos el carrito desde el principio
    if (cartWidget) {
      cartWidget.classList.add("mostrar-cart-widget");
    }
  }

  /*************************************************************************************************************************************************
   /* ANIMACIÓN AL HACER SCROLL: MOSTRAR/OCULTAR EL TÍTULO "WHAT CLIENT SAY"*/

  const h2Element = document.querySelector("#h2_whatClientSay");

  if (h2Element) {
    // Creamos el IntersectionObserver
    const observer = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            // Cuando el h2 es visible en el viewport, se activa la animación
            h2Element.classList.add("whatClientSay");
          } else {
            // Cuando el h2 no es visible, se resetea la animación
            h2Element.classList.remove("whatClientSay");
          }
        });
      },
      { threshold: 1 }
    ); // Umbral del 100% de visibilidad del h2

    // Empezamos a observar el h2
    observer.observe(h2Element);
  }

  /**************************************************************************************************************************************************
   /* SCROLL SUAVE AL HACER CLIC EN ENLACES CON ANCLAS (POR EJEMPLO, #SERVICIOS)*/

 // Detectar hash al cargar la página (por ejemplo, si la URL es www.ejemplo.com#contacto)
if (window.location.hash) {
  const targetId = window.location.hash.substring(1); 
  // Extrae el ID del hash (elimina el símbolo "#" del principio)

  setTimeout(() => {
    // Espera 300 milisegundos para asegurarse de que el DOM esté completamente cargado
    smoothScrollToId(targetId, 1000); 
    // Desplazamiento suave hacia el elemento con el ID extraído, en 1000 milisegundos
  }, 300);
}

  // Detectar clics en enlaces que contienen un hash (#)
const links = document.querySelectorAll('a[href*="#"]'); 
// Selecciona todos los enlaces con un "#" en su atributo href

for (let link of links) {
  // Recorre cada uno de esos enlaces

  link.addEventListener("click", function (e) {
    // Añade un evento al hacer clic en el enlace
    const href = this.getAttribute("href"); 
    // Obtiene el valor completo del atributo href del enlace (por ejemplo: #contacto)
    const hashIndex = href.indexOf("#"); 
    // Busca la posición del símbolo "#"

    if (hashIndex !== -1) {
      // Si hay un hash en el enlace

      const targetId = href.substring(hashIndex + 1); 
      // Extrae el ID del destino (sin el #)

      const target = document.getElementById(targetId); 
      // Busca en el documento un elemento con ese ID

      if (target) {
        // Si el destino existe en el DOM

        e.preventDefault(); 
        // Evita el comportamiento predeterminado del enlace (salto brusco)

        history.pushState(null, null, "#" + targetId); 
        // Cambia el hash en la URL sin recargar la página

        smoothScrollToId(targetId, 1000); 
        // Hace scroll suave hacia el elemento con duración de 1000 milisegundos
      }
    }
  });
}


  /********************************* 
   /* DESLIZAMIENTO TÁCTIL PARA CONTROLAR EL CARRUSEL DE BOOTSTRAP (IZQUIERDA/DERECHA)*/

  let touchStartX = 0; // Variable para almacenar la posición X donde comienza el toque
  let touchEndX = 0;   // Variable para almacenar la posición X donde termina el toque

  const carousel = document.querySelector("#carouselExampleIndicators");
  // Selecciona el carrusel por su ID para poder aplicar la detección de gestos táctiles

  function handleSwipe() {
    // Función que determina la dirección del deslizamiento

    if (touchStartX - touchEndX > 50) {
      // Si el deslizamiento fue hacia la izquierda más de 50 píxeles
      $("#carouselExampleIndicators").carousel("next");
      // Avanza a la siguiente diapositiva usando Bootstrap (jQuery)
    }

    if (touchEndX - touchStartX > 50) {
      // Si el deslizamiento fue hacia la derecha más de 50 píxeles
      $("#carouselExampleIndicators").carousel("prev");
      // Retrocede a la diapositiva anterior usando Bootstrap (jQuery)
    }
  }

  carousel.addEventListener("touchstart", function (e) {
    // Evento al comenzar el toque (finger down)
    touchStartX = e.changedTouches[0].screenX;
    // Guarda la posición horizontal inicial del dedo
  });

  carousel.addEventListener("touchend", function (e) {
    // Evento al terminar el toque (finger up)
    touchEndX = e.changedTouches[0].screenX;
    // Guarda la posición horizontal final del dedo

    handleSwipe();
    // Llama a la función para determinar si fue un swipe válido y en qué dirección
  });
  
  /***************************************************************/

});
