<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = Carbon::now()->addDays(fake()->numberBetween(1, 30));
        $endTime = $startTime->copy()->addHour();

        return [
            'user_id' => User::factory(),
            'healthcare_professional_id' => HealthcareProfessional::factory(),
            'appointment_start_time' => $startTime,
            'appointment_end_time' => $endTime,
            'status' => fake()->randomElement(['booked', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
            'cancellation_reason' => null,
        ];
    }

    /**
     * Indicate that the appointment is booked.
     */
    public function booked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'booked',
            'cancellation_reason' => null,
        ]);
    }

    /**
     * Indicate that the appointment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'cancellation_reason' => null,
        ]);
    }

    /**
     * Indicate that the appointment is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    /**
     * Set specific appointment time.
     */
    public function scheduledAt(Carbon $startTime, ?Carbon $endTime = null): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_start_time' => $startTime,
            'appointment_end_time' => $endTime ?? $startTime->copy()->addHour(),
        ]);
    }
}
