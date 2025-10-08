<?php

namespace App\Repositories\Interfaces;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;

interface AppointmentRepositoryInterface
{
    public function create(array $data): Appointment;
    public function findById(int $id): ?Appointment;
    public function getUserAppointments(int $userId): Collection;
    public function checkAvailability(
        int $professionalId,
        string $startTime,
        string $endTime,
        ?int $excludeAppointmentId = null
    ): bool;
    public function update(int $id, array $data): bool;
}
