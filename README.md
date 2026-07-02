# Obradoiro Kreativo - Tienda online a medida con PHP y MySQL

Proyecto de desarrollo web de una tienda online creada desde cero con PHP y MySQL para una marca de manualidades, creatividad y formacion artesanal.

El objetivo del proyecto fue construir una version a medida de una tienda online, trabajando la logica propia de catalogo, usuarios, carrito, panel de administracion, estructura modular, seguridad basica y una interfaz visual coherente con la identidad de Obradoiro Kreativo.

## Vista general

![Escaparate React](screenshots/inicio-react.png)

## Que incluye este repositorio

Este repositorio contiene una aplicacion PHP organizada como portfolio tecnico. Esta preparado para mostrar el trabajo realizado de forma clara y segura, sin publicar credenciales ni datos privados.

Incluye:

- Codigo fuente PHP de la tienda.
- Conexion MySQL mediante archivo de configuracion externo.
- CSS modular por zonas de la interfaz.
- JavaScript para carrusel, validaciones e interacciones.
- Escaparate interactivo con React 18.
- Formularios de registro, login, carrito y administracion.
- Plantillas reutilizables para cabecera, menu, carrusel y footer.
- Capturas optimizadas del resultado final.
- Esquema de la base de datos con datos de ejemplo (`database.sql`).
- Documentacion tecnica del proyecto.

No incluye:

- Credenciales reales de base de datos.
- Claves reales de Stripe.
- Datos reales de clientes o pedidos.
- Copias de seguridad privadas.
- Archivos propios del hosting gratuito.
- Configuraciones locales privadas.

## Tecnologias utilizadas

- PHP sin framework.
- MySQL con mysqli.
- HTML5.
- CSS3 modular.
- JavaScript nativo.
- jQuery puntual para interacciones existentes.
- React 18 para el escaparate de portada.
- Bootstrap.
- Font Awesome.
- Stripe Checkout integrado en modo test.

## Partes principales del trabajo

### Desarrollo a medida

La tienda se desarrollo sin framework para practicar la construccion manual de una aplicacion web: paginas PHP, plantillas reutilizables, conexion a base de datos, sesiones, formularios y gestion de entidades.

### Diseno visual

Se creo una identidad visual artesanal y calida, con fondos suaves, tarjetas de producto, cabecera personalizada, footer y una portada mas moderna mediante un escaparate interactivo con React.

### Header y menu

El menu se adapto para funcionar como eje de navegacion principal:

- Navegacion entre inicio, tienda, clases, talleres y contacto.
- Submenus para categorias de producto.
- Estado de usuario con inicio/cierre de sesion.
- Iconos de apoyo visual.
- Ajustes responsive para pantallas pequenas.

### Catalogo y carrito

El catalogo se organiza por categorias y productos. El carrito permite anadir articulos, revisar cantidades y avanzar hacia el flujo de pedido.

### Administracion

El proyecto incluye pantallas CRUD para gestionar usuarios y articulos, pensadas como panel de administracion basico para el mantenimiento de la tienda.

### Seguridad y datos

Se separaron las credenciales en archivos no versionados, se anadieron plantillas de configuracion y se trabajo la autenticacion con `password_hash()` y migracion de contrasenas antiguas.

### React

React se usa de forma puntual como mejora visual en la portada. No sustituye el flujo PHP de la tienda, sino que actua como escaparate interactivo que dirige a las paginas existentes.

### Stripe

La tienda integra Stripe Checkout en modo test, probado de extremo a extremo en el hosting: la cesta crea la sesion de pago en el servidor (cURL a la API de Stripe), el cobro se realiza en la pagina alojada de Stripe y, al volver, la aplicacion verifica el pago contra la API y registra el pedido. Los datos de tarjeta nunca tocan la aplicacion.

## Capturas

### Inicio con escaparate React

![Inicio con escaparate React](screenshots/inicio-react.png)

### Cabecera y menu

![Cabecera y menu](screenshots/header-menu.png)

### Catalogo de productos

![Catalogo de productos](screenshots/lista-productos.png)

### Contacto

![Contacto](screenshots/contacto.png)

### Menu movil

![Menu movil](screenshots/movil-menu.png)

## Estructura del repositorio

```text
Obradoirokreativo-PHP/
├─ README.md
├─ .gitignore
├─ database.sql
├─ code/
│  ├─ css/
│  │  ├─ base.css
│  │  ├─ menu.css
│  │  ├─ cabecera.css
│  │  ├─ carrusel.css
│  │  ├─ paginas.css
│  │  ├─ pie.css
│  │  └─ tablas-formularios.css
│  ├─ js/
│  │  ├─ funciones.js
│  │  └─ react-escaparate.js
│  ├─ templates/
│  │  ├─ inicioPagina.php
│  │  ├─ finPagina.php
│  │  ├─ nav.php
│  │  ├─ header.php
│  │  ├─ carrusel.php
│  │  └─ footer.php
│  ├─ img/
│  └─ *.php
├─ docs/
│  ├─ configuracion-local.md
│  ├─ css-organizacion-notas.md
│  ├─ decisiones-tecnicas.md
│  ├─ estructura-repositorio.md
│  └─ seguridad-y-privacidad.md
└─ screenshots/
   ├─ inicio-react.png
   ├─ header-menu.png
   ├─ lista-productos.png
   ├─ contacto.png
   └─ movil-menu.png
```

## Puesta en marcha local

1. Clona el repositorio.
2. Copia las plantillas de configuracion y rellena tus datos:

   ```bash
   cp code/config.example.php code/config.php
   cp code/configStripe.example.php code/configStripe.php
   ```

3. Crea una base de datos MySQL vacia e importa [`database.sql`](database.sql)
   (incluye las 4 tablas con sus claves foraneas, un admin y un cliente de prueba,
   y productos de muestra):

   ```bash
   mysql -u root -p tu_base_de_datos < database.sql
   ```

4. Sirve la carpeta `code/` con Apache/XAMPP o un servidor PHP local.
5. Abre `index.php` desde el servidor local.
6. Entra con `Administrador` / `admin123` (admin) o `demo@demo.com` / `demo123` (cliente).

### Probar el pago con Stripe (modo test)

1. En el panel de Stripe, con el **modo de prueba** activado, copia tus claves desde
   *Desarrolladores -> Claves de API* y pegalas en `code/configStripe.php`.
2. Anade productos a la cesta y pulsa "Finalizar compra".
3. En la pagina de Stripe paga con la tarjeta de prueba `4242 4242 4242 4242`
   (caducidad futura, CVC cualquiera). El cobro ficticio aparece en el panel de
   Stripe, en *Pagos*.

> La sesion de pago se crea por cURL desde el servidor y el pago se verifica
> consultando la API al volver (sin webhooks), por lo que funciona incluso en
> hostings gratuitos que bloquean las peticiones entrantes de servicios externos.

## Documentacion

- [Configuracion local](docs/configuracion-local.md)
- [Decisiones tecnicas](docs/decisiones-tecnicas.md)
- [Estructura del repositorio](docs/estructura-repositorio.md)
- [Seguridad y privacidad](docs/seguridad-y-privacidad.md)
- [Notas de organizacion del CSS](docs/css-organizacion-notas.md)

## Nota sobre el proyecto

Este repositorio esta enfocado a mostrar una version de tienda online desarrollada a medida con PHP y MySQL. No pretende competir con una solucion CMS como WordPress/WooCommerce, sino demostrar la implementacion manual de funcionalidades habituales de una tienda online.

## Licencia

Proyecto con fines educativos y de portfolio.