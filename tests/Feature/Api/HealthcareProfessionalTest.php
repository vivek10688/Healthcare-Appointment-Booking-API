<?php

namespace Tests\Feature\Api;

use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthcareProfessionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_healthcare_professionals(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        HealthcareProfessional::factory()->count(5)->create();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/healthcare-professionals');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'specialty',
                        'is_available',
                    ],
                ],
            ]);
    }

    public function test_can_filter_available_professionals(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        HealthcareProfessional::factory()->count(3)->create([
            'is_available' => true,
        ]);

        HealthcareProfessional::factory()->count(2)->create([
            'is_available' => false,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/healthcare-professionals?available=1');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
