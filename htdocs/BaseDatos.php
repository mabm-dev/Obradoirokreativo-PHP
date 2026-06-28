<?php
/*
 * ============================================================
 *  CONFIGURACION DE LA BASE DE DATOS
 
 * ============================================================
 */
//Las credenciales viven en config.php (fuera de GitHub).
require_once __DIR__ . "/config.php";

/*
 * KEY/COD solo se conservan para poder verificar (y migrar) las
 * contrasenas antiguas que se guardaron con openssl. Las nuevas
 * se guardan con password_hash() (bcrypt), que es lo correcto.
 */
define("KEY", "servidor");
define("COD", "AES-128-ECB");

/*
 * Crea la conexion con la base de datos usando las constantes.
 */
function crearConexion() {
    $conn = mysqli_connect(HOST, USER, PASS, DATABASE);
    if (!$conn) {
        echo "<br>Connection error" . mysqli_connect_error();
        return null;
    }
    //utf8mb4 evita que los acentos y caracteres especiales se rompan.
    $conn->set_charset("utf8mb4");
    return $conn;
}

/*
 * Redirige de forma segura. Si las cabeceras aun no se han enviado usa
 * header(); si la pagina ya imprimio HTML (caso habitual aqui, porque el
 * menu y la cabecera se pintan antes), usa una redireccion por JavaScript.
 */
function redirigir($destino) {
    if (!headers_sent()) {
        header("Location: $destino");
    } else {
        echo "<script>window.location.href='" . $destino . "';</script>";
        echo "<noscript><a href='" . $destino . "'>Continuar</a></noscript>";
    }
    exit();
}

/*
 * Devuelve un nombre de columna seguro. Como los ORDER BY / COUNT /
 * MAX no se pueden pasar como parametro preparado, validamos el
 * nombre contra una lista blanca para evitar inyeccion SQL.
 */
function columnaSegura($columna, array $permitidas, $defecto) {
    return in_array($columna, $permitidas, true) ? $columna : $defecto;
}

//Columnas validas por tabla (lista blanca).
const COLS_USER = ["UserID", "intID", "BirthDate", "Email", "Address", "PostalCode", "Password", "City", "State", "FullName", "Enabled", "LastAccess"];
const COLS_ARTICULO = ["ArticuloID", "intID", "Name", "Cost", "Price", "Tematica", "Category", "Fecha", "Img"];
const COLS_CARRITO = ["CarritoID", "Usuario", "ArticuloID", "Name", "Cantidad", "Price"];

/*
 * Devuelve un usuario por su ID.
 */
