<?php
/**
 * File: HealthcareProfessionalController.php
 * Description: Controller to view List of Healthcare Professionals
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Http\Controllers
 * @author Vivek Singh
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProfessionalResource;
use App\Services\HealthcareProfessionalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthcareProfessionalController extends Controller
{
    public function __construct(
        private HealthcareProfessionalService $professionalService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $professionals = $request->has('available') && $request->available
            ? $this->professionalService->getAvailableProfessionals()
            : $this->professionalService->getAllProfessionals();

        return response()->json([
            'success' => true,
            'data' => HealthcareProfessionalResource::collection($professionals),
        ]);
    }
}
