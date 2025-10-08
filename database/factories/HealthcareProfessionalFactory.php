<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HealthcareProfessionalFactory extends Factory
{
    public function definition(): array
    {
        $specialties = [
            'Cardiology',
            'Dermatology',
            'Neurology',
            'Pediatrics',
            'Orthopedics',
            'General Practice',
            'Psychiatry',
            'Ophthalmology'
        ];

        return [
            'name' => fake()->name(),
            'specialty' => fake()->randomElement($specialties),
            'bio' => fake()->paragraph(3),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'is_available' => fake()->boolean(85),
        ];
    }
}
