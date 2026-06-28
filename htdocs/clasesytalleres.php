<?php
$titulo = "Clases y Talleres - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "paginas", "layout-2col"];
include "templates/inicioPagina.php";
?>
	<!-- contenedor principal -->
	<section id="principal">
		<div id="menuTienda">
			<h2>Clases</h2>
			<a href="clases.php"><img class="clases" src="img/clasesRotulo.jpg"/></a>
			<img class="logo" alt="texto alternativo" src="img/logo.jpg">
		</div>
		<article class="">
			<p>&iquest;Preparado para ser un artista creativo?</p>
			<p>Prueba a apuntarte a nuestras clases de manualidades presenciales u online, ver&aacute;s todo lo lejos que puedes llegar.</p>
			<p>CLASES PRESENCIALES</p>
			<p>2 Horas semanales por 40 Euros al mes.</p>
			<p>-Aprende las t&eacute;cnicas m&aacute;s novedosas.</p>
			<p>-Atenci&oacute;n individualizada.</p>
			<p>-100% seguras frente al COVID.</p>
			<p>Nuestra profesionalidad se ve avalada por cientos de alumnos satisfechos que han pasado por nuestras clases y que contin&uacute;an desde hace a&ntilde;os con nosotros.</p>
		</article>
	</section>
	<!-- contenedor secundario -->
	<section id="secundaria">
		<div id="menuTienda">
			<h2>Talleres</h2>
			<a href="talleres.php"><img class="talleres" src="img/talleresRotulo.jpg"/></a>
			<img class="logo" alt="texto alternativo" src="img/logo.jpg">
		</div>
		<article class="">
			<p>&iquest;Te apetece restaurar una pieza antigua?&iquest;Quiz&aacute;s mejor realizar un cuadro con una t&eacute;cnica espec&iacute;fica?, Nuestros tralleres presenciales disponibles cada cierto tiempo est&aacute;n para que consigas realizar de manera puntual y m&aacute;s profesional aquello que siempre te gust&oacute; hacer.</p>
			<p>En Obradoiro Kreativo disponemos de talleres espec&iacute;ficos con grandes profesionales que se dedican a ello de una manera destacada, p&aacute;sate por la tienda o por la web e inf&oacute;rmate sobre nustros talleres.</p>
			<p>No te defraudar&aacute;n!!! A qu&eacute; esperas!!!</p>
		</article>
	</section>
<?php include "templates/finPagina.php"; ?>
