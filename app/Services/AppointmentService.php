<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
            return Appointment::create($data)->load(['patient', 'doctor']);
        });
    }

    public function update(Appointment $appointment, array $data): Appointment
    {
        return DB::transaction(function () use ($appointment, $data) {
            $appointment->update($data);

            return $appointment->refresh()->load(['patient', 'doctor']);
        });
    }

    public function changeStatus(Appointment $appointment, string $status): Appointment
    {
        $appointment->update(['status' => $status]);

        return $appointment->refresh()->load(['patient', 'doctor']);
    }
}
