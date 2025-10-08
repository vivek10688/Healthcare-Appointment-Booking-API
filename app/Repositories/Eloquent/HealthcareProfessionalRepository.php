<?php

namespace App\Repositories\Eloquent;

use App\Models\HealthcareProfessional;
use App\Repositories\Interfaces\HealthcareProfessionalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class HealthcareProfessionalRepository implements HealthcareProfessionalRepositoryInterface
{
    public function getAll(): Collection
    {
        return HealthcareProfessional::all();
    }

    public function getAvailable(): Collection
    {
        return HealthcareProfessional::where('is_available', true)->get();
    }

    public function findById(int $id): ?HealthcareProfessional
    {
        return HealthcareProfessional::find($id);
    }

    public function getBySpecialty(string $specialty): Collection
    {
        return HealthcareProfessional::where('specialty', $specialty)
                                     ->where('is_available', true)
                                     ->get();
    }
}
