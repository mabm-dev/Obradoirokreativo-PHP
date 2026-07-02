<?php
/*
 * inicioPagina.php  -  Cabecera HTML comun a todas las paginas.
 *
 * Antes de incluir este archivo, define:
 *   $titulo   -> texto del <title>
 *   $estilos  -> array con las hojas de css/ (sin extension ni ruta)
 *                ej: ["base", "menu", "cabecera", "pie", "tablas-formularios"]
 * Opcionales:
 *   $metaDescripcion -> meta description (SEO)
 *   $metaKeywords    -> meta keywords (SEO)
 *   $verificacionGoogle -> contenido de google-site-verification
 *   $mostrarCarrusel = true -> incluye el carrusel bajo la cabecera
 */
if (!isset($titulo))  { $titulo  = "Obradoiro Kreativo"; }
if (!isset($estilos)) { $estilos = ["base", "menu", "cabecera", "pie"]; }
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="index, follow">
	<meta name="language" content="Spanish">
	<title><?php echo $titulo; ?></title>
<?php if (!empty($metaDescripcion)) { ?>	<meta name="description" content="<?php echo $metaDescripcion; ?>">
<?php } if (!empty($metaKeywords)) { ?>	<meta name="keywords" content="<?php echo $metaKeywords; ?>">
<?php } if (!empty($verificacionGoogle)) { ?>	<meta name="google-site-verification" content="<?php echo $verificacionGoogle; ?>">
<?php } ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
<?php
//Version para forzar la recarga del CSS cuando cambia (sube el numero al editar estilos).
$versionCss = "18";
foreach ($estilos as $hoja) { ?>	<link rel="stylesheet" href="css/<?php echo $hoja; ?>.css?v=<?php echo $versionCss; ?>">
<?php } ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
<!-- Aviso permanente: web formativa, no comercial (estilos en base.css) -->
<div class="demo-banner" role="note" aria-label="Aviso: web de demostraci&oacute;n con fines formativos">
	<span class="demo-banner-badge"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Proyecto formativo</span>
	<span class="demo-banner-text">Tienda de demostraci&oacute;n &mdash; los pedidos y pagos no son reales</span>
	<a class="demo-banner-link" href="https://github.com/mabm-dev/Obradoirokreativo-PHP" target="_blank" rel="noopener">
		<i class="fa fa-github" aria-hidden="true"></i> Ver c&oacute;digo
	</a>
</div>
<?php include "templates/nav.php"; ?>
<div id="content">
<?php
include "templates/header.php";
if (!empty($mostrarCarrusel)) { include "templates/carrusel.php"; }
?>
