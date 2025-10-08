<?php

namespace Database\Seeders;

use App\Models\HealthcareProfessional;
use Illuminate\Database\Seeder;

class HealthcareProfessionalSeeder extends Seeder
{
    public function run(): void
    {
        HealthcareProfessional::factory()
            ->count(20)
            ->create();
    }
}
