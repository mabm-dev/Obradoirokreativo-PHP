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