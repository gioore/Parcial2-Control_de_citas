# Sistema Hospitalario - Control de Citas

Módulo de control de citas médicas construido con Laravel 12.

## Flujo de ramas

- `main`: versión estable y entregable.
- `develop`: integración y validación de funcionalidades.
- `feature/*`: trabajo aislado por funcionalidad.

Las ramas de funcionalidad se integran primero mediante Pull Request hacia `develop`. Después de validar la integración, `develop` se promueve hacia `main` mediante otro Pull Request.

## Ramas de trabajo

- `feature/backend-laravel-foundation`: bootstrap del backend Laravel 12.
- `feature/infrastructure-mysql-docker`: MySQL en Docker y persistencia.
- `feature/domain-appointment-scheduling`: modelos, migraciones y datos iniciales.
- `feature/backend-appointments-api`: API REST de citas, pacientes y doctores.
- `feature/backend-scheduling-validation`: conflictos de horario y estados.
- `feature/frontend-calendar-ui-ux`: FullCalendar, formularios y experiencia responsive.
- `feature/quality-assurance-evidence`: pruebas, documentación y evidencia final.

## Stack

- Laravel 12
- PHP 8.2+
- MySQL 8 en Docker
- Blade, Vite y FullCalendar

## Instalacion local

1. Instalar dependencias PHP y JavaScript:

```bash
composer install
npm install
```

2. Crear el archivo de entorno y generar la clave de la aplicacion:

```bash
copy .env.example .env
php artisan key:generate
```

En Git Bash se puede utilizar `cp .env.example .env` en lugar de `copy`.

3. Ejecutar las validaciones iniciales:

```bash
php artisan test
npm run build
```

El archivo `.env` es local y no debe incluirse en commits. La configuracion de pruebas utiliza SQLite en memoria y no requiere MySQL para ejecutar la suite inicial.
