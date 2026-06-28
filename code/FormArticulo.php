<?php
$titulo = "Articulos - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
/*
 * Cargar un fichero de php para tener acceso a las funciones y/o clases que hay declaradas.
 */
require("BaseDatos.php");
//si no existe la acci�n se muestra el error no definido
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
//(solo arreglos internos)
//echo "Accion POST:" . $_POST["action"] . "<br>";
//echo "Id POST:" . $_POST["id"] . "<br>";
//creamos un Switch case para cada accion que vamos a realizar y asi conectar cada funcion.
switch($_POST["action"]) {
    case "crearArticulo":
        newArticulo();
        break;
    case "crearArticuloBD":
        creacionArticuloBD($_POST["id"], $_POST["intid"], $_POST["Name"], $_POST["Cost"], $_POST["Price"],  $_POST["Tematica"],
        $_POST["Category"], $_POST["Fecha"], file_get_contents($_FILES["Img"]["tmp_name"]));
        break;
    case "modify":
        modificarFormArticulo();
        break;
    case "modifyBD":
        actualizarArticuloBD($_POST["id"], $_POST["intid"], $_POST["Name"], $_POST["Cost"], $_POST["Price"],  $_POST["Tematica"],
        $_POST["Category"], $_POST["Fecha"], file_get_contents($_FILES["Img"]["tmp_name"]));
        break;
    case "delete":
        borrarFormArticulo();
        break;
    case "deleteBD":
        borrarArticuloBD($_POST["id"]);
        break;
}
/*
 * Muestra los campos del articulo para crearlo.
 */

function newArticulo() {
    echo "<br><center><b><u>Crear Producto</u></b><br><br></center>";?>
        <form action="FormArticulo.php" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="action" value="crearArticuloBD" >
    		<input type="hidden" name="id" value="<?php echo $_POST["id"];?>"></input>
    		<input type="hidden" name="intid" value="<?php echo $_POST["intid"];?>"></input>
    		<table class="tabla" >
    			<tr><td class="izq">
            		<input type="text" name="Name" value=""></input>
            	</td><td class="der">		
            		<label for="Nombre">Nombre</label>
            	</td></tr>
    			<tr><td class="izq">		
            		<input type="text" name="Cost" value=""></input>
            	</td><td class="der">		
            		<label for="Coste">Coste</label>
            	</td></tr>
    			<tr><td class="izq">		
            		<input type="text" name="Price" value=""></input>
            	</td><td class="der">		
            		<label for="Precio">Precio</label>
            	</td></tr>
            	<tr><td class="izq">		
            		<input type="text" name="Tematica" value=""></input>
            	</td><td class="der">		
            		<label for="Tematica">Tematica</label>
            	</td></tr>
    			<tr><td class="izq">		
            		<input type="text" name="Category" value="<?php echo $_POST["Category"];?>"></input>
            	</td><td class="der">		
            		<label for="Categoria">Categoria</label>
            	</td></tr>
            	<tr><td class="izq">		
            		<input type="date" name="Fecha" value=""></input>
            	</td><td class="der">		
            		<label for="Fecha">Fecha 'YYYY-MM-DD'</label>>
            	</td></tr>
    			<tr><td class="izq">		
            		<label for="Img">Inserta Imagen Del Producto:</label>
            	</td><td class="der">		
            		<input type="file" name="Img" value="hidden" required>
            	</td></tr>
    			<tr><td>		
            		<input type="submit" value = "Guardar"></input>
            	</td><td>		
            		<!-- Boton tipo submit para volver atras o cancelar -->
            		<input type="button" onclick="history.back()" name="Cancelar" value="Cancelar"></input>
    			</td></tr>
    		</table>
    	</form><?php 
    }
/*
 * Muestra los campos del articulo para modificarlo
 */
function modificarFormArticulo() {
    $articulo = getArticulo1($_POST["id"]); //Se obtienen todos los campos del articulo indicado.
    echo "<br><center><b><u>Modificar Articulo</u></b><br><br></center>";?>
        <form action="FormArticulo.php" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="action" value="modifyBD" ></input>
    		<input type="hidden" name="id" value="<?php echo $articulo["ArticuloID"];?>"></input>
    		<input type="hidden" name="intid" value="<?php echo $articulo["intID"];?>"></input>
    		<table class="tabla" >
    			<tr><td class="izq">
            		<input type="text" name="Name" value="<?php echo $articulo["Name"];?>"></input>
            	</td><td class="der">	
            		<label for="Nombre">Nombre</label>
            	</td></tr>
    			<tr><td class="izq">	
            		<input type="text" name="Cost" value="<?php echo $articulo["Cost"];?>"></input>
            	</td><td class="der">	
            		<label for="Coste">Coste</label>
            	</td></tr>
    			<tr><td class="izq">	
            		<input type="text" name="Price" value="<?php echo $articulo["Price"];?>"></input>
            	</td><td class="der">	
            		<label for="Precio">Precio</label>
            	</td></tr>
            	<tr><td class="izq">	
            		<input type="text" name="Tematica" value="<?php echo $articulo["Tematica"];?>" >
            	</td><td class="der">	
            		<label for="tematica">Tematica</label><br>
            	</td></tr>
    			<tr><td class="izq">	
            		<input type="text" name="Category" value="<?php echo $articulo["Category"];?>"></input>
            	</td><td class="der">	
            		<label for="Categoria">Categoria</label>
            	</td></tr>
            	<tr><td class="izq">	
            		<input type="date" name="Fecha" value="<?php echo $articulo["Fecha"];?>" >
            	</td><td class="der">	
            		<label for="fecha">Fecha 'YYYY-MM-DD'</label><br><br>
            	</td></tr>
    			<tr><td class="izq">	
            		<label for="Img">Inserta Imagen Del Producto:</label>
            	</td><td class="der">	
            		<input type="file" name="Img" value="" required></input>
            	</td></tr>
    			<tr><td>	
            		<input type="submit" value = "Guardar"></input>
            	</td><td>	
            		<!-- Boton tipo submit para volver atras o cancelar -->
            		<input type="button" onclick="history.back()" name="Cancelar" value="Cancelar"></input>
    			</td></tr>
    		</table>
    	</form><?php 
    }
/*
 * Muestra las opciones para borrar el articulo.
 */
function borrarFormArticulo() {
    $articulo = getArticulo1($_POST["id"]); //Se obtienen todos los campos del articulo indicado.
    echo "<br><center><b><u>Borrar Articulo</u></b><br><br></center>";?>
    <table class="tabla" >
    <tr><td colspan="2">&iquest;Borrar Articulo?</td></tr>
    <tr style="margin-top:20px;">
    	<td>
        	<form action="FormArticulo.php" method="post" enctype="multipart/form-data">
           		<input type="hidden" name="action" value="deleteBD" >
           		<input type="hidden" name="id" value="<?php echo $articulo["ArticuloID"];?>">
           		<input type="submit" value = "Si">
        	</form>
    	</td>
    	<td>
    		<form action="index.php" method="post">
       			<input type="submit" value = "No">
    		</form>
    	</td>
    </tr>
    </table><?php
}
include "templates/finPagina.php"; ?>