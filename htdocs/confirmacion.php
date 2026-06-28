<?php
/*
 * confirmacion.php  -  Pagina de retorno tras el pago.
 * Verifica con Stripe que la sesion esta pagada, muestra el resumen,
 * vacia el carrito y registra la fecha del pedido.
 */
session_start();
require("BaseDatos.php");
require("configStripe.php");

$pagado = false;
$lineas = [];
$totalPagado = 0;
$session_id = isset($_GET["session_id"]) ? $_GET["session_id"] : "";

if ($session_id !== "") {
	//Recuperamos la sesion de Stripe (con sus productos).
	$url = "https://api.stripe.com/v1/checkout/sessions/" . urlencode($session_id) . "?expand[]=line_items";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . STRIPE_SK]);
	$resp = curl_exec($ch);
	curl_close($ch);
	$session = json_decode($resp, true);

	if ($session && isset($session["payment_status"]) && $session["payment_status"] === "paid") {
		$pagado = true;
		$totalPagado = ($session["amount_total"] ?? 0) / 100;
		if (isset($session["line_items"]["data"])) {
			foreach ($session["line_items"]["data"] as $li) {
				$lineas[] = [
					"nombre" => $li["description"],
					"precio" => ($li["amount_total"] ?? 0) / 100,
				];
			}
		}
		//Vaciamos el carrito y registramos la fecha del pedido.
		if (isset($_SESSION["usuario"])) {
			$conn = crearConexion();
			$stmt = $conn->prepare("DELETE FROM carritodecompra WHERE Usuario = ?");
			$stmt->bind_param("s", $_SESSION["usuario"]);
			$stmt->execute();
			$stmt->close();
			date_default_timezone_set('Europe/Madrid');
			$hoy = date("Y-m-d");
			$up = $conn->prepare("UPDATE pedido SET Fecha = ?, Subtotal = ? WHERE Usuario = ?");
			$up->bind_param("sds", $hoy, $totalPagado, $_SESSION["usuario"]);
			$up->execute();
			$up->close();
			$conn->close();
		}
	}
}

//Numero de pedido del usuario.
$numPedido = "";
if (isset($_SESSION["usuario"])) {
	$rp = getListaPedido($_SESSION["usuario"]);
	$rowp = $rp ? $rp->fetch_assoc() : null;
	$numPedido = $rowp ? $rowp["NumPedido"] : "";
}

$titulo = "Confirmacion de pedido - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
?>
<?php if ($pagado) { ?>
	<div class="confirmacion">
		<div class="conf-icono"><i class="fa fa-check"></i></div>
		<h3>&iexcl;Gracias por tu compra!</h3>
		<p class="conf-sub">Tu pago se ha procesado correctamente.</p>
		<?php if ($numPedido !== "") { ?><p class="conf-pedido">Pedido <strong>#<?php echo htmlspecialchars($numPedido); ?></strong></p><?php } ?>
		<div class="conf-items">
			<?php foreach ($lineas as $l) { ?>
				<div class="conf-item"><span><?php echo htmlspecialchars($l["nombre"]); ?></span><span><?php echo $l["precio"]; ?> &euro;</span></div>
			<?php } ?>
			<div class="conf-total"><span>Total pagado</span><span><?php echo $totalPagado; ?> &euro;</span></div>
		</div>
		<p class="conf-aviso">Prepararemos tu pedido enseguida. <span class="demo-tag">Pago de prueba (Stripe test)</span></p>
		<a class="btn-seguir" href="tienda.php"><i class="fa fa-arrow-left"></i> Seguir comprando</a>
	</div>
<?php } else { ?>
	<div class="carrito-vacio">
		<i class="fa fa-exclamation-triangle"></i>
		<p>No hemos podido confirmar el pago.</p>
		<a class="btn-seguir" href="cesta.php"><i class="fa fa-arrow-left"></i> Volver a la cesta</a>
	</div>
<?php }
include "templates/finPagina.php";
?>
