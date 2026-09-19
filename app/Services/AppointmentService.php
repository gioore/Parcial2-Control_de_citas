<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AppointmentService
{
    public function list(array $filters): Collection
    {
        return Appointment::query()
            ->with(['patient', 'doctor'])
            ->when($filters['doctor_id'] ?? null, fn ($query, $doctorId) => $query->where('doctor_id', $doctorId))
            ->when($filters['patient_id'] ?? null, fn ($query, $patientId) => $query->where('patient_id', $patientId))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->where('start_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->where('end_at', '<=', $to))
            ->orderBy('start_at')
            ->get();
    }

    public function create(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $this->ensureAvailability($data);

            return Appointment::create($data)->refresh()->load(['patient', 'doctor']);
        });
    }

    public function update(Appointment $appointment, array $data): Appointment
    {
        return DB::transaction(function () use ($appointment, $data) {
            $this->ensureAvailability(array_merge($appointment->only([
                'doctor_id',
                'start_at',
                'end_at',
                'status',
            ]), $data), $appointment);
            $appointment->update($data);

            return $appointment->refresh()->load(['patient', 'doctor']);
        });
    }

    public function changeStatus(Appointment $appointment, string $status): Appointment
    {
        $appointment->update(['status' => $status]);

        return $appointment->refresh()->load(['patient', 'doctor']);
    }

    private function ensureAvailability(array $data, ?Appointment $ignored = null): void
    {
        if (($data['status'] ?? 'pending') === 'cancelled') {
            return;
        }

        $conflict = Appointment::query()
            ->where('doctor_id', $data['doctor_id'])
            ->where('status', '!=', 'cancelled')
            ->where('start_at', '<', $data['end_at'])
            ->where('end_at', '>', $data['start_at'])
            ->when($ignored, fn ($query) => $query->where('id', '!=', $ignored->id))
            ->exists();

        if ($conflict) {
            throw new ConflictHttpException('El doctor ya tiene una cita activa en ese horario.');
        }
    }
}
