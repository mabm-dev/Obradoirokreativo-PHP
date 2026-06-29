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