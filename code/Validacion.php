<?php
/*
 * Procesamos el login ANTES de imprimir nada de HTML. Asi, si el acceso
 * es correcto, header("Location: ...") puede redirigir sin problemas.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("BaseDatos.php");

$email = isset($_POST["Email"]) ? trim($_POST["Email"]) : "";
$password = isset($_POST["Password"]) ? $_POST["Password"] : "";
$mensajeLogin = "";

if ($email !== "") {
    //login() devuelve true si valida; capturamos sus mensajes de error
    //para mostrarlos dentro de la pagina (no antes del HTML).
    ob_start();
    $isValidado = login($email, $password);
    $mensajeLogin = ob_get_clean();

    if ($isValidado) {
        header("Location: index.php");
        exit();
    }
}

//Datos de la pagina y hojas de estilo.
$titulo = "Login - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
?>
	<h3>Autenticaci&oacute;n</h3>
	<?php
	//Si se envio el formulario pero NO valido, mostramos el mensaje de error.
	if ($email !== "") {
		echo $mensajeLogin;
	} else {
		//Formulario de acceso.
		?>
		<form action="Validacion.php" method="post">
		<table class="tabla">
			<tr>
				<td class="izq"><label for="Email">Email</label><br></td>
				<td class="der"><input name="Email" type="text" required><br></td>
			</tr>
			<tr>
				<td class="izq"><label for="Password">Password</label><br></td>
				<td class="der"><input name="Password" type="password" required><br></td>
			</tr>
			<tr>
				<td><a href="form_registro.php"><input type="button" name="registrarse" value="Reg&iacute;strate"/></a></td>
				<td><input type="submit" name="enviar" value="Aceptar"></input></td>
			</tr>
		</table>
		</form>
	<?php
	}
	include "templates/finPagina.php";
?>
