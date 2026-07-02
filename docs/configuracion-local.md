# Configuracion local

El proyecto utiliza archivos de configuracion externos para no publicar credenciales en GitHub.

## Archivos necesarios

Copia las plantillas incluidas en `code/`:

```bash
cp code/config.example.php code/config.php
cp code/configStripe.example.php code/configStripe.php
```

Despues rellena los datos reales de tu entorno local o hosting.

## Base de datos

Importa el archivo [`database.sql`](../database.sql) de la raiz del repositorio en una
base de datos MySQL vacia. Crea las 4 tablas con sus claves foraneas:

- `user`
- `articulo`
- `carritodecompra`
- `pedido`

E incluye datos de ejemplo: un administrador (`Administrador` / `admin123`), un
cliente (`demo@demo.com` / `demo123`) y productos de muestra. Las contrasenas de
ejemplo van en texto plano y `login()` las migra a hash bcrypt en el primer acceso.

El archivo `config.php` debe definir las constantes `HOST`, `USER`, `PASS` y `DATABASE`.

## Stripe

`configStripe.php` queda fuera del repositorio. La integracion funciona en modo test
con claves propias (`pk_test_...` y `sk_test_...`, del panel de Stripe en modo de
prueba). Compra de prueba: tarjeta `4242 4242 4242 4242`, caducidad futura y CVC
cualquiera. No usa webhooks: el pago se verifica consultando la API desde el servidor,
por lo que funciona tambien en hostings gratuitos.