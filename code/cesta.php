<?php
$titulo = "Cesta - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
require("BaseDatos.php");

$UsuarioCarrito = $_SESSION["usuario"];

//Acciones del carrito (anadir como respaldo sin JS, o quitar).
if (isset($_POST["action"])) {
	switch ($_POST["action"]) {
		case "agregarAlCarritoArti":
			agregarAlCarritoBDArti($_POST["CarritoID"], $_POST["usuario"], $_POST["id"], $_POST["Name"], $_POST["Cantidad"], $_POST["Price"], $_POST["ubi"]);
			break;
		case "quitarDelCarrito":
			quitarDelCarritoBD($_POST["id"], $_POST["Cantidad"], $_POST["Name"]);
			break;
	}
	include "templates/finPagina.php";
	exit;
}

//Recogemos los articulos del carrito y el total.
$total = 0;
$items = [];
$result = getArticulosCarrito($UsuarioCarrito);
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$items[] = $row;
		$total += $row["Price"];
	}
}
$envio = 3;
?>
	<h3>Tu cesta</h3>
<?php if (empty($items)) { ?>
	<div class="carrito-vacio">
		<i class="fa fa-shopping-cart"></i>
		<p>Tu cesta est&aacute; vac&iacute;a.</p>
		<a class="btn-seguir" href="tienda.php"><i class="fa fa-arrow-left"></i> Ir a la tienda</a>
	</div>
<?php } else {
	//Numero de pedido del usuario (para el formulario de realizar pedido).
	$resPedido = getListaPedido($UsuarioCarrito);
	$rowPedido = $resPedido ? $resPedido->fetch_assoc() : null;
	$NumPedido = $rowPedido ? $rowPedido["NumPedido"] : 0;
	?>
	<div class="carrito">
		<div class="carrito-items">
			<?php foreach ($items as $row) {
				$Cantidad = getCantidadCarrito($row["ArticuloID"], $row["ArticuloID"], $row["Name"]);
				?>
				<div class="carrito-item">
					<div class="ci-icono"><i class="fa fa-paint-brush"></i></div>
					<div class="ci-nombre"><?php echo $row["Name"]; ?></div>
					<div class="ci-precio"><?php echo $row["Price"]; ?> &euro;</div>
					<form action="cesta.php" method="post" class="ci-form">
						<input type="hidden" name="id" value="<?php echo $row["ArticuloID"]; ?>">
						<input type="hidden" name="Name" value="<?php echo $row["Name"]; ?>">
						<input type="hidden" name="Cantidad" value="<?php echo $Cantidad; ?>">
						<input type="hidden" name="action" value="quitarDelCarrito">
						<button type="submit" class="quitar" aria-label="Quitar del carrito"><i class="fa fa-trash"></i></button>
					</form>
				</div>
			<?php } ?>
		</div>

		<aside class="carrito-resumen">
			<h4>Resumen del pedido</h4>
			<div class="resumen-linea"><span>Subtotal</span><span><?php echo $total; ?> &euro;</span></div>
			<div class="resumen-linea"><span>Env&iacute;o</span><span><?php echo $envio; ?> &euro;</span></div>
			<div class="resumen-total"><span>Total</span><span><?php echo $total + $envio; ?> &euro;</span></div>
			<form action="crearPagoStripe.php" method="post">
				<button type="submit" class="btn-pedido">Finalizar compra <i class="fa fa-lock"></i></button>
			</form>
			<p class="demo-nota"><i class="fa fa-info-circle"></i> Pago de prueba &middot; tarjeta 4242 4242 4242 4242</p>
			<a class="seguir-comprando" href="tienda.php"><i class="fa fa-arrow-left"></i> Seguir comprando</a>
		</aside>
	</div>
<?php }
include "templates/finPagina.php";
?>