function getUsuario($id) {
    $conn = crearConexion();
    $stmt = $conn->prepare("SELECT * FROM user WHERE UserID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row;
}

/*
 * Devuelve un articulo por ID y categoria.
 */
function getArticulo($id, $categoria) {
    $conn = crearConexion();
    $stmt = $conn->prepare("SELECT * FROM articulo WHERE ArticuloID = ? AND Category = ?");
    $stmt->bind_param("is", $id, $categoria);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row;
}

/*
 * Devuelve un articulo por su ID.
 */
function getArticulo1($id) {
    $conn = crearConexion();
    $stmt = $conn->prepare("SELECT * FROM articulo WHERE ArticuloID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row;
}

/*
 * Devuelve la lista de usuarios paginada.
 */
function getUsuarios($pag, $orden) {
    $conn = crearConexion();
    $orden = columnaSegura($orden, COLS_USER, "intID");
    $min_id = ($pag - 1) * 10;
    //El nombre de columna ya esta validado por lista blanca, el resto va parametrizado.
    $stmt = $conn->prepare("SELECT * FROM user WHERE intID > ? ORDER BY $orden LIMIT 10");
    $stmt->bind_param("i", $min_id);
    $stmt->execute();
    //No cerramos la conexion aqui: el resultado se recorre en la pagina que llama.
    return $stmt->get_result();
}

/*
 * Devuelve la lista de articulos paginada por categoria.
 */
function getArticulos($pag, $orden, $Categoria) {
    $conn = crearConexion();
    $orden = columnaSegura($orden, COLS_ARTICULO, "intID");
    $min_id = ($pag - 1) * 10;
    $stmt = $conn->prepare("SELECT * FROM articulo WHERE intID > ? AND Category = ? ORDER BY $orden LIMIT 10");
    $stmt->bind_param("is", $min_id, $Categoria);
    $stmt->execute();
    return $stmt->get_result();
}

/*
 * Devuelve todos los usuarios.
 */
function getUsers() {
    $conn = crearConexion();
    $result = $conn->query("SELECT * FROM user");
    return $result;
}

/*
 * Devuelve los articulos del carrito de un usuario.
 */
function getArticulosCarrito($UsuarioCarrito) {
    $conn = crearConexion();
    $stmt = $conn->prepare("SELECT * FROM carritodecompra WHERE Usuario = ? ORDER BY Name");
    $stmt->bind_param("s", $UsuarioCarrito);
    $stmt->execute();
    return $stmt->get_result();
}

/*
 * Devuelve el pedido de un usuario.
 */
function getListaPedido($UsuarioCarrito) {
    $conn = crearConexion();
    $stmt = $conn->prepare("SELECT * FROM pedido WHERE Usuario = ?");
    $stmt->bind_param("s", $UsuarioCarrito);
    $stmt->execute();
    return $stmt->get_result();
}

/*
 * Devuelve cuantas unidades de un articulo hay en el carrito.
 */
function getCantidadCarrito($orden, $arti, $nombre) {
    $conn = crearConexion();
    $orden = columnaSegura($orden, COLS_CARRITO, "CarritoID");
    $stmt = $conn->prepare("SELECT COUNT($orden) total FROM carritodecompra WHERE Name = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()["total"];
    $stmt->close();
    $conn->close();
    return $total;
}

/*
 * Devuelve el ID maximo de usuarios.
 */
function getNumTotalUser($ordenGeneral) {
    $conn = crearConexion();
    $ordenGeneral = columnaSegura($ordenGeneral, COLS_USER, "UserID");
    $result = $conn->query("SELECT MAX($ordenGeneral) total FROM user");
    $total = $result->fetch_assoc()["total"];
    $conn->close();
    return $total;
}

/*
 * Devuelve el ID maximo de articulos.
 */
function getNumTotalArti($ordenGeneral) {
    $conn = crearConexion();
    $ordenGeneral = columnaSegura($ordenGeneral, COLS_ARTICULO, "ArticuloID");
    $result = $conn->query("SELECT MAX($ordenGeneral) total FROM articulo");
    $total = $result->fetch_assoc()["total"];
    $conn->close();
    return $total;
}

/*
 * Devuelve el ID maximo de articulos del carrito.
 */
function getNumTotalArtiCarrito($ordenCarrito) {
    $conn = crearConexion();
    $ordenCarrito = columnaSegura($ordenCarrito, COLS_CARRITO, "CarritoID");
    $result = $conn->query("SELECT MAX($ordenCarrito) total FROM carritodecompra");
    $total = $result->fetch_assoc()["total"];
    $conn->close();
    return $total;
}

/*
 * Devuelve el numero de pedido maximo.
 */
function getNumTotalpedido() {
    $conn = crearConexion();
    $result = $conn->query("SELECT MAX(NumPedido) total FROM pedido");
    $total = $result->fetch_assoc()["total"];
    $conn->close();
    return $total;
}

/*
 * Devuelve el numero de usuarios (para la paginacion).
 */
function getNumUsuarios($orden) {
    $conn = crearConexion();
    $orden = columnaSegura($orden, COLS_USER, "intID");
    $result = $conn->query("SELECT COUNT($orden) total FROM user");
    $total = $result->fetch_assoc()["total"];
    $conn->close();
    return $total;
}

/*
 * Devuelve el numero de articulos de una categoria (para la paginacion).
 */
function getNumArticulos($orden, $Categoria) {
    $conn = crearConexion();
    $orden = columnaSegura($orden, COLS_ARTICULO, "intID");
    $stmt = $conn->prepare("SELECT COUNT($orden) total FROM articulo WHERE Category = ?");
    $stmt->bind_param("s", $Categoria);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()["total"];
    $stmt->close();
    $conn->close();
    return $total;
}

/*
 * Crea un usuario en la base de datos. La contrasena llega en texto
 * plano y aqui se convierte en hash seguro con password_hash().
 */
function creacionUsuarioBD($UserID, $intID, $cumple, $email, $direccion, $cp, $pass, $ciudad, $estado, $nombre, $enabled) {
    $conn = crearConexion();
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO user (UserID, intID, BirthDate, Email, Address, PostalCode, Password, City, State, FullName, Enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssssssi", $UserID, $intID, $cumple, $email, $direccion, $cp, $hash, $ciudad, $estado, $nombre, $enabled);
    $ok = $stmt->execute();
    $stmt->close();

    //Asignamos un numero de pedido a este usuario.
    $NumPedido = getNumTotalpedido() + 1;
    $Total = 0;
    $stmt = $conn->prepare("INSERT INTO pedido (NumPedido, Usuario, Total) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $NumPedido, $email, $Total);
    $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo "Registro Creado. <br>"; ?>
        <a href="ListaUsuario.php"><button>Volver</button></a><br>
    <?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Crea un articulo en la base de datos (la imagen se guarda como BLOB).
 */
function creacionArticuloBD($ArticuloID, $intID, $nombre, $coste, $precio, $Tematica, $categoria, $Fecha, $img) {
    $conn = crearConexion();
    $stmt = $conn->prepare("INSERT INTO articulo (ArticuloID, intID, Name, Cost, Price, Tematica, Category, Fecha, Img) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $null = null;
    $stmt->bind_param("iisddsssb", $ArticuloID, $intID, $nombre, $coste, $precio, $Tematica, $categoria, $Fecha, $null);
    //La imagen (BLOB) se envia aparte; el indice 8 es el parametro Img (0-based).
    $stmt->send_long_data(8, $img);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo "Registro Creado. <br>"; ?>
        <a href="index.php"><button>Inicio</button></a><br>
    <?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Actualiza un usuario. Si la contrasena llega vacia, NO se toca
 * (asi al modificar otros datos no se borra la contrasena actual).
 */
function actualizarUserBD($UserID, $intID, $cumple, $email, $direccion, $cp, $pass, $ciudad, $estado, $nombre, $enabled) {
    $conn = crearConexion();

    if ($pass !== "" && $pass !== null) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET UserID=?, intID=?, BirthDate=?, Email=?, Address=?, PostalCode=?, Password=?, City=?, State=?, FullName=?, Enabled=? WHERE UserID=?");
        $stmt->bind_param("iissssssssii", $UserID, $intID, $cumple, $email, $direccion, $cp, $hash, $ciudad, $estado, $nombre, $enabled, $UserID);
    } else {
        $stmt = $conn->prepare("UPDATE user SET UserID=?, intID=?, BirthDate=?, Email=?, Address=?, PostalCode=?, City=?, State=?, FullName=?, Enabled=? WHERE UserID=?");
        $stmt->bind_param("iisssssssii", $UserID, $intID, $cumple, $email, $direccion, $cp, $ciudad, $estado, $nombre, $enabled, $UserID);
    }

    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        $_SESSION['count'] = 0;
        echo "Registro modificado. <br>"; ?>
        <a href="ListaUsuario.php"><button>Volver</button></a><br>
    <?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Actualiza un articulo (la imagen se guarda como BLOB).
 */
function actualizarArticuloBD($ArticuloID, $intID, $nombre, $coste, $precio, $tematica, $categoria, $fecha, $img) {
    $conn = crearConexion();
    $stmt = $conn->prepare("UPDATE articulo SET ArticuloID=?, intID=?, Name=?, Cost=?, Price=?, Tematica=?, Category=?, Fecha=?, Img=? WHERE ArticuloID=?");
    $null = null;
    $stmt->bind_param("iisddsssbi", $ArticuloID, $intID, $nombre, $coste, $precio, $tematica, $categoria, $fecha, $null, $ArticuloID);
    $stmt->send_long_data(8, $img);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo "Registro modificado. <br>"; ?>
        <a href="index.php"><button>Inicio</button></a><br>
    <?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Elimina un usuario por ID.
 */
function borrarUsuarioBD($UserID) {
    $conn = crearConexion();
    $stmt = $conn->prepare("DELETE FROM user WHERE UserID = ?");
    $stmt->bind_param("i", $UserID);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo "Registro eliminado. <br>"; ?>
            <a href="ListaUsuario.php"><button>Volver</button></a><br>
        <?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Elimina un articulo por ID.
 */
function borrarArticuloBD($ArticuloID) {
    $conn = crearConexion();
    $stmt = $conn->prepare("DELETE FROM articulo WHERE ArticuloID = ?");
    $stmt->bind_param("i", $ArticuloID);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo "Registro eliminado. <br>"; ?>
            <a href="index.php"><button>Inicio</button></a><br><?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Anade un articulo al carrito.
 */
function agregarAlCarritoBDArti($CarritoID, $usuario, $ArticuloID, $Name, $Cantidad, $Price, $ubi) {
    $conn = crearConexion();
    $stmt = $conn->prepare("INSERT INTO carritodecompra (CarritoID, Usuario, ArticuloID, Name, Cantidad, Price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisid", $CarritoID, $usuario, $ArticuloID, $Name, $Cantidad, $Price);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        echo "Producto $Name agregado al carrito de compra.<br>"; ?>
       <a href="<?php echo htmlspecialchars($ubi, ENT_QUOTES) ?>.php"><button>Volver</button></a><br><?php
    } else {
        echo "Error query:" . $conn->error;
    }
    $conn->close();
}

/*
 * Quita un articulo del carrito.
 */
function quitarDelCarritoBD($orden, $Cantidad, $nombre) {
    $conn = crearConexion();
    if ($Cantidad > 0) {
        $stmt = $conn->prepare("DELETE FROM carritodecompra WHERE Name = ? AND Cantidad = ? LIMIT 1");
        $stmt->bind_param("si", $nombre, $Cantidad);
    } else {
        $stmt = $conn->prepare("DELETE FROM carritodecompra WHERE Name = ? LIMIT 1");
        $stmt->bind_param("s", $nombre);
    }
    $ok = $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($ok) {
        redirigir("cesta.php");
    } else {
        echo "Error al eliminar del carrito.";
    }
}

/*
 * Crea/añade el usuario y total al pedido.
 */
function agregarAlPedidoBD($NumPedido, $Usuario, $Total) {
    $conn = crearConexion();
    $stmt = $conn->prepare("INSERT INTO pedido (NumPedido, Usuario, Total) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $NumPedido, $Usuario, $Total);
    $ok = $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($ok) {
        redirigir("pedido.php");
    } else {
        echo "Error query:" . $conn->error;
    }
}

/*
 * Actualiza el total del pedido (si se cambio el carrito antes de pagar).
 */
function actualizarPedidoBD($NumPedido, $Usuario, $Total) {
    $conn = crearConexion();
    $stmt = $conn->prepare("UPDATE pedido SET NumPedido = ?, Total = ? WHERE Usuario = ?");
    $stmt->bind_param("ids", $NumPedido, $Total, $Usuario);
    $ok = $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($ok) {
        redirigir("pedido.php");
    } else {
        echo "Error query:" . $conn->error;
    }
}

/*
 * Registra los datos del pago en el pedido.
 *
 * SEGURIDAD / LEGAL: el codigo de seguridad de la tarjeta (CVC/CVV)
 * NO se puede almacenar nunca (lo prohibe el estandar PCI-DSS), por eso
 * aqui NO se guarda. Ademas, guardar el numero completo de tarjeta en
 * texto plano es muy peligroso: en una web real esto debe ir a traves
 * de una pasarela de pago (Stripe, Redsys, PayPal...) y la web no deberia
 * ver ni guardar nunca esos datos.
 */
function agregarAlPedidoTarjetaBD($Usuario, $nombreTarjeta, $numTarjeta, $FechaDeCaducidad, $codSegTarjeta, $Subtotal) {
    $conn = crearConexion();
    date_default_timezone_set('Europe/Madrid');
    $Fecha = date("Y-m-d");

    //Guardamos solo los 4 ultimos digitos como referencia, nunca la tarjeta completa ni el CVC.
    $ultimos4 = substr(preg_replace('/\D/', '', $numTarjeta), -4);
    $numTarjetaEnmascarada = "**** **** **** " . $ultimos4;

    $stmt = $conn->prepare("UPDATE pedido SET nombreTarjeta = ?, numTarjeta = ?, FechaDeCaducidad = ?, Fecha = ?, Subtotal = ? WHERE Usuario = ?");
    $stmt->bind_param("ssssds", $nombreTarjeta, $numTarjetaEnmascarada, $FechaDeCaducidad, $Fecha, $Subtotal, $Usuario);
    $ok = $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($ok) {
        echo "Pedido Realizado. Gracias por elegir Obradoiro Kreativo $Usuario. <br>"; ?>
            <a href="index.php"><button>Inicio</button></a><br> <?php
    } else {
        echo "Error query:" . $conn->error;
    }
}

/*
 * Autentica un usuario. Verifica la contrasena con password_verify()
 * y, si todavia tiene el formato antiguo (openssl), la migra a hash
 * seguro de forma transparente la proxima vez que entra.
 */
function login($email, $password) {
    $conn = crearConexion();
    $stmt = $conn->prepare("SELECT Password, Enabled, UserID FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        //Enabled = 1 significa que el usuario esta bloqueado.
        if ($row["Enabled"] == 1) {
            echo "SQL: El usuario est&aacute; deshabilitado<br>";
            ?><br><a href="index.php"><button>Inicio</button></a><br><?php
            $conn->close();
            return false;
        }

        $stored = $row["Password"];
        $valido = false;
        $necesitaRehash = false;

        //rtrim por si la columna Password es CHAR y MySQL la rellena con espacios.
        $stored = rtrim((string) $stored);

        if (password_verify($password, $stored)) {
            $valido = true;
            $necesitaRehash = password_needs_rehash($stored, PASSWORD_DEFAULT);
        } elseif (hash_equals($stored, (string) openssl_encrypt($password, COD, KEY))) {
            //Contrasena con el formato antiguo (openssl): la aceptamos y la migramos.
            $valido = true;
            $necesitaRehash = true;
        } elseif (hash_equals($stored, (string) $password)) {
            //Contrasena guardada en texto plano (cuenta antigua sembrada a mano): la aceptamos y la migramos.
            $valido = true;
            $necesitaRehash = true;
        }

        if ($valido) {
            //Migrar la contrasena al formato seguro si hace falta.
            if ($necesitaRehash) {
                $nuevoHash = password_hash($password, PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE user SET Password = ? WHERE Email = ?");
                $up->bind_param("ss", $nuevoHash, $email);
                $up->execute();
                $up->close();
            }
            //Establecer la sesion (ambas claves) y actualizar el ultimo acceso.
            $_SESSION["usuario"] = $email; //clave que usa nav.php / header.php
            $_SESSION["Usuario"] = $email; //compatibilidad
            $_SESSION['count'] = 0;
            date_default_timezone_set('Europe/Madrid');
            $lastaccess = date("Y-m-d");
            $up = $conn->prepare("UPDATE user SET LastAccess = ? WHERE Email = ?");
            $up->bind_param("ss", $lastaccess, $email);
            $up->execute();
            $up->close();
            $conn->close();
            //Devolvemos true: quien llama (Validacion.php) hace la redireccion
            //antes de imprimir HTML, por lo que header() funciona sin problemas.
            return true;
        } else {
            //Contador de intentos fallidos.
            if (!isset($_SESSION['count'])) {
                $_SESSION['count'] = 0;
            }
            ++$_SESSION['count'];
            //El superAdmin (UserID 1) nunca se bloquea.
            if ($row["UserID"] == 1) {
                $_SESSION['count'] = 1;
                echo "Intento de acceso con SuperAdmin, podr&aacute; intentarlo las veces que quiera...<br>";
            }

            if ($_SESSION['count'] < 3) {
                if ($row["UserID"] == 1) {
                    echo "Contrase&ntilde;a incorrecta superAdmin.<br>";
                    ?><br><a href="Validacion.php"><button>Volver</button></a><br><?php
                } else {
                    echo "Contrase&ntilde;a incorrecta, solo tiene 3 Intentos<br>";
                    echo "Intento " . $_SESSION['count'] . "...<br>";
                    ?><br><a href="Validacion.php"><button>Volver</button></a><br><?php
                }
            } else {
                //A los 3 intentos bloqueamos el usuario (Enabled = 1).
                $up = $conn->prepare("UPDATE user SET Enabled = 1 WHERE Email = ?");
                $up->bind_param("s", $email);
                $up->execute();
                $up->close();
                echo "Intento 3, Lo siento no tiene m&aacute;s intentos para loguearse comun&iacute;quelo a la empresa.<br>";
                ?><br><a href="index.php"><button>Inicio</button></a><br><?php
                $_SESSION['count'] = 0;
            }
        }
    } else {
        echo "Usuario no encontrado!<br>";
        ?><br><a href="Validacion.php"><button>Volver</button></a><br><?php
        $conn->close();
        return false;
    }

    $conn->close();
    return false;
}

/*
 * Registra un usuario nuevo desde el formulario publico.
 */
function registro($UserID, $email, $password, $password2, $FullName, $BirthDate, $Address, $City, $PostalCode, $State) {
    $conn = crearConexion();

    //Limpieza y validacion basica del email.
    $email = htmlentities(trim($email), ENT_NOQUOTES);

    //Las dos contrasenas deben coincidir (ademas de la validacion en JS).
    if ($password !== $password2) {
        echo "Las contrase&ntilde;as no coinciden.<br>";
        ?><br><a href="form_registro.php"><button>Volver</button></a><br><?php
        $conn->close();
        return false;
    }

    //Comprobar si el email ya existe.
    $stmt = $conn->prepare("SELECT UserID FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($existe) {
        echo "El usuario ya existe, prueba con otro.<br>";
        ?><br><a href="form_registro.php"><button>Volver</button></a><br><?php
        $conn->close();
        return false;
    }

    //Crear el usuario con la contrasena cifrada de forma segura.
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO user (UserID, Email, Password, FullName, BirthDate, Address, City, PostalCode, State, intID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssi", $UserID, $email, $hash, $FullName, $BirthDate, $Address, $City, $PostalCode, $State, $UserID);
    $stmt->execute();
    $stmt->close();

    //Asignar un numero de pedido a este usuario.
    $NumPedido = getNumTotalpedido() + 1;
    $Total = 0;
    $stmt = $conn->prepare("INSERT INTO pedido (NumPedido, Usuario, Total) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $NumPedido, $email, $Total);
    $stmt->execute();
    $stmt->close();

    echo "Bienvenido, usuario registrado, ya puedes iniciar sesi&oacute;n.<br>";
    ?><br><a href="Validacion.php"><button>Iniciar Sesi&oacute;n</button></a><br><?php

    $conn->close();
    return false;
}
?>
