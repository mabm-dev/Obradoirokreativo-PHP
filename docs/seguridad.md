# Seguridad

Resumen de las medidas de seguridad aplicadas al proyecto (muchas fueron una mejora sobre
la versión inicial).

## Consultas preparadas (anti SQL-injection)

**Todas** las consultas a la base de datos usan *prepared statements* con `bind_param`, en
lugar de concatenar texto del usuario en el SQL.

```php
// Antes (vulnerable):
$query = "SELECT ... WHERE Email = '" . $email . "'";

// Ahora (seguro):
$stmt = $conn->prepare("SELECT ... WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

## Contraseñas con `password_hash()` (bcrypt)

Las contraseñas se guardan con **bcrypt** (`password_hash` / `password_verify`), no con
cifrado reversible.

Además, hay **migración automática**: si un usuario tenía la contraseña en el formato
antiguo (openssl o texto plano), se valida una vez y se reconvierte a bcrypt de forma
transparente en su siguiente inicio de sesión.

```php
if (password_verify($password, $stored)) { /* ok */ }
elseif (hash_equals($stored, openssl_encrypt($password, COD, KEY))) { /* legado -> migrar */ }
```

## Listas blancas en `ORDER BY` / `COUNT`

Los nombres de columna no se pueden parametrizar en `ORDER BY`. Para evitar inyección por
ahí, se validan contra una **lista blanca**.

```php
function columnaSegura($columna, array $permitidas, $defecto){
  return in_array($columna, $permitidas, true) ? $columna : $defecto;
}
```

## Datos de tarjeta: no se manejan ni se guardan

- El cobro lo gestiona **Stripe Checkout** (página alojada por Stripe): la web **nunca
  recibe** los datos de la tarjeta → cumplimiento PCI delegado en Stripe.
- En el flujo antiguo (eliminado) se llegó a blindar para **no almacenar el CVC** (lo
  prohíbe PCI-DSS) y **enmascarar** el número de tarjeta. Ahora directamente no se tocan.

## Secretos fuera del repositorio

Las credenciales y claves viven en archivos que están en `.gitignore` y **no se suben a
GitHub**:

- `config.php` → credenciales de la base de datos.
- `configStripe.php` → claves de Stripe.

El repositorio incluye solo las plantillas (`config.example.php`,
`configStripe.example.php`) para que cualquiera pueda configurarlo sin exponer datos.

## Control de acceso

- Sesiones PHP para diferenciar usuario anónimo, cliente y administrador.
- Bloqueo del usuario tras varios intentos fallidos de inicio de sesión.
