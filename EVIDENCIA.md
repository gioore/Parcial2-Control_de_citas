# Evidencia - Control de Citas HIS

## Stack

- Laravel 12.69.2
- PHP 8.4+
- MySQL 8 en Docker
- FullCalendar
- Vite

## Instalacion con Docker

Ejecutar desde la raiz del repositorio:

```bash
docker compose up -d --build
docker compose ps
docker compose logs app
docker compose logs mysql
docker compose exec app php artisan migrate:status
```

Aplicacion:

```text
http://localhost:8000
```

Captura requerida:

```text
evidence/docker-compose-ps.png
evidence/docker-migrations.png
```

## Pruebas automatizadas

Comandos ejecutados:

```bash
php artisan test --display-warnings
vendor/bin/pint --test
npm run build
```

Resultado validado localmente:

```text
Tests: 8 passed (17 assertions)
Pint: passed
Vite: Build completed successfully
```

## Pruebas de API

Con la aplicacion levantada:

```bash
curl http://localhost:8000/api/doctors
curl http://localhost:8000/api/patients
curl http://localhost:8000/api/appointments
```

Crear una cita:

```bash
curl -X POST http://localhost:8000/api/appointments ^
  -H "Content-Type: application/json" ^
  -d "{\"patient_id\":1,\"doctor_id\":1,\"start_at\":\"2026-10-01 09:00:00\",\"end_at\":\"2026-10-01 09:45:00\",\"reason\":\"Consulta general\"}"
```

Cambiar estado:

```bash
curl -X PATCH http://localhost:8000/api/appointments/1/status ^
  -H "Content-Type: application/json" ^
  -d "{\"status\":\"confirmed\"}"
```

Probar conflicto de horario. La respuesta esperada es `409 Conflict`:

```bash
curl -X POST http://localhost:8000/api/appointments ^
  -H "Content-Type: application/json" ^
  -d "{\"patient_id\":2,\"doctor_id\":1,\"start_at\":\"2026-10-01 09:30:00\",\"end_at\":\"2026-10-01 10:30:00\",\"reason\":\"Horario ocupado\"}"
```

## Pruebas de interfaz

Capturar:

```text
evidence/calendar-month.png
evidence/calendar-week.png
evidence/create-appointment.png
evidence/appointment-detail.png
evidence/drag-and-drop.png
evidence/status-colors.png
evidence/doctor-filter.png
evidence/conflict-message.png
```

Funcionalidades verificadas:

- Crear cita desde un horario del calendario.
- Visualizar vistas mensual, semanal y diaria.
- Abrir detalle de una cita.
- Reprogramar con drag and drop.
- Revertir el movimiento cuando la API responde `409`.
- Cancelar sin eliminar el registro.
- Filtrar por doctor.
- Mostrar colores según estado.

## Historial Git

```bash
git branch -a
git log --graph --oneline --decorate --all
git log --merges --oneline develop
```

El historial debe mostrar los Pull Requests fusionados hacia `develop` y la promoción final hacia `main`.

## Checklist de entrega

- [x] Laravel 12 configurado.
- [x] MySQL definido en Docker Compose.
- [x] Persistencia mediante volumen Docker.
- [x] Migraciones, factories y seeders.
- [x] API REST de citas, pacientes y doctores.
- [x] Validacion de conflictos en el servidor.
- [x] FullCalendar interactivo.
- [x] Pruebas automatizadas.
- [x] Pull Requests hacia `develop`.
- [ ] Capturas finales agregadas por el estudiante.
- [ ] PR final `develop` hacia `main`.
