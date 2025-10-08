<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function findById(int $id): ?Appointment
    {
        return Appointment::with(['user', 'healthcareProfessional'])->find($id);
    }

    public function getUserAppointments(int $userId): Collection
    {
        return Appointment::with('healthcareProfessional')
                         ->where('user_id', $userId)
                         ->orderBy('appointment_start_time', 'desc')
                         ->get();
    }

    public function checkAvailability(
        int $professionalId,
        string $startTime,
        string $endTime,
        ?int $excludeAppointmentId = null
    ): bool {
        // Normalize datetime strings to Y-m-d H:i:s format
        $start = \Carbon\Carbon::parse($startTime)->format('Y-m-d H:i:s');
        $end = \Carbon\Carbon::parse($endTime)->format('Y-m-d H:i:s');

        $query = Appointment::where('healthcare_professional_id', $professionalId)
            ->where('status', 'booked')
            ->where(function($q) use ($start, $end) {
                // Check if there's any overlap
                // Overlap occurs when: start < existing_end AND end > existing_start
                $q->where('appointment_start_time', '<', $end)
                  ->where('appointment_end_time', '>', $start);
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        // Return true if no conflicting appointments exist
        return $query->doesntExist();
    }

    public function update(int $id, array $data): bool
    {
        $appointment = Appointment::find($id);
        return $appointment ? $appointment->update($data) : false;
    }
}
