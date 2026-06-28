<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
//Pagina actual, para marcar el enlace activo en el menu.
$pagActual = basename($_SERVER["PHP_SELF"]);
function navActiva($actual, $paginas) {
    return in_array($actual, $paginas, true) ? " activo" : "";
}
?>
<!--barra de navegacion -->
<nav id="navbar">
	<div class="myicono"><i class="fa fa-bars"></i></div>
	<ul class="small-list">
		<li><a class="nav-link<?php echo navActiva($pagActual, ['index.php']); ?>" href="index.php"><i class="fa fa-home"></i><span>Inicio</span></a></li>
		<li><a class="nav-link<?php echo navActiva($pagActual, ['tienda.php','pinturasYPastas.php','doraMetalica.php','vintageLegend.php','antiquePaint.php']); ?>" href="tienda.php"><i class="fa fa-paint-brush"></i><span>Tienda</span></a>
			<ul class="small-list3">
				<li><a href="pinturasYPastas.php">Pinturas y Pastas</a></li>
				<li><a href="doraMetalica.php">Dora Metalica</a></li>
				<li><a href="vintageLegend.php">Vintage Legend</a></li>
				<li><a href="antiquePaint.php">Antique Paint</a></li>
			</ul>
		</li>
		<li><a class="nav-link<?php echo navActiva($pagActual, ['clasesytalleres.php','clases.php','talleres.php']); ?>" href="clasesytalleres.php"><i class="fa fa-graduation-cap"></i><span>Clases y Talleres</span></a>
			<ul class="small-list3">
				<li><a href="clases.php">Clases</a></li>
				<li><a href="talleres.php">Talleres</a></li>
			</ul>
		</li>
		<li><a class="nav-link<?php echo navActiva($pagActual, ['contacto.php']); ?>" href="contacto.php"><i class="fa fa-envelope-o"></i><span>Contacto</span></a></li>
		<?php
		//Si no existe usuario, mostramos el acceso.
		if(!isset($_SESSION["usuario"])){?>
			<li class="nav-cuenta"><a class="nav-link" href="Validacion.php"><i class="fa fa-user-o"></i><span>Iniciar sesi&oacute;n</span></a></li>
		<?php }
		//Si existe usuario, mostramos cerrar sesion (y carrito si no es admin).
		else {
			if($_SESSION["usuario"]!="Administrador"){?>
				<li class="nav-cuenta"><a class="nav-link" href="cerrarSesion.php"><i class="fa fa-sign-out"></i><span>Cerrar sesi&oacute;n</span></a></li>
				<li class="nav-cuenta nav-cesta"><a class="nav-link" href="cesta.php"><i class="fa fa-shopping-cart"></i></a></li>
			<?php }else{?>
				<li class="nav-cuenta"><a class="nav-link" href="cerrarSesion.php"><i class="fa fa-sign-out"></i><span>Cerrar sesi&oacute;n</span></a></li>
			<?php }
		}?>
	</ul>
</nav>
<?php
//Mensaje de bienvenida segun el estado de la sesion.
if(!isset($_SESSION["usuario"])){
	echo "<h5>Bienvenido, necesitas registrarte o loguearte Usuario: Basico</h5>";
} else {
	echo "<h5>Bienvenido a la WEB Obradoiro Kreativo usuario: " . $_SESSION["usuario"] . "</h5>";
}
?>
