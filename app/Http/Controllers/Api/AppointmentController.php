<?php

/**
 * File: AppointmentController.php
 * Description: Controller to handle appointment operations
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
use App\Http\Requests\Appointment\CancelAppointmentRequest;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {
        // Middleware is already applied in routes/api.php
        // No need to add it here in Laravel 11
    }

    public function index(Request $request): JsonResponse
    {
        $appointments = $this->appointmentService->getUserAppointments(
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'data' => AppointmentResource::collection($appointments),
        ]);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = $this->appointmentService->bookAppointment([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your appointment has been booked successfully.',
            'data' => new AppointmentResource($appointment->load('healthcareProfessional')),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $appointment = $this->appointmentService->getUserAppointments($request->user()->id)
            ->firstWhere('id', $id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'No Appointments found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function cancel(CancelAppointmentRequest $request, int $id): JsonResponse
    {
        $this->appointmentService->cancelAppointment(
            $id,
            $request->user()->id,
            $request->validated('cancellation_reason')
        );

        return response()->json([
            'success' => true,
            'message' => 'Appointment cancelled successfully',
        ]);
    }

    public function complete(Request $request, int $id): JsonResponse
    {
        $this->appointmentService->completeAppointment($id);

        return response()->json([
            'success' => true,
            'message' => 'Appointment marked as completed',
        ]);
    }
}
