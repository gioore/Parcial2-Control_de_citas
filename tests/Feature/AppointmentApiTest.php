<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_appointment(): void
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $response = $this->postJson('/api/appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'start_at' => '2026-10-01 09:00:00',
            'end_at' => '2026-10-01 09:45:00',
            'reason' => 'Consulta de seguimiento',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.patient_id', $patient->id)
            ->assertJsonPath('data.doctor_id', $doctor->id)
            ->assertJsonPath('data.status', 'pending');
    }

    public function test_it_filters_appointments_by_doctor(): void
    {
        $doctor = Doctor::factory()->create();
        Appointment::factory()->create(['doctor_id' => $doctor->id]);
        Appointment::factory()->create();

        $response = $this->getJson('/api/appointments?doctor_id='.$doctor->id);

        $response->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_it_changes_an_appointment_status(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->patchJson('/api/appointments/'.$appointment->id.'/status', [
            'status' => 'confirmed',
        ]);

        $response->assertOk()->assertJsonPath('data.status', 'confirmed');
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_it_rejects_a_doctor_schedule_conflict(): void
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();

        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'start_at' => '2026-10-01 09:00:00',
            'end_at' => '2026-10-01 10:00:00',
        ]);

        $response = $this->postJson('/api/appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'start_at' => '2026-10-01 09:30:00',
            'end_at' => '2026-10-01 10:30:00',
            'reason' => 'Horario en conflicto',
        ]);

        $response->assertStatus(409)
            ->assertJsonPath('message', 'El doctor ya tiene una cita activa en ese horario.');
    }

    public function test_cancelled_appointments_do_not_block_a_schedule(): void
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();

        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'start_at' => '2026-10-01 09:00:00',
            'end_at' => '2026-10-01 10:00:00',
            'status' => 'cancelled',
        ]);

        $response = $this->postJson('/api/appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'start_at' => '2026-10-01 09:30:00',
            'end_at' => '2026-10-01 10:30:00',
            'reason' => 'Horario liberado',
        ]);

        $response->assertCreated();
    }
}
