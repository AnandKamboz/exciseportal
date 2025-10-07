<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\ComplainantController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\UserDashboardController;
use App\Http\Controllers\Api\DetcController;
use App\Http\Controllers\Api\InspectorDashboardController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
    ]);
});

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/complaints/step-first', [ComplainantController::class, 'storeFirstStep']);
    Route::post('/complaints/step-second', [ComplainantController::class, 'storeSecondStep']);
    Route::get('/districts', [ComplainantController::class, 'getAllDistricts']);
    Route::post('/complaints/step-third', [ComplainantController::class, 'storeThirdStep']);
    Route::post('/complaints/store', [ComplainantController::class, 'store']);
    Route::get('/user/dashboard', [UserDashboardController::class, 'userDashboard']);
    Route::get('user/complaint/{secure_id}', [ComplainantController::class, 'getComplaintBySecureId']);
    Route::post('/logout', [LogoutController::class, 'logout']);


    // Detc Routes 
    Route::get('/detc/dashboard', [DetcController::class, 'getDashboardData']);
    Route::get('detc/complaint/{secure_id}', [DetcController::class, 'getComplaintDetails']);
    Route::post('/complaint/{secure_id}/update-status', [DetcController::class, 'updateComplaintStatus']);

    Route::get('/inspector/dashboard',[InspectorDashboardController::class, 'dashboard']);
    Route::get('/inspector/complaint/{secure_id}',[InspectorDashboardController::class, 'showComplaint']);
    Route::post('/inspector/inspection-report',[InspectorDashboardController::class, 'storeInspectionReport']);
    Route::get('/inspector/feedback', [InspectorDashboardController::class, 'inspectorFeedback']);
});
