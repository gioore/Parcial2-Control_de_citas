<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Citas | HIS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="app-header">
        <div><span class="eyebrow">HIS / AGENDA MEDICA</span><h1>Control de citas</h1></div>
        <p>Agenda, reprograma y conserva el historial de cada consulta.</p>
    </header>
    <main class="app-shell">
        <section class="toolbar" aria-label="Filtros de agenda">
            <label>Doctor
                <select id="doctor-filter"><option value="">Todos los doctores</option></select>
            </label>
            <span class="legend"><i class="dot pending"></i>Pendiente <i class="dot confirmed"></i>Confirmada <i class="dot cancelled"></i>Cancelada</span>
        </section>
        <p id="calendar-message" class="notice" hidden></p>
        <section class="calendar-card"><div id="calendar"></div></section>
    </main>
    <dialog id="appointment-modal" class="dialog">
        <form id="appointment-form" method="dialog">
            <div class="dialog-heading"><div><span class="eyebrow">NUEVA CONSULTA</span><h2>Agendar cita</h2></div><button type="button" data-close-dialog class="icon-button">&times;</button></div>
            <label>Paciente<select name="patient_id" required></select></label>
            <label>Doctor<select name="doctor_id" required></select></label>
            <div class="form-grid"><label>Inicio<input type="datetime-local" name="start_at" required></label><label>Fin<input type="datetime-local" name="end_at" required></label></div>
            <label>Motivo<input name="reason" maxlength="255" required placeholder="Motivo de la consulta"></label>
            <div class="dialog-actions"><button type="button" data-close-dialog class="button secondary">Cerrar</button><button class="button primary">Guardar cita</button></div>
        </form>
    </dialog>
    <dialog id="detail-modal" class="dialog"><div class="dialog-heading"><div><span class="eyebrow">DETALLE</span><h2>Cita médica</h2></div><button type="button" data-close-dialog class="icon-button">&times;</button></div><div id="detail-content"></div><div class="dialog-actions"><button id="cancel-appointment" class="button danger">Cancelar cita</button><button type="button" data-close-dialog class="button secondary">Cerrar</button></div></dialog>
</body>
</html>
