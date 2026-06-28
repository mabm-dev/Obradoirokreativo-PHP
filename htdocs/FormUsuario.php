<?php
$titulo = "Usuarios - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
/*
 * Cargar un fichero de php para tener acceso a las funciones y/o clases que hay declaradas.
 */
require("BaseDatos.php");
//si no existe la acci�n se muestra el error no definido.
if (!isset($_POST["action"])) {
echo "Error: accion no definida";?><br>
    <a href="index.php"> Volver</a>
    <?php exit();
}
//si no existe el id se muestra el error no definido.
if (!isset($_POST["id"])) {
    echo "Error: id no definido";?><br>
    <a href="index.php"> Volver</a>
    <?php exit();
}
//se muestra por pantalla que accion y que id estamos consultando, modificando, creando o eliminando.
//(solo arreglos internos).
//echo "Accion POST:" . $_POST["action"] . "<br>";
//echo "Id POST:" . $_POST["id"] . "<br>";
//creamos un Switch case para cada accion que vamos a realizar y asi conectar cada funcion.
switch($_POST["action"]) {
    case "crearUsuario":
        newUsuario();
        break;
    case "crearUsuarioBD":
        creacionUsuarioBD($_POST["id"], $_POST["intid"], $_POST["BirthDate"], htmlentities(trim($_POST["Email"]), ENT_NOQUOTES), $_POST["Address"], $_POST["PostalCode"],
            $_POST["Password"], $_POST["City"], $_POST["State"], $_POST["FullName"], $_POST["Enabled"]);
        break;
    case "modify":
        modificarFormUsuario();
        break;
    case "modifyBD":
        actualizarUserBD($_POST["id"], $_POST["intid"], $_POST["BirthDate"], htmlentities(trim($_POST["Email"]), ENT_NOQUOTES), $_POST["Address"], $_POST["postalCode"],
            $_POST["Password"], $_POST["City"], $_POST["State"], $_POST["FullName"], $_POST["Enabled"]);
        break;
    case "delete":
        borrarFormUsuario();
        break;
    case "deleteBD":
        borrarUsuarioBD($_POST["id"]);
        break;
}
/*
 * Muestra los campos del usuario para modificarlo
 */
function newUsuario() {
    echo "<br><center><b><u>Crear Usuario</u></b><br><br></center>";?>
    <form action="FormUsuario.php" method="post">
		<input type="hidden" name="action" value="crearUsuarioBD" ></input>
        <input type="hidden" name="id" value="<?php echo $_POST["id"];?>"></input>
        <input type="hidden" name="intid" value="<?php echo $_POST["intid"];?>"></input>
        	<table class="tabla">	
        	<tr><td class="izq">
        		<input type="text" name="FullName" value="" ></input>
        	</td><td class="der">	
        		<label for="nombre">Nombre</label>
        	</td></tr>
    		<tr><td class="izq">	
        		<input type="text" name="Email" value="" ></input>
        	</td><td class="der">	
        		<label for="email">Email</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="password" name="Password" value="" ></input>
        	</td><td class="der">	
        		<label for="pass">Password</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="date" name="BirthDate" value="" ></input>
        	</td><td class="der">	
        		<label for="cumple">Cumplea&ntilde;os</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="text" name="Address" value="" ></input>
        	</td><td class="der">	
        		<label for="direccion">Direcci&oacute;n</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="text" name="PostalCode" value="" ></input>
        	</td><td class="der">	
        		<label for="cp">Codigo Postal</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="text" name="City" value="" ></input>
        	</td><td class="der">	
        		<label for="cidudad">Ciudad</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="text" name="State" value="" ></input>
        	</td><td class="der">	
        		<label for="estado">Estado</label>
        	</td></tr>
        	<tr><td class="izq">	
        		<input type="text" name="Enabled" value="" ></input>
        	</td><td class="der">	
        		<label for="enabled">Enabled</label>
        	</td></tr>
        	<tr><td>	
        		<input type="submit" value = "Aceptar"></input>
        	</td><td>	
        		<!-- Boton tipo submit para volver atras o cancelar -->
        		<a href="ListaUsuario.php"><input type="button" name="Cancelar" value="Cancelar"></input></a>  		
    		</td></tr>
    		</table>
    	</form>    
        <?php 
    }
