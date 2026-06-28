<!--cabecera de contenido general-->
    <header class="site-header">
        <!-- Fila superior: telefono y, si es admin, acceso a usuarios -->
        <div class="header-row">
            <a class="header-phone" href="tel:627973393"><i class="fa fa-phone"></i> 627 973 393</a>
            <?php if(isset($_SESSION["usuario"])){
            if ($_SESSION["usuario"] == "Administrador"){?>
                <form class="header-admin" action="ListaUsuario.php" method="post">
                    <input type="submit" value="Lista De Usuarios">
                </form>
            <?php }}?>
        </div>
        <!-- Marca: logo + nombre -->
        <div class="header-brand">
            <img class="brand-logo" alt="Logo Obradoiro Kreativo" src="img/logo.jpg">
            <div class="brand-text">
                <h2>Obradoiro Kreativo</h2>
                <span class="brand-tagline">Tienda &middot; Taller de Manualidades</span>
            </div>
        </div>
    </header>
