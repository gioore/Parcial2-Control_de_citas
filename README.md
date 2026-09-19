# Sistema Hospitalario - Control de Citas

Módulo de control de citas médicas construido con Laravel 12.

## Flujo de ramas

- `main`: versión estable y entregable.
- `develop`: integración y validación de funcionalidades.
- `feature/*`: trabajo aislado por funcionalidad.

Las ramas de funcionalidad se integran primero mediante Pull Request hacia `develop`. Después de validar la integración, `develop` se promueve hacia `main` mediante otro Pull Request.

## Stack

- Laravel 12
- PHP 8.2+
- MySQL 8 en Docker
- Blade, Vite y FullCalendar
