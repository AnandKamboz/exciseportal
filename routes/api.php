<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComplainantController;
use App\Http\Controllers\Api\UserDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
    ]);
});

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/states', [ComplainantController::class, 'getStates']);
    Route::get('/districts/{state_id}', [ComplainantController::class, 'getDistricts']);
    Route::post('/save-informer', [ComplainantController::class, 'saveInformer']);
    Route::get('/haryana/districts', [ComplainantController::class, 'getHaryanaDistrict']);
    Route::post('/submit-complaint', [ComplainantController::class, 'submitComplaint']);
    Route::get('/user/dashboard', [UserDashboardController::class, 'userDashboard']);
    Route::get('/user/complaint/{secure_id}', [UserDashboardController::class, 'show']);
    Route::post('/complaint/{secure_id}/submit-missing-info', [ComplainantController::class, 'submitMissingInfoApi']);

    // For user profile
    Route::get('user/profile', [UserDashboardController::class, 'profile']);
    Route::post('user/update-profile', [UserDashboardController::class, 'update']);
});

// Route::post('/complaint/{secure_id}/submit-missing-info', [ComplainantController::class, 'submitMissingInfoApi']);


// Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

// });

// userrole    , middelware
// ✔ 2. Response हमेशा JSON में (Force JSON)

// users table me , is_active
// ForceJsonResponse  60 request per minute
