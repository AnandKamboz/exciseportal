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
    Route::get('/states', [ComplainantController::class, 'getStates']);
    Route::get('/districts/{state_id}', [ComplainantController::class, 'getDistricts']);
    Route::post('/save-informer',[ComplainantController::class,'saveInformer']);
    Route::get('/haryana/districts', [ComplainantController::class, 'getHaryanaDistrict']);
    Route::post('/submit-complaint', [ComplainantController::class, 'submitComplaint']); 
    Route::get('/user/dashboard', [UserDashboardController::class, 'userDashboard']);
    Route::get('/user/complaint/{secure_id}', [UserDashboardController::class, 'show']);
});