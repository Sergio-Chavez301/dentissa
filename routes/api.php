<?php

use App\Http\Controllers\Api\MobileController;
use App\Http\Middleware\EnsureActiveMobilePatient;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {
    Route::post('/login', [MobileController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/forgot-password', [MobileController::class, 'forgotPassword'])->middleware('throttle:3,1');

    Route::middleware(['auth:sanctum', EnsureActiveMobilePatient::class, 'throttle:60,1'])->group(function () {
        Route::post('/logout', [MobileController::class, 'logout']);
        Route::get('/dashboard', [MobileController::class, 'dashboard']);
        Route::get('/availability', [MobileController::class, 'availability']);
        Route::post('/devices', [MobileController::class, 'registerPushDevice']);
        Route::delete('/devices', [MobileController::class, 'removePushDevice']);
        Route::post('/appointments', [MobileController::class, 'storeAppointment'])
            ->middleware('throttle:10,1');
        Route::post('/treatment-appointments', [MobileController::class, 'storeTreatmentAppointment'])
            ->middleware('throttle:10,1');
        Route::post('/appointments/{appointment}/cancel', [MobileController::class, 'cancelAppointment']);
    });
});
