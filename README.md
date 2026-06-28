# Obradoiro Kreativo — Tienda online a medida (PHP · MySQL · Stripe)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![React](https://img.shields.io/badge/React-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)
![Stripe](https://img.shields.io/badge/Stripe-635BFF?style=for-the-badge&logo=stripe&logoColor=white)

Tienda online desarrollada **desde cero con PHP y MySQL** (sin framework) para una
tienda-taller de manualidades. Incluye catálogo por categorías, registro e inicio de
sesión seguros, carrito de compra con AJAX, pasarela de pago **Stripe**, panel de
administración (CRUD) y un diseño responsive moderno.

> ⚠️ **Proyecto formativo / demostración.** Los pagos usan **Stripe en modo test** (no se
> cobra dinero real). Tarjeta de prueba: `4242 4242 4242 4242`.

---

## 🛍️ Vista general

![Portada](capturasreadme/inicio.png)

Web completa y funcional: navegación, tienda, clases y talleres, contacto, área de
cliente y panel de administrador. El foco está tanto en la **funcionalidad** (carrito,
pago, gestión) como en una **experiencia de usuario cuidada y profesional**.

## 📦 Qué incluye este repositorio

**Incluye:**
- Todo el **código fuente** de la web (PHP, CSS por secciones, JavaScript).
- Plantillas reutilizables y arquitectura modular.
- Plantillas de configuración (`config.example.php`, `configStripe.example.php`).

**NO incluye (por seguridad):**
- Credenciales de la base de datos (`config.php`).
- Claves de Stripe (`configStripe.php`).
- Copias de seguridad, archivos del IDE ni datos privados.

## 🛠️ Tecnologías utilizadas

| Área | Tecnología |
|---|---|
| Backend | PHP (sin framework), MySQL (mysqli + **consultas preparadas**) |
| Frontend | HTML5, CSS3 modular, JavaScript (jQuery puntual + JS nativo) |
| Interactividad | **React 18** (escaparate de la portada) |
| Pago | **Stripe Checkout** (modo test, vía API REST con cURL) |
| Otros | Bootstrap, Font Awesome |

## 🧩 Partes principales del trabajo

- **Diseño visual**: paleta cálida (verde + crema + dorado), *glassmorphism*,
  micro-interacciones y animaciones sutiles.
- **Header y menú**: pincelada de pintura animada, indicador de sección activa, submenús
  y versión móvil tipo acordeón.
- **Catálogo y carrito**: listados por categorías, **añadir al carrito por AJAX** con
  notificación *toast* y carrito con panel de resumen (subtotal · envío · total).
- **Pago**: integración con **Stripe Checkout** (el cobro lo gestiona Stripe; nunca se
  manejan datos de tarjeta) y página de confirmación.
- **Administración**: panel con **CRUD** de usuarios y artículos.
- **Seguridad**: consultas preparadas (anti SQL-injection), contraseñas con
  `password_hash()` (bcrypt) y migración automática, listas blancas en `ORDER BY`.
- **Responsive**: sistema moderno con **Container Queries** y **`clamp()`**.

## 📸 Capturas

### Inicio
| Portada | Escaparate (React) | Cabecera y menú |
|---|---|---|
| ![Portada](capturasreadme/inicio.png) | ![Escaparate](capturasreadme/inicioreact.png) | ![Menú](capturasreadme/headermenu.png) |

| Cabecera | Pie de página |
|---|---|
| ![Cabecera](capturasreadme/header.png) | ![Footer](capturasreadme/footer.png) |

### Tienda y compra
| Catálogo de productos | Cesta | Pago (Stripe) |
|---|---|---|
| ![Catálogo](capturasreadme/listaproductos.png) | ![Cesta](capturasreadme/finalizar-compra.png) | ![Pago](capturasreadme/checkout.png) |

| Método de pago | Resumen de la compra |
|---|---|
| ![Método de pago](capturasreadme/metododepago.png) | ![Compra](capturasreadme/sesion.png) |

### Área de cliente
| Inicio de sesión | Registro | Contacto |
|---|---|---|
| ![Login](capturasreadme/iniciarsesion.png) | ![Registro](capturasreadme/formularioregistro.png) | ![Contacto](capturasreadme/contacto.png) |

### Panel de administración
| Lista de usuarios | Modificar usuario |
|---|---|
| ![Usuarios](capturasreadme/adminusers.png) | ![Modificar usuario](capturasreadme/adminusersmodifica.png) |

| Gestión de productos | Modificar producto | Añadir producto |
|---|---|---|
| ![Productos](capturasreadme/adminproductos.png) | ![Modificar producto](capturasreadme/adminproductomodifica.png) | ![Añadir producto](capturasreadme/añadirproducto.png) |

### Responsive
| Vista tablet | Menú móvil | Menú móvil (selección) |
|---|---|---|
| ![Tablet](capturasreadme/tabletinicio.png) | ![Menú móvil](capturasreadme/movilmenu.png) | ![Menú móvil selección](capturasreadme/movilmenuseleccion.png) |

## 📁 Estructura del repositorio

```
htdocs/
  css/             Hojas por secciones (base, menu, cabecera, pie, layouts, tablas...)
  js/              JavaScript (menú, carrito AJAX, validaciones)
  templates/       Plantillas PHP reutilizables (inicioPagina, finPagina, nav, ...)
  img/             Imágenes de la web
  *.php            Páginas (index, tienda, catálogos, cesta, pago, admin, ...)
capturasreadme/    Capturas usadas en este README
README.md
```

## 🚀 Puesta en marcha (local)

1. Clona el repositorio.
2. Copia las plantillas de configuración y rellénalas:
   ```bash
   cp htdocs/config.example.php htdocs/config.php
   cp htdocs/configStripe.example.php htdocs/configStripe.php
   ```
3. Crea la base de datos e importa las tablas (`user`, `articulo`, `carritodecompra`, `pedido`).
4. Sirve la carpeta `htdocs/` con un servidor PHP (Apache/XAMPP o `php -S`).

> Para que el **pago con Stripe** funcione, el servidor debe permitir conexiones salientes
> a `api.stripe.com` (el hosting gratuito de InfinityFree las bloquea; usa un hosting que
> las permita, como Render o Railway).

---

## 📄 Licencia

Proyecto con fines educativos.
