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

La aplicacion espera una base de datos MySQL con las tablas principales:

- `user`
- `articulo`
- `carritodecompra`
- `pedido`

El archivo `config.php` debe definir las constantes `HOST`, `USER`, `PASS` y `DATABASE`.

## Stripe

`configStripe.php` queda fuera del repositorio. La integracion esta planteada para modo test y requiere claves de Stripe propias.