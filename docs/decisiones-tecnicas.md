# Decisiones tecnicas

## PHP sin framework

El proyecto se realizo con PHP sin framework para trabajar directamente la estructura de paginas, sesiones, formularios y consultas a base de datos.

## CSS modular

La interfaz se separo en varias hojas CSS por responsabilidad: base, menu, cabecera, carrusel, layouts, paginas y formularios. Esta organizacion facilita mantener el estilo sin depender de una unica hoja demasiado grande.

## Plantillas reutilizables

La carpeta `templates/` contiene piezas comunes como menu, cabecera, carrusel y footer. Esto evita duplicar HTML entre paginas.

## React puntual

React se usa solo en el escaparate de portada para aportar una capa moderna e interactiva sin reescribir toda la aplicacion PHP.

## Stripe preparado para test

La integracion con Stripe esta separada mediante `configStripe.php` para mantener las claves fuera del repositorio y facilitar el modo test.

## Cache-busting de CSS y JS

Los enlaces de estilos y scripts llevan un numero de version (`?v=N`) que se sube al editar,
para que los cambios se vean sin tener que vaciar la cache del navegador.

## Carrito con AJAX y notificacion (toast)

Anadir al carrito no recarga la pagina: se envia con `fetch` a un endpoint JSON
(`agregarCarrito.php`) y aparece una notificacion deslizante (toast). Es progressive
enhancement: si el JavaScript fallara, el formulario hace el envio normal de toda la vida.

## Pago con Stripe Checkout

La sesion de pago se crea en el servidor (cURL a la API de Stripe) y se redirige a la pagina
de pago de Stripe. Al volver, `confirmacion.php` verifica que el pago esta `paid`, vacia el
carrito y muestra el resumen. Modo test (tarjeta 4242 4242 4242 4242).

## Responsive moderno

El sistema responsive se basa en Container Queries y `clamp()` (detallado en "Notas de
organizacion del CSS").
