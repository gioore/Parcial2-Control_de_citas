# Sistema Hospitalario - Control de Citas

Módulo de control de citas médicas construido con Laravel 12.

## Flujo de ramas

- `main`: versión estable y entregable.
- `develop`: integración y validación de funcionalidades.
- `feature/*`: trabajo aislado por funcionalidad.

Las ramas de funcionalidad se integran primero mediante Pull Request hacia `develop`. Después de validar la integración, `develop` se promueve hacia `main` mediante otro Pull Request.

## Ramas de trabajo

- `feature/backend-laravel-foundation`: bootstrap del backend Laravel 12.
- `feature/infrastructure-mysql`: MySQL en Docker y persistencia.
- `feature/domain-appointment-management`: modelos, migraciones y datos iniciales.
- `feature/backend-appointments-api`: API REST de citas, pacientes y doctores.
- `feature/backend-scheduling-rules`: conflictos de horario y estados.
- `feature/frontend-calendar-ui`: FullCalendar, formularios y experiencia responsive.
- `feature/qa-and-project-evidence`: pruebas, documentación y evidencia final.

## Stack

- Laravel 12
- PHP 8.2+
- MySQL 8 en Docker
- Blade, Vite y FullCalendar
