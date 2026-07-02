-- ============================================================
-- database.sql  -  Esquema de la base de datos de Obradoiro Kreativo
-- (MySQL / MariaDB, utf8mb4)
--
-- Estructura exportada de la base de datos real (MariaDB 11.4,
-- phpMyAdmin) con cuatro ajustes deliberados respecto a produccion:
--
--   1. pedido.Usuario y carritodecompra.Usuario se amplian a
--      varchar(100) para igualar a user.Email: con los tamanos
--      originales (20/25) los emails largos rompen la clave
--      foranea al crear el pedido en el registro.
--   2. Las columnas del pago con tarjeta de `pedido` admiten NULL
--      (pertenecen al checkout antiguo, sustituido por Stripe) y
--      Enabled/Total tienen DEFAULT: asi las INSERT parciales del
--      codigo funcionan tambien en MySQL con modo estricto.
--   3. user.Address usa utf8mb4 (la tabla real arrastra una
--      collation cp1250_bin accidental).
--   4. Los productos de ejemplo llevan Img = '' porque la columna
--      es un BLOB NOT NULL; las fotos se suben desde el panel de
--      administracion (FormArticulo.php).
--
-- Puesta en marcha:
--   1. Crea una base de datos vacia (ej.: obradoiro_kreativo).
--   2. Importa este archivo:
--        mysql -u root -p obradoiro_kreativo < database.sql
--      (o desde phpMyAdmin: pestana Importar)
--   3. Copia htdocs/config.example.php como htdocs/config.php y
--      pon el nombre de tu base de datos y credenciales.
--
-- Datos de ejemplo incluidos:
--   - Admin:        usuario "Administrador"   contrasena "admin123"
--   - Cliente demo: usuario "demo@demo.com"   contrasena "demo123"
--     Las contrasenas van en texto plano a proposito: login()
--     las migra automaticamente a hash bcrypt en el primer acceso.
--   - Productos de muestra en las 6 categorias.
-- ============================================================

SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- user: cuentas de clientes y administrador.
-- Enabled: 0 = activo, 1 = bloqueado (tras 3 intentos fallidos).
-- El usuario con UserID = 1 es el superAdmin y nunca se bloquea.
-- ------------------------------------------------------------
CREATE TABLE `user` (
  `UserID`     int(11)      NOT NULL,
  `BirthDate`  date         DEFAULT NULL,
  `Email`      varchar(100) NOT NULL,
  `Address`    varchar(100) DEFAULT NULL,
  `PostalCode` varchar(5)   DEFAULT NULL,
  `Password`   varchar(255) DEFAULT NULL,
  `City`       varchar(50)  DEFAULT NULL,
  `State`      varchar(50)  DEFAULT NULL,
  `FullName`   varchar(50)  NOT NULL,
  `LastAccess` date         DEFAULT NULL,
  `Enabled`    int(1)       NOT NULL DEFAULT 0,
  `intID`      int(4)       NOT NULL,
  PRIMARY KEY (`UserID`),
  KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- articulo: catalogo de productos, clases y talleres.
-- Img es la foto en binario (se muestra inline en base64).
-- ------------------------------------------------------------
CREATE TABLE `articulo` (
  `ArticuloID` int(11)       NOT NULL,
  `Name`       varchar(50)   NOT NULL,
  `Cost`       double(10,2)  NOT NULL,
  `Price`      double(10,2)  NOT NULL,
  `Category`   varchar(25)   NOT NULL,
  `Tematica`   varchar(20)   NOT NULL,
  `Fecha`      date          DEFAULT NULL,
  `Img`        mediumblob    NOT NULL,
  `intID`      int(4)        NOT NULL,
  PRIMARY KEY (`ArticuloID`),
  KEY `Price` (`Price`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- carritodecompra: articulos en la cesta (una fila por unidad;
-- Usuario es el email de la sesion). Las claves foraneas exigen
-- que el usuario y el articulo existan.
-- ------------------------------------------------------------
CREATE TABLE `carritodecompra` (
  `CarritoID`  int(10)       NOT NULL,
  `Usuario`    varchar(100)  NOT NULL,
  `ArticuloID` int(11)       NOT NULL,
  `Name`       varchar(50)   NOT NULL,
  `Cantidad`   int(3)        NOT NULL,
  `Price`      double(10,2)  NOT NULL,
  PRIMARY KEY (`CarritoID`),
  KEY `Usuario` (`Usuario`),
  KEY `Name` (`Name`),
  KEY `Price` (`Price`),
  KEY `ArticuloID` (`ArticuloID`),
  CONSTRAINT `FK_carrito_usuario`   FOREIGN KEY (`Usuario`)    REFERENCES `user` (`Email`)        ON UPDATE CASCADE,
  CONSTRAINT `FK_carrito_producto`  FOREIGN KEY (`ArticuloID`) REFERENCES `articulo` (`ArticuloID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_carrito_prodname`  FOREIGN KEY (`Name`)       REFERENCES `articulo` (`Name`)       ON UPDATE CASCADE,
  CONSTRAINT `FK_carrito_prodPrice` FOREIGN KEY (`Price`)      REFERENCES `articulo` (`Price`)      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- pedido: un pedido por usuario (se crea al registrarse, se
-- actualiza al pagar). Las columnas de tarjeta pertenecen al
-- checkout antiguo; con Stripe quedan sin uso (por eso NULL).
-- ------------------------------------------------------------
CREATE TABLE `pedido` (
  `NumPedido`        int(11)       NOT NULL,
  `Usuario`          varchar(100)  NOT NULL,
  `Fecha`            date          DEFAULT NULL,
  `Total`            double(10,2)  NOT NULL DEFAULT 0.00,
  `Subtotal`         double(10,2)  DEFAULT NULL,
  `nombreTarjeta`    varchar(30)   DEFAULT NULL,
  `numTarjeta`       varchar(30)   DEFAULT NULL,
  `FechaDeCaducidad` varchar(10)   DEFAULT NULL,
  `codSegTarjeta`    int(3)        DEFAULT NULL,
  PRIMARY KEY (`NumPedido`),
  KEY `Usuario` (`Usuario`),
  CONSTRAINT `FK_pedido_usuario` FOREIGN KEY (`Usuario`) REFERENCES `user` (`Email`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- DATOS DE EJEMPLO
-- ============================================================

-- Usuarios (contrasena en texto plano: se migra a bcrypt al entrar).
INSERT INTO `user` (`UserID`, `intID`, `BirthDate`, `Email`, `Address`, `PostalCode`, `Password`, `City`, `State`, `FullName`, `Enabled`) VALUES
(1, 1, '1980-01-01', 'Administrador', 'Rua do Obradoiro 1', '15001', 'admin123', 'A Coruna', 'Galicia', 'Administrador', 0),
(2, 2, '1990-05-15', 'demo@demo.com', 'Rua Demo 2', '15002', 'demo123', 'A Coruna', 'Galicia', 'Cliente Demo', 0);

-- Pedido inicial de cada usuario (igual que hace el registro).
INSERT INTO `pedido` (`NumPedido`, `Usuario`, `Total`) VALUES
(1, 'Administrador', 0),
(2, 'demo@demo.com', 0);

-- Productos de muestra (Img = '': sube las fotos desde el admin).
INSERT INTO `articulo` (`ArticuloID`, `intID`, `Name`, `Cost`, `Price`, `Category`, `Tematica`, `Fecha`, `Img`) VALUES
(1,  1,  'Antique Paint Blanco Cadence',                 3.20, 5.95,  'Antique Paint',     'Chalk paint',       '2026-06-01', ''),
(2,  2,  'Antique Paint Burdeos Cadence',                3.20, 5.95,  'Antique Paint',     'Chalk paint',       '2026-06-01', ''),
(3,  3,  'Antique Paint Verde Cadence',                  3.20, 5.95,  'Antique Paint',     'Chalk paint',       '2026-06-01', ''),
(4,  4,  'Dora Metalica Oro Cadence',                    4.10, 7.50,  'Dora Metalica',     'Dorado metalizado', '2026-06-01', ''),
(5,  5,  'Dora Metalica Plata Cadence',                  4.10, 7.50,  'Dora Metalica',     'Dorado metalizado', '2026-06-01', ''),
(6,  6,  'Acrilexa Pintura 3D Brillante Amarillo',       2.40, 4.25,  'Pinturas Y Pastas', 'Acrilicos',         '2026-06-01', ''),
(7,  7,  'Acrilexa Pintura 3D Brillante Naranja',        2.40, 4.25,  'Pinturas Y Pastas', 'Acrilicos',         '2026-06-01', ''),
(8,  8,  'Acrilexa Pintura Menta',                       2.40, 4.25,  'Pinturas Y Pastas', 'Acrilicos',         '2026-06-01', ''),
(9,  9,  'Vintage Legend Azul Oscuro',                   3.80, 6.75,  'Vintage Legend',    'Efecto vintage',    '2026-06-01', ''),
(10, 10, 'Vintage Legend Marron',                        3.80, 6.75,  'Vintage Legend',    'Efecto vintage',    '2026-06-01', ''),
(11, 11, 'Clases de Calceta - Enero',                    0.00, 25.00, 'Clases',            'Formacion',         '2026-01-10', ''),
(12, 12, 'Clases de Pintura Acuarela y Oleo - Diciembre',0.00, 30.00, 'Clases',            'Formacion',         '2025-12-05', ''),
(13, 13, 'Taller de Decoracion de Muebles',              0.00, 35.00, 'Talleres',          'Formacion',         '2026-02-14', ''),
(14, 14, 'Taller de Scrapbooking',                       0.00, 20.00, 'Talleres',          'Formacion',         '2026-03-07', '');
