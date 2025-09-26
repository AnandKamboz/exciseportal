<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComplainantController;


Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
    ]);
});


Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);



Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/complaints/step-first', [ComplainantController::class, 'storeFirstStep']);
    // Route::post('/complaints/step-second', [ComplainantController::class, 'storeSecondStep']);
    // Route::post('/complaints/third-step', [ComplainantController::class, 'storeThirdStep']);
    Route::post('/complaints/step-first-x9f2kq', [ComplainantController::class, 'storeFirstStep']);
    Route::post('/complaints/step-second-b7p4mz', [ComplainantController::class, 'storeSecondStep']);
    Route::post('/complaints/final-step-t6y9dw', [ComplainantController::class, 'store']);
});