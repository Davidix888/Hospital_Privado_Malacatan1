# Docker Setup (Laravel + PostgreSQL)

Este proyecto usa Docker solo para la aplicacion Laravel. La base de datos se conecta desde el archivo `.env` del proyecto.

## Requisitos

- Docker Desktop encendido (Engine running)
- Proyecto clonado

## Primer uso

1. Construir imagen:

```powershell
docker compose -f docker/docker-compose.yml build --no-cache
```

2. Levantar contenedor:

```powershell
docker compose -f docker/docker-compose.yml up -d
```

3. Verificar extension PostgreSQL en PHP:

```powershell
docker compose -f docker/docker-compose.yml exec app php -m | findstr /I pgsql
```

Debe aparecer `pdo_pgsql`.

## Uso diario

- Levantar:

```powershell
docker compose -f docker/docker-compose.yml up -d
```

- Estado:

```powershell
docker compose -f docker/docker-compose.yml ps
```

- Logs:

```powershell
docker compose -f docker/docker-compose.yml logs -f app
```

- Comandos artisan:

```powershell
docker compose -f docker/docker-compose.yml exec app php artisan migrate:status
docker compose -f docker/docker-compose.yml exec app php artisan migrate
docker compose -f docker/docker-compose.yml exec app php artisan config:clear
```

- Detener:

```powershell
docker compose -f docker/docker-compose.yml down
```

## Configuracion de base de datos

El contenedor usa el `.env` de raiz (`../.env`).

Si PostgreSQL corre en tu maquina local (fuera de Docker), usa en `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=host.docker.internal
DB_PORT=5432
DB_DATABASE=bd_hospital_privado_malacatan_1
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

No usar `127.0.0.1` como `DB_HOST` cuando Laravel corre dentro de Docker.

## Colaboracion en equipo

- Subir a Git:
  - `docker/`
  - `.dockerignore`
- No subir:
  - `.env`

El `docker-compose.yml` tiene volumen `../:/var/www/html`, asi que los cambios de codigo local se reflejan dentro del contenedor sin rebuild.
