<?php
/**
 * File: AppointmentResource.php
 * Description: Resources to handle appointment operations
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Http\Resources
 * @author Vivek Singh
 */

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'healthcare_professional' => new HealthcareProfessionalResource(
                $this->whenLoaded('healthcareProfessional')
            ),
            'appointment_start_time' => $this->appointment_start_time->toISOString(),
            'appointment_end_time' => $this->appointment_end_time->toISOString(),
            'status' => $this->status,
            'notes' => $this->notes,
            'cancellation_reason' => $this->cancellation_reason,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
