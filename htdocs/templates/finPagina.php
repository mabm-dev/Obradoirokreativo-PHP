<?php
/*
 * finPagina.php  -  Cierre HTML comun a todas las paginas.
 *
 * Opcional antes de incluir:
 *   $scriptsExtra -> cadena HTML con scripts adicionales
 *                    (p. ej. los de React en la portada)
 *
 * El footer va FUERA de #content para que ocupe todo el ancho.
 */
?>
</div><!-- fin #content -->
<?php
include "templates/footer.php";
if (!empty($scriptsExtra)) { echo $scriptsExtra; }
?>
</body>
</html>
