# Arquitectura tecnica

## Stack

- Laravel 12.
- PHP 8.2 o superior.
- Eloquent ORM.
- MySQL 8 en Docker Compose.
- Blade para las vistas.
- Vite para los recursos frontend.
- FullCalendar para la agenda interactiva.
- PHPUnit para pruebas automatizadas.

## Capas

```text
FullCalendar / Blade
        |
        v
Rutas web y API
        |
        v
Controllers + Form Requests + API Resources
        |
        v
Application Services
        |
        v
Repositories y Modelos Eloquent
        |
        v
MySQL en Docker
```

## Responsabilidades

### Presentacion

Las vistas Blade y los scripts de FullCalendar muestran eventos, recopilan datos y consumen la API. No contienen reglas de disponibilidad.

### API

Los controladores reciben solicitudes HTTP, validan mediante Form Requests y delegan operaciones al servicio correspondiente.

### Logica de negocio

`AppointmentService` coordina la creacion, reprogramacion y cambio de estado. La validacion de solapamientos se ejecuta aqui dentro de una transaccion.

### Acceso a datos

Los repositorios y modelos Eloquent consultan y persisten pacientes, doctores y citas.

## Endpoints principales

```text
GET    /api/appointments
POST   /api/appointments
GET    /api/appointments/{id}
PUT    /api/appointments/{id}
PATCH  /api/appointments/{id}/status
GET    /api/doctors
GET    /api/patients
```

## Regla de conflicto

Existe solapamiento cuando:

```text
nuevo_inicio < cita_existente_fin
AND nuevo_fin > cita_existente_inicio
```

La consulta considera el mismo doctor, ignora citas canceladas y excluye la cita actual durante una reprogramacion.
