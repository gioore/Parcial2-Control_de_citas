# Requerimientos del modulo

## Objetivo

Implementar un modulo hospitalario para registrar, consultar, reprogramar y cancelar citas medicas desde un calendario interactivo.

## Requerimientos funcionales

| ID | Requerimiento | Criterio de aceptacion |
| --- | --- | --- |
| RQF-01 | Crear una cita con paciente, doctor, inicio, fin y motivo. | La cita valida los datos y queda persistida en MySQL. |
| RQF-02 | Mostrar citas en FullCalendar. | Existen vistas mensual y semanal. |
| RQF-03 | Evitar doble reserva. | El servidor responde `409` cuando el horario del doctor se solapa. |
| RQF-04 | Reprogramar mediante drag and drop. | El nuevo horario se actualiza en la base de datos. |
| RQF-05 | Cancelar sin borrar historial. | La cita conserva sus datos y cambia a estado `cancelled`. |
| RQF-06 | Filtrar citas. | Se puede filtrar por doctor y rango de fechas. |
| RQF-07 | Exponer API REST. | Existen operaciones para citas y lecturas de pacientes y doctores. |
| RQF-08 | Validar datos de entrada. | Se validan campos, fechas y existencia de relaciones antes de guardar. |
| RQF-09 | Consultar detalle de una cita. | Al seleccionar un evento se muestran sus datos. |
| RQF-10 | Representar estados con colores. | Cada estado usa un color diferente en el calendario. |

## Requerimientos no funcionales

| ID | Requerimiento | Criterio de aceptacion |
| --- | --- | --- |
| RQNF-01 | Ejecutar MySQL en Docker. | MySQL funciona dentro de un contenedor con volumen persistente. |
| RQNF-02 | Levantar el entorno de base de datos reproduciblemente. | `docker compose up -d` inicia el servicio. |
| RQNF-03 | Responder JSON y usar HTTP correcto. | Se utilizan `200`, `201`, `400`, `404`, `409` y `422` segun corresponda. |
| RQNF-04 | Separar el codigo por capas. | La logica no se mezcla en controladores ni eventos de interfaz. |
| RQNF-05 | Mantener trazabilidad Git. | Cada funcionalidad tiene rama, commits descriptivos, PR y merge. |
| RQNF-06 | Mantener una interfaz usable. | El calendario funciona en escritorio y tablet. |
| RQNF-07 | Validar disponibilidad en el servidor. | El cliente no puede omitir la regla de conflicto. |
| RQNF-08 | Documentar evidencia. | Se registran comandos, capturas, API, Docker e historial Git. |

## Estados de una cita

```text
pending   -> pendiente
confirmed -> confirmada
cancelled -> cancelada
attended  -> atendida
```

Una cita cancelada no se elimina y no bloquea un horario futuro.
