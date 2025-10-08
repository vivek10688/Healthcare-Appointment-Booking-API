<?php
/**
 * File: StoreAppointmentRequest.php
 * Description: Request to handle validation operations
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Http\Requests
 * @author Vivek Singh
 */

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'healthcare_professional_id' => ['required', 'integer', 'exists:healthcare_professionals,id'],
            'appointment_start_time' => ['required', 'date', 'after:now'],
            'appointment_end_time' => ['required', 'date', 'after:appointment_start_time'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_start_time.after' => 'The appointment must be scheduled for a future date and time.',
            'appointment_end_time.after' => 'The appointment end time must be after the start time.',
        ];
    }
}
