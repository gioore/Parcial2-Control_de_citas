<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $patients = Patient::factory(3)->create();
        $doctors = Doctor::factory(3)->create();

        Appointment::factory()->create([
            'patient_id' => $patients[0]->id,
            'doctor_id' => $doctors[0]->id,
            'start_at' => now()->addDay()->setTime(9, 0),
            'end_at' => now()->addDay()->setTime(9, 45),
            'reason' => 'Consulta general',
            'status' => 'pending',
        ]);

        Appointment::factory()->create([
            'patient_id' => $patients[1]->id,
            'doctor_id' => $doctors[1]->id,
            'start_at' => now()->addDays(2)->setTime(11, 0),
            'end_at' => now()->addDays(2)->setTime(11, 45),
            'reason' => 'Seguimiento medico',
            'status' => 'confirmed',
        ]);

        Appointment::factory()->create([
            'patient_id' => $patients[2]->id,
            'doctor_id' => $doctors[2]->id,
            'start_at' => now()->addDays(3)->setTime(15, 0),
            'end_at' => now()->addDays(3)->setTime(15, 45),
            'reason' => 'Revision de resultados',
            'status' => 'cancelled',
        ]);
    }
}
