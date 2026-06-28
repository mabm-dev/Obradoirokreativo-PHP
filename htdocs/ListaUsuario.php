<?php
$titulo = "Lista de Usuarios - Obradoiro Kreativo";
$estilos = ["base", "menu", "cabecera", "pie", "tablas-formularios"];
include "templates/inicioPagina.php";
?>
	<h3>Lista Usuarios</h3><?php
	require("BaseDatos.php");
	//por defecto
	$orden = "intID";
	$ordenGeneral = "UserID";
	if(isset($_GET["orden"])){ $orden = $_GET["orden"]; }
	if(!isset($_GET["pag"])){ $pag = 1; } else { $pag = $_GET["pag"]; }
	$numUsuarios  = getNumUsuarios($orden);
	$numUserTotal = getNumTotalUser($ordenGeneral);
	$resxPag = 10;
	$numPaginas = ceil($numUsuarios / $resxPag);
	$result = getUsuarios($pag, $orden);

	if ($result) { ?>
		<!-- Boton crear usuario (fuera de la tabla) -->
		<form class="barra-acciones" action="FormUsuario.php" method="post">
			<input name="id" type="hidden" value="<?php echo $numUserTotal+1;?>">
			<input name="intid" type="hidden" value="<?php echo $numUsuarios+1;?>">
			<input name="action" type="hidden" value="crearUsuario">
			<input type="submit" value="Crear Usuario">
		</form>

		<table class="tabla listado">
			<tr><!-- Cabeceras -->
				<td><a href="ListaUsuario.php?pag=1&orden=intID">ID</a></td>
				<td><a href="ListaUsuario.php?pag=1&orden=FullName">Nombre</a></td>
				<td><a href="ListaUsuario.php?pag=1&orden=Email">Email</a></td>
				<td><a href="ListaUsuario.php?pag=1&orden=LastAccess">&Uacute;ltimo Acceso</a></td>
				<td><a href="ListaUsuario.php?pag=1&orden=Enabled">Estado</a></td>
				<td colspan="2">Acciones</td>
			</tr><?php
			//Recorremos los usuarios.
			while ($row = $result->fetch_assoc()) {
				$claseFila = ($row["UserID"] == 1) ? "fila-super" : "";
				?>
				<tr class="<?php echo $claseFila; ?>">
					<td><?php echo $row["UserID"]; ?></td>
					<td><?php echo $row["FullName"]; ?></td>
					<td><?php echo $row["Email"]; ?></td>
					<td><?php echo $row["LastAccess"]; ?></td>
					<td><?php echo ($row["Enabled"] == 1)
							? '<span class="badge bloqueado">Bloqueado</span>'
							: '<span class="badge activo">Activo</span>'; ?></td>
					<td>
						<?php if($row["UserID"] == 1){ ?>
							<span class="sin-accion">&mdash;</span>
						<?php } else { ?>
							<form action="FormUsuario.php" method="post">
								<input name="id" type="hidden" value="<?php echo $row["UserID"]; ?>">
								<input name="action" type="hidden" value="modify">
								<input type="submit" value="Modificar">
							</form>
						<?php } ?>
					</td>
					<td>
						<?php if($row["UserID"] == 1){ ?>
							<span class="sin-accion">&mdash;</span>
						<?php } else { ?>
							<form action="FormUsuario.php" method="post">
								<input name="id" type="hidden" value="<?php echo $row["UserID"]; ?>">
								<input name="action" type="hidden" value="delete">
								<input type="submit" value="Eliminar">
							</form>
						<?php } ?>
					</td>
				</tr><?php
			} ?>
			<tr>
				<td colspan="7">PAGINA:
					<?php for($i=1; $i<=$numPaginas; $i++){ ?>
						<a href="ListaUsuario.php?pag=<?php echo $i;?>&orden=<?php echo $orden;?>"><?php echo $i;?></a>
						&nbsp;
					<?php } ?>
				</td>
			</tr>
		</table><?php
	}
	include "templates/finPagina.php"; ?>
