<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 day', '+30 days');

        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'start_at' => $start,
            'end_at' => (clone $start)->modify('+45 minutes'),
            'reason' => fake()->sentence(6),
            'status' => 'pending',
        ];
    }
}
