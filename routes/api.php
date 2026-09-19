<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;
use Illuminate\Support\Facades\Route;

Route::apiResource('appointments', AppointmentController::class);
Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
Route::apiResource('doctors', DoctorController::class)->only(['index', 'show']);
Route::apiResource('patients', PatientController::class)->only(['index', 'show']);
