<?php
$titulo = "Tienda - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "paginas", "layout-1col"];
include "templates/inicioPagina.php";
?>
	<!-- contenedor principal -->
	<section id="principal">
		<div id="menuTienda">
			<h2>Tienda</h2>
			<a href="pinturasYPastas.php"><img class="pintuPastas" src="img/PinturasYPastasRotulo.jpg"/></a>
			<a href="doraMetalica.php"><img class="doraMetalica" src="img/DoraMetalicaRotulo.jpg"/></a>
			<a href="vintageLegend.php"><img class="vintageLegend" src="img/VintageLegendRotulo.jpg"/></a>
			<a href="antiquePaint.php"><img class="antiquePaint" src="img/AntiquePaintRotulo.jpg"/></a>
		</div>
	</section>
<?php include "templates/finPagina.php"; ?>
