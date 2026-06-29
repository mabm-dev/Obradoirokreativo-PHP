# Seguridad y privacidad

Este repositorio esta preparado para portfolio y evita publicar informacion sensible.

No se versionan:

- `code/config.php`
- `code/configStripe.php`
- `.override`
- `.htaccess`
- archivos temporales o de sistema

Las credenciales reales deben configurarse solo en el entorno local o en el hosting.

## Contrasenas

El proyecto usa `password_hash()` para nuevas contrasenas y conserva compatibilidad con registros antiguos mediante migracion progresiva.

## Datos reales

No deben subirse bases de datos con clientes, pedidos, direcciones, telefonos ni credenciales reales.

## Consultas preparadas (anti SQL-injection)

Todas las consultas a la base de datos usan prepared statements con `bind_param`, en lugar
de concatenar texto del usuario en el SQL.

```php
$stmt = $conn->prepare("SELECT ... WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

## Migracion de contrasenas

Si un usuario tenia la contrasena en el formato antiguo (openssl o texto plano), se valida
una vez y se reconvierte a bcrypt de forma transparente en su siguiente inicio de sesion.

## Listas blancas en ORDER BY

Los nombres de columna no se pueden parametrizar en `ORDER BY` / `COUNT`. Para evitar
inyeccion por ahi, se validan contra una lista blanca.

## Datos de tarjeta

El cobro lo gestiona Stripe Checkout (pagina alojada por Stripe): la web nunca recibe los
datos de la tarjeta, delegando el cumplimiento PCI en Stripe. El CVC no se almacena nunca.