# Arquitectura y funcionalidades

Documentación de cómo está organizado el proyecto y de las funcionalidades principales que
se han ido añadiendo.

## Arquitectura PHP modular (sin duplicación)

Para no repetir el mismo HTML en cada página, se crearon **plantillas reutilizables**:

- `templates/inicioPagina.php` → genera el `<head>`, los `<link>` de CSS, el `<body>`, el
  menú (`nav.php`) y la cabecera (`header.php`).
- `templates/finPagina.php` → el pie (`footer.php`) y el cierre del documento.
- `templates/nav.php`, `header.php`, `footer.php`, `carrusel.php` → partes comunes.

Una página queda así de simple:

```php
<?php
$titulo  = "Clases - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
?>
   ... contenido propio de la página ...
<?php include "templates/finPagina.php"; ?>
```

## CSS por secciones

En lugar de varios archivos casi idénticos, el CSS está partido en **secciones
reutilizables**, y cada página carga solo las que necesita (vía el array `$estilos`):

`base` · `menu` · `cabecera` · `pie` · `carrusel` · `paginas` · `tablas-formularios` ·
`layout-1col` · `layout-2col` · `layout-3col`.

Así, por ejemplo, el header vive en **un único archivo** (`cabecera.css`).

## Cache-busting de CSS y JS

Para que los cambios de estilos/scripts se vean sin tener que vaciar la caché, los enlaces
llevan un número de versión que se sube al editar:

```php
$versionCss = "17";
// ...
<link rel="stylesheet" href="css/base.css?v=17">
```

## Carrito con AJAX + notificación (toast)

Al pulsar "Añadir al carrito", **no se recarga la página**: se envía en segundo plano con
`fetch` a un endpoint que responde en JSON (`agregarCarrito.php`), y aparece una
**notificación deslizante (toast)**. Sigue el patrón de las tiendas modernas.

Es **progressive enhancement**: si el JavaScript fallara, el formulario hace el envío
normal de toda la vida (no se rompe).

```js
fetch("agregarCarrito.php", { method: "POST", body: new FormData(form) })
  .then(r => r.json())
  .then(d => { if (d.ok) mostrarToast(d.name); })
  .catch(() => form.submit());   // respaldo
```

## Pasarela de pago: Stripe Checkout

- La **sesión de pago** se crea en el servidor (`crearPagoStripe.php`) llamando a la API
  de Stripe con cURL, y se redirige al usuario a la página de pago de Stripe.
- Al volver, `confirmacion.php` **verifica con Stripe** que el pago está `paid`, muestra el
  resumen, **vacía el carrito** y registra la fecha del pedido.
- Modo **test** (no se cobra dinero real). Tarjeta de prueba: `4242 4242 4242 4242`.

```
Cesta → crearPagoStripe.php → página de Stripe → pago →
   confirmacion.php (verifica, vacía carrito, muestra resumen)
```

## Panel de administración (CRUD)

Alta, edición y baja de **usuarios** y **artículos**, con estados claros (badges
"Activo/Bloqueado", fila destacada para el SuperAdmin, botones de acción diferenciados por
color).

## Escaparate interactivo (React)

La portada incluye un pequeño componente de **React 18** con pestañas que cambian la imagen
y el texto destacado, integrado dentro de la web PHP.

## Otros detalles

- **Menú responsive en acordeón** en móvil (los submenús se despliegan al tocar).
- **SEO básico**: `robots.txt`, `sitemap.xml`, meta tags y verificación de Google.
- **Correcciones**: enlaces con mayúsculas mal puestas (404 en Linux), ampliación de la
  columna de contraseñas para el hash bcrypt, etc.
