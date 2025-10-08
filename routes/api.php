<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\HealthcareProfessionalController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    // 10 requests per 1 minute for register and login
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes - auth:sanctum middleware applied
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth routes
    Route::prefix('auth')->middleware('throttle:30,1')->group(function () {
        // 30 requests per 1 minute for logout and me endpoints
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Healthcare professionals routes
    Route::get('/healthcare-professionals', [HealthcareProfessionalController::class, 'index'])
        ->middleware('throttle:60,1'); // 60 requests per minute

    // Appointment routes
    Route::prefix('appointments')->middleware('throttle:60,1')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/{id}', [AppointmentController::class, 'show']);
        Route::patch('/{id}/cancel', [AppointmentController::class, 'cancel']);
        Route::patch('/{id}/complete', [AppointmentController::class, 'complete']);
    });
});
