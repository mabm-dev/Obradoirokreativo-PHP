# Obradoiro Kreativo — Tienda online de manualidades

Tienda online (proyecto formativo) para una tienda-taller de manualidades: catálogo de
productos por categorías, registro e inicio de sesión, carrito de compra, pago con
**Stripe** (modo test), panel de administración (CRUD de usuarios y artículos), clases y
talleres, contacto y SEO básico.

> ⚠️ **Proyecto formativo / demostración.** Los pagos usan Stripe en **modo test**
> (no se cobra dinero real). Tarjeta de prueba: `4242 4242 4242 4242`.

## 🛠️ Tecnologías

- **PHP** (sin framework) + **MySQL** (mysqli con **consultas preparadas**)
- **HTML5 / CSS3** modular + **JavaScript** (jQuery puntual y JS nativo)
- **React 18** (componente de escaparate en la portada)
- **Stripe Checkout** (pasarela de pago, modo test, vía API REST con cURL)
- **Bootstrap** y **Font Awesome**

## ✨ Funcionalidades destacadas

- **Seguridad**: consultas preparadas (anti SQL-injection), contraseñas con
  `password_hash()` (bcrypt) y migración automática desde el formato antiguo, listas
  blancas en `ORDER BY`.
- **Arquitectura PHP modular**: plantillas reutilizables (`inicioPagina`, `finPagina`,
  `nav`, `header`, `footer`) y CSS por secciones — sin código duplicado.
- **Responsive moderno**: **Container Queries** + `clamp()` (tamaños fluidos).
- **E-commerce**: carrito con AJAX + notificación *toast*, pago con Stripe, página de
  confirmación, vaciado del carrito.
- **Panel de administración**: alta/edición/baja de usuarios y artículos.
- **Detalles de UX**: menú con pincelada animada, *glassmorphism*, micro-interacciones.

## 🚀 Puesta en marcha (local)

1. Clona el repositorio.
2. Copia las plantillas de configuración y rellénalas:
   ```bash
   cp htdocs/config.example.php htdocs/config.php
   cp htdocs/configStripe.example.php htdocs/configStripe.php
   ```
   - `config.php`: credenciales de tu base de datos MySQL.
   - `configStripe.php`: tus claves de Stripe (modo test).
3. Crea la base de datos e importa las tablas (`user`, `articulo`, `carritodecompra`,
   `pedido`).
4. Sirve la carpeta `htdocs/` con un servidor PHP (Apache/XAMPP, o `php -S`).

> Para que el **pago con Stripe** funcione, el servidor debe permitir conexiones
> salientes a `api.stripe.com` (el hosting gratuito de InfinityFree las bloquea; usa un
> hosting que las permita, p. ej. Render o Railway).

## 📁 Estructura

```
htdocs/
  css/            Hojas por secciones (base, menu, cabecera, pie, layouts, ...)
  js/             JavaScript (menú, carrito AJAX, validaciones)
  templates/      Plantillas PHP reutilizables
  img/            Imágenes
  *.php           Páginas (index, tienda, catálogos, cesta, pago, admin, ...)
```

## 📄 Licencia

Proyecto con fines educativos.
