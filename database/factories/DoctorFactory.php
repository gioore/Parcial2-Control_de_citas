<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'specialty' => fake()->randomElement([
                'Medicina general',
                'Cardiologia',
                'Pediatria',
                'Dermatologia',
            ]),
            'active' => true,
        ];
    }
}
