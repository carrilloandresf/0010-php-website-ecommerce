# 0010-php-website-ecommerce
Un software basico para vender productos

## Levantar el proyecto

```bash
# 1. Clonar el repositorio
git clone <url-del-repo>
cd 0010-php-website-ecommerce

# 2. Construir la imagen y levantar el contenedor
docker compose up --build -d

# 3. Abrir en el navegador
# http://localhost:8080
```

## Detener el proyecto

```bash
docker compose down
```

## Reiniciar después de cambios en el código PHP

Los archivos PHP se recargan automáticamente en cada request (volumen montado).
Solo se necesita rebuild si se modifica el Dockerfile o apache.conf:

```bash
docker compose up --build -d
```
