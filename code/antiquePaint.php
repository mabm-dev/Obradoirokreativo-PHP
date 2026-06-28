<?php
$titulo = "Antique Paint - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
?>
    <h3>Antique Paint</h3><?php
    /*
     * Cargar un fichero de php para tener acceso a las funciones y/o clases que hay declaradas.
     */
    require("BaseDatos.php");
    //por defecto
    //orden de los Articulos.
    $orden = "intID";
    //orden general.
    $ordenGeneral ="ArticuloID";
    //orden Del Carrito.
    $ordenCarrito ="CarritoID";
    //categoria de los articulos.
    $Categoria = "Antique Paint";
    //Si existe orden en el enlace ponemos ese orden.
    if(isset($_GET["orden"])){
        $orden = $_GET["orden"];
    }
    //Mostramos en pantalla el orden numerico(solo arreglos internos).
    //echo "Orden: " . $orden . "<br>";
    //Si no existe pagina en el enlace ponemos pagina 1.
    if(!isset($_GET["pag"])){//paginar los productos listados
        $pag=1;
    }else{
        $pag = $_GET["pag"];
    }
    //Mostramos en pantalla el orden numerico(solo arreglos internos).
    //echo "Pag: " . $pag . "<br>";
    //Guardamos en una variable el numero de productos de la tabla
    $numArticulos = getNumArticulos($orden, $Categoria);
    $numArticulosTotal = getNumTotalArti($ordenGeneral);
    $numArticulosTotalCarrito = getNumTotalArtiCarrito($ordenCarrito);
    //resultados por pagina
    $resxPag = 10;
    //Paginacion de 10 filas como maximo en cada tabla,
    $numPagina = ceil($numArticulos/$resxPag);
    //Mostramos en pantalla el numero total de paginas(solo arreglos internos).
    //echo "NumPaginas: " . $numPagina;
    //Se muestran los datos de los productos en una tabla HTML
    $result =  getArticulos($pag, $orden, $Categoria);
    //Si hay resultado
    if ($result) { //Gesti�n de errores. ?>
        <table class="tabla listado"><?php
        if(isset($_SESSION["usuario"])){
    		if ($_SESSION["usuario"] == "Administrador"){?>
        <tr>
            <td>
            <form action="FormArticulo.php" method="post" enctype="multipart/form-data">
                <input name="id" type="hidden" value="<?php echo $numArticulosTotal+1;?>">
                <input name="intid" type="hidden" value="<?php echo $numArticulos+1;?>">
                <input name="Category" type="hidden" value="<?php echo $Categoria;?>">
                <input name="action" type="hidden" value="crearArticulo">
                <input type="submit" value="Crear Producto"></input>
            </form><?php }}?>
            </td>
        </tr>
        <tr><!-- Introducimos las Cabeceras de la tabla -->
        <?php if(isset($_SESSION["usuario"])){
        if ($_SESSION["usuario"] == "Administrador"){?>
            <td><a href="antiquePaint.php?pag=<?php echo $pag=1;?>&orden=intID">ID</a></td>
        <?php }} ?>   
            <td><a href="antiquePaint.php?pag=<?php echo $pag=1;?>&orden=Name">Nombre</a></td>
        <?php if(isset($_SESSION["usuario"])){
        if ($_SESSION["usuario"] == "Administrador"){?>   
            <td><a href="antiquePaint.php?pag=<?php echo $pag=1;?>&orden=Cost">Coste</a></td>
        <?php }} ?>    
            <td><a href="antiquePaint.php?pag=<?php echo $pag=1;?>&orden=Price">Precio</a></td>
                        <td><a href="antiquePaint.php?pag=<?php echo $pag=1;?>&orden=Img">Imagen</a></td>
        </tr><?php
        //Con el "fetch_assoc()" se obtiene un array asociativo y mueve el puntero de datos interno hacia adelante.
        while ($row = $result->fetch_assoc()) {?>
    	<tr><!-- Introducimos los datos de la tabla en cada fila -->
    	<?php if(isset($_SESSION["usuario"])){ 
    	if ($_SESSION["usuario"] == "Administrador"){?>	
    		<td><?php echo $row["ArticuloID"];?></td>
    	 <?php }} ?>	
    		<td><?php echo $row["Name"];?></td>
    	<?php if(isset($_SESSION["usuario"])){
    	if ($_SESSION["usuario"] == "Administrador"){?>	
    		<td><?php echo $row["Cost"]. " &euro;";?></td>
    	<?php }} ?>	
    		<td><?php echo $row["Price"]. " &euro;";?></td>
    		    		<td><img class="imagenes" src="data:image/jpg;base64,<?php echo base64_encode($row["Img"]);?>"></td>
    		<?php 
    		if(isset($_SESSION["usuario"])){
    		if ($_SESSION["usuario"] == "Administrador"){?>
    		<td>
            	<form action="FormArticulo.php" method="post" enctype="multipart/form-data">
            	    <input name="id" type="hidden" value="<?php echo $row["ArticuloID"];?>">
                	<input name="action" type="hidden" value="modify">
                	<input type="submit" value="Modificar"></input>
                </form>
           		<br>
                <form action="FormArticulo.php" method="post">
               		<input name="id" type="hidden" value="<?php echo $row["ArticuloID"];?>">
                	<input name="action" type="hidden" value="delete" >
                	<input type="submit" value="Eliminar"></input>
                </form>
            </td><?php }
            if ($_SESSION["usuario"] != "Administrador"){?>
            <td><?php  $arti=$row["ArticuloID"];
                       $orden="ArticuloID";
                       $nombre=$row["Name"];
                       $Cantidad = getCantidadCarrito($orden, $arti, $nombre); ?>
             	<form class="form-carrito" action="cesta.php" method="post">
             		<input type="hidden" name="CarritoID"  value="<?php echo $numArticulosTotalCarrito+1;?>">
               		<input type="hidden" name="usuario"  value="<?php echo $_SESSION["usuario"];?>">
               		<input type="hidden" name="id"  value="<?php echo $row["ArticuloID"];?>">
               		<input type="hidden" name="Name" value="<?php echo $row["Name"];?>">
               		<input type="hidden" name="Cantidad" value="<?php echo $Cantidad+1;?>">
                	<input type="hidden" name="Price" value="<?php echo $row["Price"];?>">
                	<input type="hidden" name="ubi" value="<?php echo "antiquePaint";?>">
                	<input name="action" type="hidden" value="agregarAlCarritoArti" >
                	<button type="submit" class="agregar"><i class="fa fa-shopping-bag"></i><span class="btn-txt">A&ntilde;adir</span></button>
                </form>
            </td><?php }}?> 
    	</tr><?php     
        }?>
        <tr>
            <td>PAGINA:
            	<?php 
            	//aqui mostramos y guardamos el numero de paginas totales de los productos y el orden para la 
            	//paginacion en todos los campos de producto
            	for($i=1;$i<=$numPagina;$i++){?>
            	<a href="antiquePaint.php?pag=<?php echo $i;?>&orden=<?php echo $orden;?>"><?php echo $i;?></a>
            	&nbsp;
            	<?php }
            	?>
            </td>
            <tr>
        </table><?php 
}
    include "templates/finPagina.php"; ?>