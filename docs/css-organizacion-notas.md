# Notas de organizacion del CSS

El CSS se organizo por zonas para facilitar el mantenimiento:

- `base.css`: estilos generales, fuentes y variables visuales.
- `menu.css`: navegacion principal y responsive.
- `cabecera.css`: header y elementos de marca.
- `carrusel.css`: carrusel de productos o destacados.
- `layout-1col.css`, `layout-2col.css`, `layout-3col.css`: estructuras de pagina.
- `paginas.css`: estilos especificos de contenido.
- `pie.css`: footer.
- `tablas-formularios.css`: formularios, tablas y panel de administracion.

La idea es mantener una identidad visual comun sin mezclar todas las reglas en un unico archivo.

## Tecnicas de CSS moderno

Ademas de la organizacion por secciones, la interfaz emplea propiedades de CSS actuales,
todas con respaldo cuando hace falta.

### Container Queries

Los componentes se adaptan al ancho de SU contenedor (no de la ventana). `#content` se
declara como contenedor de consultas y las secciones responden a su ancho.

```css
#content{ container-type: inline-size; container-name: principal; }
@container principal (max-width: 820px){ #principal{ width: 50%; } }
/* Respaldo para navegadores sin soporte */
@supports not (container-type: inline-size){
  @media (max-width: 820px){ #principal{ width: 50%; } }
}
```

### Tamanos fluidos con clamp()

Tipografias y espaciados que crecen/encogen de forma continua, sin saltos bruscos:

```css
.brand-text h2{ font-size: clamp(1.8rem, 1.3rem + 2.2vw, 2.6rem); }
```

### Colores derivados con color-mix()

Cada boton calcula su sombra a partir de su propio color, mediante variables CSS:

```css
.btn{ --btn: #2f5536; box-shadow: 0 4px 12px color-mix(in srgb, var(--btn) 35%, transparent); }
```

### Selector :has()

Detecta, en el menu movil, que apartados tienen submenu para mostrar una flecha indicadora,
sin anadir clases extra en el HTML.

### Glassmorphism (backdrop-filter)

Efecto cristal esmerilado (fondo translucido + desenfoque del contenido de detras) en
cabecera, menu, footer y tablas.

### Carrusel con scroll-snap

El carrusel se desliza con el dedo e imanta cada tarjeta al centro, sin librerias
(`scroll-snap-type: x mandatory`).

### Micro-interacciones (@keyframes)

Animaciones sutiles de feedback: la papelera se menea, el check del pedido late, el logo de
la cabecera flota, la linea de acento del menu/footer se desplaza.

### Pincelada de pintura (SVG en data: URI)

El menu dibuja una pincelada de pintura (SVG embebido en el propio CSS) que se pinta de
izquierda a derecha bajo cada opcion, muy acorde a una tienda de manualidades.

### Tooltips solo con CSS

Las etiquetas que aparecen al pasar el raton se generan con `content: attr(aria-label)`
(tambien mejora la accesibilidad).

### Sticky footer con Flexbox

La pagina ocupa siempre el alto de la pantalla y el pie se ancla abajo:

```css
body{ display: flex; flex-direction: column; min-height: 100dvh; }
#content{ flex: 1 0 auto; }
footer{ flex-shrink: 0; }
```