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

![Portada](screenshots/portada.png)

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

### Diseño visual
Paleta cálida (verde + crema + dorado), efecto *glassmorphism* (cristal esmerilado),
micro-interacciones y animaciones sutiles.

### Header y menú
Menú de navegación con **pincelada de pintura animada**, indicador de sección activa,
submenús desplegables y versión móvil tipo **acordeón** (hamburguesa).

### Catálogo y carrito
Listados por categorías con tablas estilizadas, **añadir al carrito por AJAX** con
notificación *toast* (sin recargar) y carrito con panel de resumen (subtotal · envío ·
total).

### Pago
Integración con **Stripe Checkout**: la sesión de pago se crea en el servidor y el cobro
lo gestiona Stripe (nunca se manejan datos de tarjeta). Página de confirmación tras el
pago.

### Administración
Panel de administrador con **CRUD** de usuarios y artículos (alta, edición y baja), con
estados y acciones claras.

### Seguridad (backend)
- **Consultas preparadas** en todas las consultas (anti SQL-injection).
- Contraseñas con **`password_hash()` (bcrypt)** y migración automática desde el formato
  antiguo.
- Listas blancas en los `ORDER BY`.

### Responsive
Sistema moderno con **Container Queries** y **`clamp()`** (tamaños fluidos), con respaldo
para navegadores antiguos.

## 📸 Capturas

### Inicio y tienda
| Portada | Tienda | Catálogo de productos |
|---|---|---|
| ![Portada](screenshots/portada.png) | ![Tienda](screenshots/tienda.png) | ![Catálogo](screenshots/catalogo.png) |

### Compra (carrito y pago)
| Carrito | Pago (Stripe) | Confirmación |
|---|---|---|
| ![Carrito](screenshots/carrito.png) | ![Pago](screenshots/pago.png) | ![Confirmación](screenshots/confirmacion.png) |

### Área de cliente y administración
| Inicio de sesión | Registro | Lista de usuarios (admin) |
|---|---|---|
| ![Login](screenshots/login.png) | ![Registro](screenshots/registro.png) | ![Admin](screenshots/admin.png) |

### Otras páginas y responsive
| Clases y talleres | Contacto | Menú móvil |
|---|---|---|
| ![Clases y talleres](screenshots/clases-talleres.png) | ![Contacto](screenshots/contacto.png) | ![Menú móvil](screenshots/movil-menu.png) |

> Las imágenes van en la carpeta `screenshots/` con esos mismos nombres.

## 📁 Estructura del repositorio

```
htdocs/
  css/            Hojas por secciones (base, menu, cabecera, pie, layouts, tablas...)
  js/             JavaScript (menú, carrito AJAX, validaciones)
  templates/      Plantillas PHP reutilizables (inicioPagina, finPagina, nav, ...)
  img/            Imágenes
  *.php           Páginas (index, tienda, catálogos, cesta, pago, admin, ...)
screenshots/      Capturas de la web
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
