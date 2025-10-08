<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\HealthcareProfessional;

interface HealthcareProfessionalRepositoryInterface
{
    public function getAll(): Collection;
    public function getAvailable(): Collection;
    public function findById(int $id): ?HealthcareProfessional;
    public function getBySpecialty(string $specialty): Collection;
}
