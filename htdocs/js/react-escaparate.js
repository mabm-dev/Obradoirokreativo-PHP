const productosEscaparate = [
  {
    titulo: "Pinturas y Pastas",
    texto: "Materiales creativos para decorar, restaurar y dar textura a tus proyectos.",
    imagen: "img/escaparate-pinturas-pastas.webp",
    enlace: "pinturasYPastas.php"
  },
  {
    titulo: "Antique Paint",
    texto: "Acabados con carácter para piezas únicas y restauraciones con encanto.",
    imagen: "img/escaparate-antique-paint.webp",
    enlace: "antiquePaint.php"
  },
  {
    titulo: "Dora Metálica",
    texto: "Colores metalizados para detalles luminosos y trabajos decorativos.",
    imagen: "img/escaparate-dora-metalica.webp",
    enlace: "doraMetalica.php"
  },
  {
    titulo: "Vintage Legend",
    texto: "Pinturas con estilo vintage para transformar muebles y objetos.",
    imagen: "img/escaparate-vintage-legend.webp",
    enlace: "vintageLegend.php"
  },
  {
    titulo: "Talleres",
    texto: "Actividades presenciales para aprender técnicas creativas paso a paso.",
    imagen: "img/escaparate-talleres.webp",
    enlace: "talleres.php"
  },
  {
    titulo: "Clases",
    texto: "Cursos y sesiones para desarrollar habilidades artísticas en un entorno cercano.",
    imagen: "img/escaparate-clases.webp",
    enlace: "clases.php"
  }
];

function EscaparateCreativo() {
  const [activo, setActivo] = React.useState(0);
  const destacado = productosEscaparate[activo];

  return React.createElement(
    "section",
    { className: "escaparateReact" },
    React.createElement(
      "div",
      { className: "escaparateTexto" },
      React.createElement("p", { className: "escaparateEtiqueta" }, "Selección creativa"),
      React.createElement("h2", null, destacado.titulo),
      React.createElement("p", null, destacado.texto),
      React.createElement("a", { href: destacado.enlace, className: "escaparateBoton" }, "Ver colección")
    ),
    React.createElement(
      "div",
      { className: "escaparateImagen" },
      React.createElement("img", { src: destacado.imagen, alt: destacado.titulo })
    ),
    React.createElement(
      "div",
      { className: "escaparateTabs" },
      productosEscaparate.map(function(item, index) {
        return React.createElement(
          "button",
          {
            key: item.titulo,
            type: "button",
            className: index === activo ? "activo" : "",
            onClick: function() {
              setActivo(index);
            }
          },
          item.titulo
        );
      })
    )
  );
}

const raizEscaparate = ReactDOM.createRoot(document.getElementById("react-escaparate"));
raizEscaparate.render(React.createElement(EscaparateCreativo));