<?php
$titulo = "Pedido - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
/*
 * Cargar un fichero de php para tener acceso a las funciones y/o clases que hay declaradas.
 */
require("BaseDatos.php");
//usuario del carrito.
$UsuarioCarrito = $_SESSION["usuario"];?>
<h3>Metodo de Pago</h3><?php 
if(isset($_POST["action"])){
    //Se agrega al carrito la siguiente informacion  
    switch($_POST["action"]) {
        //casos de llamadas 
        case "hacerPedido":
            //si no existe la acci�n se muestra el error no definido
            if (!isset($_POST["action"])) {
                echo "Error: accion no definida";?><br>
        	<a href="index.php"> Volver</a> 
        	<?php exit();
            }
            agregarAlPedidoBD($_POST["NumPedido"], $_POST["Usuario"], $_POST["Total"]);
            break;
        case "actualizarPedido":
                //si no existe la acci�n se muestra el error no definido
                if (!isset($_POST["action"])) {
                    echo "Error: accion no definida";?><br>
        	<a href="index.php"> Volver</a> 
        	<?php exit();
            }
            actualizarPedidoBD($_POST["NumPedido"], $_POST["Usuario"], $_POST["Total"]);
            break;
        case "Pago":
            //si no existe la acci�n se muestra el error no definido
            if (!isset($_POST["action"])) {
                echo "Error: accion no definida";?><br>
        	<a href="index.php"> Volver</a> 
        	<?php exit();
            }
            agregarAlPedidoTarjetaBD($_POST["Usuario"], $_POST["nombreTarjeta"], $_POST["numTarjeta"], $_POST["FechaDeCaducidad"],
                                    $_POST["codSegTarjeta"], $_POST["Subtotal"]);
            break;
    }
}else {?>
<?php
$result=getListaPedido($UsuarioCarrito);
    //Si hay resultado
    if ($result) {  //Gesti�n de errores. 
	if($row = $result->fetch_assoc()){?>
	 <form action="pedido.php" method="post" enctype="multipart/form-data">
    	 <table class="tabla" border=1>
    	 	<tr><td class="izq">
    				<label for="Email">Email:</label>
    		</td><td class="der">	
    				<?php echo $row["Usuario"] ?>
    		</td></tr>
            <tr><td class="izq">
    				<label for="Total">Pedido:</label>
    		</td><td class="der">	
    				<?php echo $row["Total"] . " &euro; + Envio: 3 &euro; "?>
    		<tr><td class="izq">
    				<label for="Subtotal">Total:</label>
    		</td><td class="der">	
    				<?php echo $row["Total"]+3 . " &euro;"?>		
    		</td></tr>
    		<?php echo "<br><center><b><u>Pago Con Tarjeta</u></b><br><br></center>";?> 
            	<tr><td class="izq">
    				<label for="nombreTarjeta">Titular De Tarjeta</label>
    			</td><td class="der">	
    				<input name="nombreTarjeta" id="nombreTarjeta" type="text" required>
    			</td></tr>
    			<tr><td class="izq">		
    				<label for="numTarjeta">Numero De Tarjeta</label>
    			</td><td class="der">	
        			<input name="numTarjeta" id="numTarjeta" type="text" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="FechaDeCaducidad">Fecha De Caducidad</label>
    			</td><td class="der">	
        			<input name="FechaDeCaducidad" id="FechaDeCaducidad" placeholder="MM/YY" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="codSegTarjeta">CVC</label>
    			</td><td class="der">	
        			<input name="codSegTarjeta" id="codSegTarjeta" type="text" required>
        		</td></tr>
        		<tr><td class="izq">
        			<label for="Pagar">Pagar</label>
        			<i class="fa fa-arrow-right"></i>
        			<input name="Usuario" type="hidden" value="<?php echo $_SESSION["usuario"];?>" ></input>
        			<input name="Subtotal" type="hidden" value="<?php echo $row["Total"]+3;?>" ></input>
        			<input name="action" type="hidden" value="Pago" ></input>
            		<button type="submit" class="pagar" value="Pago"><i class="fa fa-credit-card"></i> Pagar</button>
            	</td><td class="der">	
            		<input type="reset" value="Restaurar">
            		<a href="cesta.php"><input type="button" name="volver" value="Volver"/></a>
            	</td></tr>
    	</table>	
	</form>
<?php }}}
include "templates/finPagina.php"; ?>