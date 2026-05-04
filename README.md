# GEOIS — Auditoría contable

Sistema de auditoría contable que aplica reglas de análisis sobre datos contables masivos para producir hallazgos y dictámenes.

Stack: PHP 7.4 + CodeIgniter 3 + MySQL 5.7 + Memcached + Gearman.

## Levantar el entorno local

Requiere [Docker Desktop](https://www.docker.com/products/docker-desktop/) (o equivalente como OrbStack).

### Primera vez

1. Clonar el repo:
   ```bash
   git clone <url-repo> geois && cd geois
   ```

2. Crear los configs locales a partir de las plantillas:
   ```bash
   cp application/config/database.example.php application/config/database.php
   cp application/config/config.example.php   application/config/config.php
   cp application/config/validate.example.php application/config/validate.php
   ```
   Editar `database.php` y `config.php` para poner password real y `encryption_key` aleatoria.

3. Restaurar la base de datos. El dump (`geois-dump.sql.gz`) NO está en el repo (muy grande, contiene datos de clientes). Pedirlo a quien administra el sistema y restaurarlo así:
   ```bash
   docker compose up -d mysql
   gunzip -c geois-dump.sql.gz | docker exec -i geois-mysql mysql
   ```

4. Levantar todo:
   ```bash
   docker compose up -d
   ```

5. Abrir <http://localhost:8080>.

### Comandos útiles

```bash
docker compose up -d        # arranca todos los servicios
docker compose down         # apaga
docker compose ps           # ver estado
docker logs geois-app -f    # logs PHP/Apache en vivo
docker exec -it geois-mysql mysql develop   # consola MySQL
```

## Estructura

- `application/` — controllers, models, views (CodeIgniter)
- `system/` — framework CodeIgniter (no editar)
- `assets/` — CSS, JS, imágenes públicas
- `archivos/` — uploads de clientes (la mayoría excluida del repo)
- `Dockerfile`, `docker-compose.yml` — entorno local
- `application/config/*.example.php` — plantillas de configs locales

## Notas

- El password MySQL "real" del entorno productivo no debe usarse en local. Usar uno arbitrario; el contenedor MySQL local corre con `--skip-grant-tables`.
- El servicio `gearmand` aún no está en el docker-compose. Funciones que disparen jobs async (análisis pesados) van a fallar hasta agregarlo.
