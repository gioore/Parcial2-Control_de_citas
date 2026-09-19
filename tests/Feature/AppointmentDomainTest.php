<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_appointment_can_be_persisted_with_patient_and_doctor(): void
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'status' => 'pending',
        ]);
        $this->assertTrue($appointment->patient->is($patient));
        $this->assertTrue($appointment->doctor->is($doctor));
    }
}
