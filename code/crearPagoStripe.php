<?php
/*
 * crearPagoStripe.php  -  Crea una sesion de Stripe Checkout (modo test)
 * con los productos del carrito y redirige a la pagina de pago de Stripe.
 * No usa Composer: llama a la API de Stripe con cURL.
 */
session_start();
require("BaseDatos.php");
require("configStripe.php");

if (!isset($_SESSION["usuario"])) { header("Location: Validacion.php"); exit; }
$usuario = $_SESSION["usuario"];

//Construimos los articulos (line_items) desde el carrito.
$result = getArticulosCarrito($usuario);
$lineItems = [];
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$lineItems[] = [
			"price_data" => [
				"currency" => "eur",
				"product_data" => ["name" => $row["Name"]],
				"unit_amount" => (int) round(((float) $row["Price"]) * 100), // en centimos
			],
			"quantity" => 1,
		];
	}
}
//Si el carrito esta vacio, de vuelta a la cesta.
if (empty($lineItems)) { header("Location: cesta.php"); exit; }

//Gastos de envio como una linea mas.
$lineItems[] = [
	"price_data" => ["currency" => "eur", "product_data" => ["name" => "Envio"], "unit_amount" => 300],
	"quantity" => 1,
];

//URL base del sitio (funciona en cualquier dominio).
$esHttps = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off");
$base = ($esHttps ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];

$params = [
	"mode"        => "payment",
	"success_url" => $base . "/confirmacion.php?session_id={CHECKOUT_SESSION_ID}",
	"cancel_url"  => $base . "/cesta.php",
	"line_items"  => $lineItems,
];
//Si el usuario es un email valido, se lo pasamos a Stripe.
if (filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
	$params["customer_email"] = $usuario;
}

//Llamada a la API de Stripe para crear la sesion.
$ch = curl_init("https://api.stripe.com/v1/checkout/sessions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Authorization: Bearer " . STRIPE_SK,
	"Content-Type: application/x-www-form-urlencoded",
]);
$resp = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$errCurl = curl_error($ch);
curl_close($ch);

$data = json_decode($resp, true);

//Si todo va bien, Stripe devuelve la URL de pago: redirigimos.
if ($http == 200 && !empty($data["url"])) {
	header("Location: " . $data["url"]);
	exit;
}

//Si falla, mostramos un mensaje (y pistas para depurar).
$titulo = "No se pudo iniciar el pago";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
?>
	<div class="carrito-vacio">
		<i class="fa fa-exclamation-triangle"></i>
		<p>No se pudo iniciar el pago con Stripe.</p>
		<a class="btn-seguir" href="cesta.php"><i class="fa fa-arrow-left"></i> Volver a la cesta</a>
	</div>
	<?php
	//Pista de diagnostico (puedes quitarla cuando funcione):
	if ($errCurl) {
		echo '<p style="text-align:center;color:#c0392b;font-size:.85rem;">cURL: ' . htmlspecialchars($errCurl) . '</p>';
	} elseif (isset($data["error"]["message"])) {
		echo '<p style="text-align:center;color:#c0392b;font-size:.85rem;">Stripe: ' . htmlspecialchars($data["error"]["message"]) . '</p>';
	}
	include "templates/finPagina.php";
?>
