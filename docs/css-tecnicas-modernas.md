# Técnicas de CSS moderno

Documentación de las propiedades y técnicas de CSS **actuales** utilizadas en el proyecto.
Todas tienen un respaldo (fallback) cuando es necesario, para no romper en navegadores
antiguos.

## Container Queries (`@container`)

En lugar de mirar el tamaño de la **ventana** (media queries), los componentes se adaptan
al ancho de **su contenedor**. El contenedor principal (`#content`) se declara como
contenedor de consultas y las secciones internas responden a su ancho.

```css
#content{
  container-type: inline-size;
  container-name: principal;
}

/* Las columnas se reorganizan según el ancho del contenedor, no de la ventana */
@container principal (max-width: 820px){
  #principal { width: 50%; }
  #secundaria { width: 50%; }
}

/* Respaldo para navegadores sin soporte */
@supports not (container-type: inline-size){
  @media (max-width: 820px){ #principal{ width: 50%; } #secundaria{ width: 50%; } }
}
```

Se usa en: layouts de columnas, cabecera, escaparate, footer y carrusel.

## Tamaños fluidos con `clamp()`

Tipografías y espaciados que crecen/encogen de forma **continua** (sin saltos bruscos
entre breakpoints): `clamp(mínimo, preferido, máximo)`.

```css
/* El título escala de 1.8rem a 2.6rem según el ancho disponible */
.brand-text h2{ font-size: clamp(1.8rem, 1.3rem + 2.2vw, 2.6rem); }

/* Padding fluido */
#content{ padding-inline: clamp(14px, 3vw, 24px); }
```

## Colores derivados con `color-mix()`

Las sombras de cada botón se calculan **a partir de su propio color** mediante variables
CSS + `color-mix()`. Así un único sistema de botones sirve para todos los colores.

```css
.btn{
  --btn: #2f5536;
  background: var(--btn);
  box-shadow: 0 4px 12px color-mix(in srgb, var(--btn) 35%, transparent);
}
.btn-eliminar{ --btn: #c0392b; }  /* hereda toda la lógica, solo cambia el color */
```

## Selector `:has()`

Se usa para detectar, en el menú móvil, qué apartados tienen submenú y mostrarles una
flecha indicadora — sin añadir clases extra en el HTML.

```css
@media (max-width: 820px){
  nav li:has(> ul) > a.nav-link::after{ content: "\f107"; font-family: 'FontAwesome'; }
}
```

## Glassmorphism (`backdrop-filter`)

Efecto "cristal esmerilado" en cabecera, menú, footer y tablas: fondo translúcido +
desenfoque del contenido que hay detrás.

```css
.cabecera{
  background: linear-gradient(135deg, rgba(255,250,242,0.82), rgba(242,214,216,0.5));
  backdrop-filter: blur(10px);
}
```

## Carrusel con CSS Scroll-Snap

El carrusel de productos se desliza con el dedo (gesto nativo) y "imanta" cada tarjeta al
centro, sin librerías. El JavaScript solo mueve la pista con las flechas (`scrollBy`).

```css
.carrusel-pista{ display: flex; overflow-x: auto; scroll-snap-type: x mandatory; scroll-behavior: smooth; }
.carrusel-item{ scroll-snap-align: center; }
```

## Micro-interacciones con `@keyframes`

Animaciones sutiles de feedback: la papelera "se menea" al pasar el ratón, el check del
pedido "late", el logo de la cabecera "flota", la línea de acento del menú/footer se
desplaza, etc.

```css
.btn-eliminar:hover .fa{ animation: meneo 0.45s ease; }
@keyframes meneo{ 0%,100%{transform:rotate(0)} 25%{transform:rotate(-13deg)} 75%{transform:rotate(13deg)} }
```

## Pincelada de pintura animada (SVG en `data:` URI)

El menú usa una **pincelada de pintura** dibujada como SVG embebido en el CSS, que se
"pinta" de izquierda a derecha bajo cada opción (muy acorde a una tienda de manualidades).

```css
nav li > a.nav-link::after{
  background: url("data:image/svg+xml,%3Csvg ...%3E%3Cpath d='...' fill='%23ecc0c6'/%3E%3C/svg%3E");
  transform: scaleX(0);
}
nav li > a.nav-link:hover::after{ transform: scaleX(1); }
```

## Tooltips solo con CSS (`content: attr()`)

Las etiquetas que aparecen al pasar el ratón sobre los botones de icono se generan con
CSS leyendo el `aria-label` (mejora también la accesibilidad).

```css
.btn[aria-label]::after{ content: attr(aria-label); /* ... */ }
```

## Sticky footer con Flexbox

La página ocupa siempre el alto de la pantalla y el pie se ancla abajo, aunque haya poco
contenido.

```css
body{ display: flex; flex-direction: column; min-height: 100dvh; }
#content{ flex: 1 0 auto; }
footer{ flex-shrink: 0; }
```
