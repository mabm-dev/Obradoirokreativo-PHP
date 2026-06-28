<?php
$titulo = "Registro - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
    ?><h3>Formulario De Registro</h3><?php 
/*
 * Cargar un fichero de php para tener acceso a las funciones y/o clases que hay declaradas.
 */
require("BaseDatos.php");
//Declaracion de variables
$email="";
$password="";
$orden="intID";
$UserID=getNumUsuarios($orden)+1;

    //Si existe email ponemos ese email.
    if(isset($_POST["Email"])){
        $email = $_POST["Email"];
    }
    //Si existe contrase�a ponemos esa contrase�a.
    if(isset($_POST["Password"])){
        $password = $_POST["Password"];
    }
    //Si existe contrase�a2 ponemos esa contrase�a.
    if(isset($_POST["Password2"])){
        $password2 = $_POST["Password2"];
    }
    //Si existe nombre ponemos esa nombre.
    if(isset($_POST["FullName"])){
        $FullName = $_POST["FullName"];
    }
    //Si existe cumplea�os ponemos esa cumplea�os.
    if(isset($_POST["BirthDate"])){
        $BirthDate = $_POST["BirthDate"];
    }
    //Si existe direccion ponemos esa direccion.
    if(isset($_POST["Address"])){
        $Address = $_POST["Address"];
    }
    //Si existe ciudad ponemos esa ciudad.
    if(isset($_POST["City"])){
        $City = $_POST["City"];
    }
    //Si existe codigo postal ponemos esa codigo postal.
    if(isset($_POST["PostalCode"])){
        $PostalCode = $_POST["PostalCode"];
    }
    //Si existe Pais ponemos esa contrase�a.
    if(isset($_POST["State"])){
        $State = $_POST["State"];
    }
    if($email==""){?>
        <form action="form_registro.php" method="post" onsubmit="return comprobar()" >
         <input name="id" type="hidden" value="<?php echo $UserID;?>"> 
            <table class="tabla">
    			<tr><td class="izq">
    				<label for="Email">Email</label>
    			</td><td class="der">	
    				<input name="Email" type="text" required>
    			</td></tr>
    			<tr><td class="izq">		
    				<label for="Password">Password</label>
    			</td><td class="der">	
        			<input name="Password" id="password" type="password" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="Password2">Repetir Password</label>
    			</td><td class="der">	
        			<input name="Password2" id="password2" type="password" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="FullName">Nombre</label>
    			</td><td class="der">	
        			<input name="FullName" id="FullName" type="text" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="BirthDate">Cumplea&ntilde;os</label>
    			</td><td class="der">	
        			<input name="BirthDate" id="BirthDate" type="date" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="Address">Direcci&oacute;n</label>
    			</td><td class="der">	
        			<input name="Address" id="Address" type="text" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="City">Ciudad</label><br>
    			</td><td class="der">	
        			<input name="City" id="City" type="text" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="PostalCode">Codigo Postal</label>
    			</td><td class="der">	
        			<input name="PostalCode" id="PostalCode" type="text" required>
        		</td></tr>
        		<tr><td class="izq">		
    				<label for="State">Pais</label>
    			</td><td class="der">	
        			<input name="State" id="State" type="text" required>
        		</td></tr>
        		<tr><td>
        			<a href="Validacion.php"><input type="button" name="volver" value="Volver"/></a>
        		</td><td>	
        			<input type="submit" name="enviar" value="Alta"></input>
        		</td></tr>		
        	</table>
        </form>
  	<?php
    }
    else{
        //Comprobar Registro
        $isRegistrado = registro($UserID, $email, $password, $password2, $FullName, $BirthDate, $Address, $City, $PostalCode, $State);
        if($isRegistrado){
            //volvemos a index.php una vez realizado.
            header("Location: index.php");
            echo"salida";
        }
    }
    include "templates/finPagina.php"; ?>
     