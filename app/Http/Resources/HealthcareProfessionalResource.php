<?php
/**
 * File: HealthcareProfessionalResource.php
 * 
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

class HealthcareProfessionalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->specialty,
            'bio' => $this->bio,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_available' => $this->is_available,
        ];
    }
}
