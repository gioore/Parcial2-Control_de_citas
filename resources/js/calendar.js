import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

const calendarElement = document.querySelector('#calendar');

if (calendarElement) {
    const state = { doctorId: '' };
    const form = document.querySelector('#appointment-form');
    const modal = document.querySelector('#appointment-modal');
    const detailModal = document.querySelector('#detail-modal');
    const message = document.querySelector('#calendar-message');

    const showMessage = (text, type = 'info') => {
        message.textContent = text;
        message.dataset.type = type;
        message.hidden = false;
        window.setTimeout(() => { message.hidden = true; }, 4500);
    };

    const closeModal = (element) => element.close();

    const openForm = (start, end = '') => {
        form.reset();
        form.elements.start_at.value = start ? start.slice(0, 16) : '';
        form.elements.end_at.value = end ? end.slice(0, 16) : '';
        modal.showModal();
    };

    const loadSelectOptions = async () => {
        const [patientsResponse, doctorsResponse] = await Promise.all([
            fetch('/api/patients'),
            fetch('/api/doctors'),
        ]);
        const patients = (await patientsResponse.json()).data;
        const doctors = (await doctorsResponse.json()).data;
        const patientSelect = form.elements.patient_id;
        const doctorSelect = form.elements.doctor_id;
        patientSelect.innerHTML = '<option value="">Selecciona un paciente</option>';
        doctorSelect.innerHTML = '<option value="">Selecciona un doctor</option>';
        patients.forEach((patient) => {
            patientSelect.add(new Option(`${patient.name} ${patient.last_name}`, patient.id));
        });
        doctors.forEach((doctor) => {
            doctorSelect.add(new Option(`Dr. ${doctor.name} ${doctor.last_name} - ${doctor.specialty}`, doctor.id));
            document.querySelector('#doctor-filter').add(new Option(`${doctor.name} ${doctor.last_name}`, doctor.id));
        });
    };

    const calendar = new Calendar(calendarElement, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        selectable: true,
        editable: true,
        nowIndicator: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        events: async (info, successCallback, failureCallback) => {
            try {
                const params = new URLSearchParams({ from: info.startStr, to: info.endStr });
                if (state.doctorId) params.set('doctor_id', state.doctorId);
                const response = await fetch(`/api/appointments?${params}`);
                if (!response.ok) throw new Error('No se pudieron cargar las citas.');
                const result = await response.json();
                successCallback(result.data.map((appointment) => ({
                    id: appointment.id,
                    title: `${appointment.patient?.name ?? ''} ${appointment.patient?.last_name ?? ''}`.trim(),
                    start: appointment.start_at,
                    end: appointment.end_at,
                    classNames: [`status-${appointment.status}`],
                    extendedProps: appointment,
                })));
            } catch (error) {
                failureCallback(error);
                showMessage(error.message, 'error');
            }
        },
        select: (selection) => openForm(selection.startStr, selection.endStr),
        eventClick: ({ event }) => {
            const appointment = event.extendedProps;
            document.querySelector('#detail-content').innerHTML = `
                <strong>${event.title}</strong>
                <p>Doctor: ${appointment.doctor?.name ?? ''} ${appointment.doctor?.last_name ?? ''}</p>
                <p>Motivo: ${appointment.reason}</p>
                <p>Estado: ${appointment.status}</p>
                <p>${event.start.toLocaleString('es-MX')}</p>
            `;
            detailModal.dataset.appointmentId = event.id;
            detailModal.showModal();
        },
        eventDrop: async ({ event, revert }) => {
            try {
                const response = await fetch(`/api/appointments/${event.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                    body: JSON.stringify({
                        start_at: event.start.toISOString(),
                        end_at: event.end?.toISOString() ?? new Date(event.start.getTime() + 45 * 60000).toISOString(),
                    }),
                });
                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message ?? 'No se pudo reprogramar la cita.');
                }
                showMessage('Cita reprogramada correctamente.', 'success');
            } catch (error) {
                revert();
                showMessage(error.message, 'error');
            }
        },
    });

    document.querySelector('#doctor-filter').addEventListener('change', (event) => {
        state.doctorId = event.target.value;
        calendar.refetchEvents();
    });

    document.querySelectorAll('[data-close-dialog]').forEach((button) => {
        button.addEventListener('click', () => closeModal(button.closest('dialog')));
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const response = await fetch('/api/appointments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify(Object.fromEntries(new FormData(form))),
        });
        if (!response.ok) {
            const error = await response.json();
            showMessage(error.message ?? 'Revisa los datos de la cita.', 'error');
            return;
        }
        closeModal(modal);
        calendar.refetchEvents();
        showMessage('Cita creada correctamente.', 'success');
    });

    document.querySelector('#cancel-appointment').addEventListener('click', async () => {
        const response = await fetch(`/api/appointments/${detailModal.dataset.appointmentId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify({ status: 'cancelled' }),
        });
        if (response.ok) {
            closeModal(detailModal);
            calendar.refetchEvents();
            showMessage('Cita cancelada. El historial se conserva.', 'success');
        }
    });

    loadSelectOptions().catch(() => showMessage('No se pudieron cargar pacientes y doctores.', 'error'));
    calendar.render();
}
