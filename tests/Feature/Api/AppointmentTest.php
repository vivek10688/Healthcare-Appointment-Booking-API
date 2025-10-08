<?php

namespace Tests\Feature\Api;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private HealthcareProfessional $professional;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Freeze time for consistent testing
        Carbon::setTestNow(Carbon::create(2025, 10, 7, 10, 0, 0));

        $this->user = User::factory()->create();
        $this->professional = HealthcareProfessional::factory()->create([
            'is_available' => true,
        ]);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    protected function tearDown(): void
    {
        // Reset Carbon time after each test
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_user_can_book_appointment(): void
    {
        $startTime = Carbon::now()->addDays(2)->setTime(10, 0);
        $endTime = $startTime->copy()->addHour();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/appointments', [
                'healthcare_professional_id' => $this->professional->id,
                'appointment_start_time' => $startTime->toISOString(),
                'appointment_end_time' => $endTime->toISOString(),
                'notes' => 'Annual checkup',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'healthcare_professional',
                    'appointment_start_time',
                    'appointment_end_time',
                    'status',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Appointment booked successfully',
            ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'healthcare_professional_id' => $this->professional->id,
            'status' => 'booked',
        ]);
    }

    public function test_cannot_book_appointment_in_past(): void
    {
        $startTime = Carbon::now()->subDay();
        $endTime = $startTime->copy()->addHour();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/appointments', [
                'healthcare_professional_id' => $this->professional->id,
                'appointment_start_time' => $startTime->toISOString(),
                'appointment_end_time' => $endTime->toISOString(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['appointment_start_time'])
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_cannot_double_book_professional(): void
    {
        $startTime = Carbon::now()->addDays(2)->setTime(10, 0, 0);
        $endTime = $startTime->copy()->addHour();

        // Create existing appointment with exact same time
        $existingAppointment = Appointment::factory()->create([
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => $startTime->format('Y-m-d H:i:s'),
            'appointment_end_time' => $endTime->format('Y-m-d H:i:s'),
            'status' => 'booked',
        ]);

        // Debug: Check if appointment was created correctly
        $this->assertDatabaseHas('appointments', [
            'id' => $existingAppointment->id,
            'healthcare_professional_id' => $this->professional->id,
            'status' => 'booked',
        ]);

        // Try to book overlapping appointment
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/appointments', [
                'healthcare_professional_id' => $this->professional->id,
                'appointment_start_time' => $startTime->toISOString(),
                'appointment_end_time' => $endTime->toISOString(),
            ]);

        // If this fails, dump the response to see what's happening
        if ($response->status() !== 422) {
            dump($response->json());
        }

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['appointment_start_time'])
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }


    public function test_user_can_view_their_appointments(): void
    {
        Appointment::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/appointments');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'healthcare_professional',
                        'appointment_start_time',
                        'appointment_end_time',
                        'status',
                    ],
                ],
            ]);
    }

    public function test_user_can_cancel_appointment_more_than_24_hours_before(): void
    {
        $startTime = Carbon::now()->addDays(3);
        $endTime = $startTime->copy()->addHour();

        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'appointment_start_time' => $startTime,
            'appointment_end_time' => $endTime,
            'status' => 'booked',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->patchJson("/api/appointments/{$appointment->id}/cancel", [
                'cancellation_reason' => 'Schedule conflict',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Appointment cancelled successfully',
            ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
            'cancellation_reason' => 'Schedule conflict',
        ]);
    }

    public function test_user_cannot_cancel_appointment_within_24_hours(): void
    {
        $startTime = Carbon::now()->addHours(20);
        $endTime = $startTime->copy()->addHour();

        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'appointment_start_time' => $startTime,
            'appointment_end_time' => $endTime,
            'status' => 'booked',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->patchJson("/api/appointments/{$appointment->id}/cancel");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['appointment'])
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_user_cannot_cancel_other_users_appointment(): void
    {
        $otherUser = User::factory()->create();
        $appointment = Appointment::factory()->create([
            'user_id' => $otherUser->id,
            'appointment_start_time' => Carbon::now()->addDays(3),
            'appointment_end_time' => Carbon::now()->addDays(3)->addHour(),
            'status' => 'booked',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->patchJson("/api/appointments/{$appointment->id}/cancel");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['appointment'])
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_unauthenticated_user_cannot_book_appointment(): void
    {
        $startTime = Carbon::now()->addDays(2)->setTime(10, 0);

        $response = $this->postJson('/api/appointments', [
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => $startTime->toISOString(),
            'appointment_end_time' => $startTime->copy()->addHour()->toISOString(),
        ]);

        $response->assertStatus(401);
    }

    public function test_cannot_book_with_unavailable_professional(): void
    {
        $unavailableProfessional = HealthcareProfessional::factory()->create([
            'is_available' => false,
        ]);

        $startTime = Carbon::now()->addDays(2)->setTime(10, 0);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/appointments', [
                'healthcare_professional_id' => $unavailableProfessional->id,
                'appointment_start_time' => $startTime->toISOString(),
                'appointment_end_time' => $startTime->copy()->addHour()->toISOString(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['healthcare_professional_id']);
    }

    public function test_cannot_book_appointment_with_invalid_data(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/appointments', [
                'healthcare_professional_id' => 999999, // Non-existent
                'appointment_start_time' => 'invalid-date',
                'appointment_end_time' => 'invalid-date',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'healthcare_professional_id',
                'appointment_start_time',
                'appointment_end_time',
            ]);
    }

    public function test_cannot_cancel_already_cancelled_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'appointment_start_time' => Carbon::now()->addDays(3),
            'appointment_end_time' => Carbon::now()->addDays(3)->addHour(),
            'status' => 'cancelled',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->patchJson("/api/appointments/{$appointment->id}/cancel");

        $response->assertStatus(422);
    }

    public function test_can_complete_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'booked',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->patchJson("/api/appointments/{$appointment->id}/complete");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Appointment marked as completed',
            ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed',
        ]);
    }
}