/*
 * Muestra los campos del usuario para modificarlo
 */
function modificarFormUsuario() {
    $usuario = getUsuario($_POST["id"]); //Se obtienen todos los campos del usuario indicado.
    echo "<br><center><b><u>Modificar Usuario</u></b><br><br></center>";?>
    	<form action="FormUsuario.php" method="post">
    		<input type="hidden" name="action" value="modifyBD" >
    		<input type="hidden" name="id" value="<?php echo $usuario["UserID"];?>">
    		<input type="hidden" name="intid" value="<?php echo $usuario["intID"];?>">
    		<table class="tabla">
    			<tr><td class="izq">
    					<input type="text" name="FullName" value="<?php echo $usuario["FullName"];?>" >
    			</td><td class="der">
    					<label for="nombre">Nombre</label>
    			</td></tr>
    			<tr><td class="izq">
    					<input type="text" name="Email" value="<?php echo $usuario["Email"];?>" >
    			</td><td class="der">
    					<label for="email">Email</label>
    			</td></tr>
    			<tr><td class="izq">
    					<input type="password" name="Password" value="" >
                </td><td class="der">		
                		<label for="pass">Nueva Password</label>
    			</td></tr>
    			<tr><td class="izq">
                		<input type="date" name="BirthDate" value="<?php echo $usuario["BirthDate"];?>" >
                </td><td class="der">		
                		<label for="nombre">Cumplea&ntilde;os</label>
    			</td></tr>
    			<tr><td class="izq">
    					<input type="text" name="Address" value="<?php echo $usuario["Address"];?>" >
    			</td><td class="der">
    					<label for="direccion">Direcci&oacute;n</label>
    			</td></tr>
    			<tr><td class="izq">
                		<input type="text" name="postalCode" value="<?php echo $usuario["PostalCode"];?>" >
                </td><td class="der">		
                		<label for="cp">Codigo Postal</label>
    			</td></tr>
                <tr><td class="izq">		
                		<input type="text" name="City" value="<?php echo $usuario["City"];?>" >
                </td><td class="der">		
                		<label for="cidudad">Ciudad</label>
    			</td></tr>
    			<tr><td class="izq">
                		<input type="text" name="State" value="<?php echo $usuario["State"];?>" >
                </td><td class="der">		
                		<label for="estado">Estado</label>
    			</td></tr>
                <tr><td class="izq">		
                		<input type="text" name="Enabled" value="<?php echo $usuario["Enabled"];?>" >
                </td><td class="der">		
                		<label for="enabled">Enabled</label>
    			</td></tr>
    		 	<tr><td>
    					<input type="submit" value = "Aceptar">
    	        </td><td>      
    	                <!-- Boton tipo submit para volver atras o cancelar -->
    					<a href="ListaUsuario.php"><input type="button" name="Cancelar" value="Cancelar"></input></a>
    			</td></tr>
    		</table>	
    	</form>    
        <?php 
    }
/*
 * Muestra las opciones para borrar el usuario.
 */
function borrarFormUsuario() {
    $usuario = getUsuario($_POST["id"]); //Se obtienen todos los campos del usuario indicado.
    echo "<br><center><b><u>Borrar Usuario</u></b><br><br></center>";?>
    <table class="tabla">
    <tr><td colspan="2">&iquest;Borrar Usuario?</td></tr>
    <tr style="margin-top:20px;">
    	<td>
        	<form action="FormUsuario.php" method="post">
           		<input type="hidden" name="action" value="deleteBD" >
           		<input type="hidden" name="id" value="<?php echo $usuario['UserID'];?>">
           		<input type="submit" value = "Si">
        	</form>
    	</td>
    	<td>
    		<form action="ListaUsuario.php" method="post">
       			<input type="submit" value = "No">
    		</form>
    	</td>
    </tr>
    </table><?php 
} 
     include "templates/finPagina.php"; ?>