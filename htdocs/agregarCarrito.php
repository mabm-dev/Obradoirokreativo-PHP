<?php
/*
 * agregarCarrito.php  -  Endpoint AJAX para anadir un articulo al carrito.
 * Responde en JSON (sin recargar la pagina). Lo usa el toast del catalogo.
 */
session_start();
require("BaseDatos.php");
header("Content-Type: application/json; charset=utf-8");

//Hace falta sesion iniciada.
if (!isset($_SESSION["usuario"])) {
	echo json_encode(["ok" => false, "error" => "sin-sesion"]);
	exit;
}

$usuario = $_SESSION["usuario"];
$id      = isset($_POST["id"])    ? (int) $_POST["id"]      : 0;
$Name    = isset($_POST["Name"])  ? $_POST["Name"]          : "";
$Price   = isset($_POST["Price"]) ? (float) $_POST["Price"] : 0;

if ($id <= 0 || $Name === "") {
	echo json_encode(["ok" => false, "error" => "datos"]);
	exit;
}

//El CarritoID y la cantidad se calculan en el servidor (evita colisiones).
$CarritoID = getNumTotalArtiCarrito("CarritoID") + 1;
$Cantidad  = getCantidadCarrito("ArticuloID", $id, $Name) + 1;

$conn = crearConexion();
$stmt = $conn->prepare("INSERT INTO carritodecompra (CarritoID, Usuario, ArticuloID, Name, Cantidad, Price) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isisid", $CarritoID, $usuario, $id, $Name, $Cantidad, $Price);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(["ok" => (bool) $ok, "name" => $Name]);
