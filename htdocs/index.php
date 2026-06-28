<?php
//Datos de la pagina y hojas de estilo que necesita.
$titulo = "Pagina de Inicio Obradoiro Kreativo";
$metaDescripcion = "Desde obradoiro Kreativo buscamos fomentar la creatividad, desarrollar habilidades artisticas, poner a disposicion del cliente productos de primer nivel para sus manualidades y todo ello de una manera agradable y divertida.";
$metaKeywords = "obradoiro, kreativo, manualidades, clases pintura, talleres";
$verificacionGoogle = "googleb8ad51640d112346.html";
$estilos = ["base", "menu", "cabecera", "pie", "carrusel", "layout-3col"];
$mostrarCarrusel = false;
//Scripts de React para el escaparate (van al final de la pagina).
$scriptsExtra = '
	<script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
	<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
	<script src="js/react-escaparate.js"></script>';

require("BaseDatos.php");
include "templates/inicioPagina.php";
?>
	<!-- escaparate interactivo (React) -->
	<section id="react-escaparate"></section>
	<!-- contenedor principal -->
	<section id="principal">
		<article class="">
			<h2>Tienda</h2>
			<div class="videoYoutube">
				<iframe
					src="https://www.youtube.com/embed/skDspYGoRTs"
					title="Video Obradoiro Kreativo"
					frameborder="0"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
					allowfullscreen>
				</iframe>
			</div>
			<p>&iquest;Quieres aprender a pintar?&iquest;Decorar?&iquest;Darle una segunda vida a tus muebles preferidos?</p>
			<p>Aprende nuevas t&eacute;cnicas fomentando tu creatividad!!!</p>
			<p>T&eacute;cnicas que podri&aacute;n sonarte raro pero que aprender&aacute;s no solo a decirlas, sino tambi&eacute;n a realizarlas.</p>
			<p>T&eacute;cnicas como:</p>
			<p>Decoupage.</p>
			<p>Estarcidos.</p>
			<p>Decapados.</p>
			<p>Tratamientos para madera.</p>
			<p>Porcelana fria.</p>
			<p>Modelado.</p>
			<p>Scrapbooking.</p>
			<p>Y much&iacute;simas mas...</p>
			<p>Desde obradoiro Kreativo buscamos fomentar la creatividad, desarrollar habilidades art&iacute;sticas que seguro no pensabas tener.</p>
		</article>
	</section>
	<!-- contenedor secundario -->
	<section id="secundaria">
		<article class="">
			<h2>Clases y Talleres</h2>
			<img class="logo" alt="texto alternativo" src="img/logo.jpg"><p>En nuestra tienda Obradoiro Kreativo, a lo largo del proceso de introduci&oacute;n en el arte de las manualidades, hemos ido consiguiendo ampliar nuestros cursos y talleres para que todos vosotros, que nos lo demand&aacute;is con buena gana, pudierais realizarlos de una manera conjunta y satisfactoria.</p>
			<p>Por ello en nuestras nuevas clases y talleres hemos mejorado nuestros kit de preparaci&oacute;n para realizarlas y ajustado el precio para todos los bolsillos.</p>
			<p>Nuestro aula esta enfocada a poder realizar de la mejor manera nuestros trabajos manuales y sobre todo a pasarnoslo muy bien entre todos, compartiendo experiencias, t&eacute;cnicas y destrezas en el arte del mundo creativo.</p>
			<p>Disponemos de varias Clases y Talleres de todo tipo de manualidades y t&eacute;cnicas, nuestra labor es mejorar dia a dia y poderos atender y ayudar a mejoraros cada vez mas en vuestro aprendizaje.</p>
			<p>En Obradoiro Kreativo nuestro lema es divertirnos mientras vamos creando todo tipo de art&iacute;culos o restaurando aquellos que ya no se ven como al principio. por ello disponemos de todo tipo de productos para poder lograrlo.</p>
			<p>Tambi&eacute;n debido a la pandemia y a personas que no se pueden desplazar a nuestra tienda/taller, disponemos de cursos virtuales nuestros y patrocinados, donde podre&iacute;s realizarlos siguiendo las clases online o videotutoriales de nuestros expertos creativos.</p>
			<p><b>Estas de suerte!! Correeee y reg&iacute;strate!!! 1 Mes gratuito de Aula Abierta!! 2 Horas a la Semana!! El sorteo se realizar&aacute; a principios de Enero!!!</b></p>
		</article>
	</section>
	<!-- contenedor aside -->
	<aside>
		<h2>Promociones</h2>
		<img class="promo" alt="Black Friday" src="img/blackFridayPromo.jpg">
		<p>ATENTOS!!! Promoci&oacute;n BLACK FRIDAY!! Solo ma&ntilde;ana 26 de Noviembre, ten&eacute;is un 50% de descuento en la mensualidad de 2h a la semana de clases de pintura y/o Manualidades!!!</p>
		<p>Plazas limitadas!!! Correeee Que la oferta VUELA!!! Si est&aacute;s pensando hacer un regalo esta es tu oportunidad!! Adel&aacute;ntate a las compras navide&ntilde;as!!</p>
		<h2>Sorteo</h2>
		<img class="promo" alt="Sorteo" src="img/SorteoMesGratisPromo.jpg">
		<p>ATENTOS!!! Promoci&oacute;n SORTEO!! Sorteamos entre nuestros clientes Registrados un vale gratuito de Aula Abierta!!!</p>
	</aside>
<?php include "templates/finPagina.php"; ?>
