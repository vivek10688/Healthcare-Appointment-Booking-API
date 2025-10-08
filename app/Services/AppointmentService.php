<?php
/**
 * File: AppointmentService.php
 * Description: Service to handle appointment operations
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Services
 * @author Vivek Singh
 */

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Repositories\Interfaces\HealthcareProfessionalRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    public function __construct(
        private AppointmentRepositoryInterface $appointmentRepository,
        private HealthcareProfessionalRepositoryInterface $professionalRepository
    ) {}

    public function bookAppointment(array $data): Appointment
    {
        // Validate future date
        $startTime = Carbon::parse($data['appointment_start_time']);
        $endTime = Carbon::parse($data['appointment_end_time']);

        if ($startTime->isPast()) {
            throw ValidationException::withMessages([
                'appointment_start_time' => ['Appointment must be scheduled for a future date and time.'],
            ]);
        }

        if ($endTime->lessThanOrEqualTo($startTime)) {
            throw ValidationException::withMessages([
                'appointment_end_time' => ['End time must be after start time.'],
            ]);
        }

        // Check professional exists and is available
        $professional = $this->professionalRepository->findById($data['healthcare_professional_id']);

        if (!$professional) {
            throw ValidationException::withMessages([
                'healthcare_professional_id' => ['Healthcare professional not found.'],
            ]);
        }

        if (!$professional->is_available) {
            throw ValidationException::withMessages([
                'healthcare_professional_id' => ['Healthcare professional is not available.'],
            ]);
        }

        // Check for double booking - normalize datetime strings
        $isAvailable = $this->appointmentRepository->checkAvailability(
            $data['healthcare_professional_id'],
            $startTime->format('Y-m-d H:i:s'),
            $endTime->format('Y-m-d H:i:s')
        );

        if (!$isAvailable) {
            throw ValidationException::withMessages([
                'appointment_start_time' => ['This time slot is not available.'],
            ]);
        }

        return $this->appointmentRepository->create([
            'user_id' => $data['user_id'],
            'healthcare_professional_id' => $data['healthcare_professional_id'],
            'appointment_start_time' => $startTime->format('Y-m-d H:i:s'),
            'appointment_end_time' => $endTime->format('Y-m-d H:i:s'),
            'notes' => $data['notes'] ?? null,
            'status' => AppointmentStatus::BOOKED,
        ]);
    }

    public function getUserAppointments(int $userId): Collection
    {
        return $this->appointmentRepository->getUserAppointments($userId);
    }

    public function cancelAppointment(int $appointmentId, int $userId, ?string $reason = null): bool
    {
        $appointment = $this->appointmentRepository->findById($appointmentId);

        if (!$appointment) {
            throw ValidationException::withMessages([
                'appointment' => ['Appointment not found.'],
            ]);
        }

        // Check ownership
        if ($appointment->user_id !== $userId) {
            throw ValidationException::withMessages([
                'appointment' => ['You are not authorized to cancel this appointment.'],
            ]);
        }

        // Check if already cancelled
        if ($appointment->status === AppointmentStatus::CANCELLED) {
            throw ValidationException::withMessages([
                'appointment' => ['Appointment is already cancelled.'],
            ]);
        }

        // Check 24-hour cancellation policy
        $now = Carbon::now();
        $appointmentTime = Carbon::parse($appointment->appointment_start_time);

        // Calculate hours until appointment
        $hoursUntilAppointment = $now->diffInHours($appointmentTime, false);

        // If appointment is in future (positive hours) and less than 24 hours away
        if ($hoursUntilAppointment >= 0 && $hoursUntilAppointment < 24) {
            throw ValidationException::withMessages([
                'appointment' => ['Appointments cannot be cancelled within 24 hours of the scheduled time.'],
            ]);
        }

        return $this->appointmentRepository->update($appointmentId, [
            'status' => AppointmentStatus::CANCELLED,
            'cancellation_reason' => $reason,
        ]);
    }

    public function completeAppointment(int $appointmentId): bool
    {
        $appointment = $this->appointmentRepository->findById($appointmentId);

        if (!$appointment) {
            throw ValidationException::withMessages([
                'appointment' => ['Appointment not found.'],
            ]);
        }

        if ($appointment->status !== AppointmentStatus::BOOKED) {
            throw ValidationException::withMessages([
                'appointment' => ['Only booked appointments can be marked as completed.'],
            ]);
        }

        return $this->appointmentRepository->update($appointmentId, [
            'status' => AppointmentStatus::COMPLETED,
        ]);
    }
}
