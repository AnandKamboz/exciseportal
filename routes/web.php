<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ComplainantController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


Route::get('/complainant', [ComplainantController::class, 'complainant'])->name('complainant');
Route::post('/complaints/step-first', [ComplainantController::class, 'storeFirstStep'])->name('complaints.step-first');
Route::post('/complaints/step-second', [ComplainantController::class, 'storeSecondStep'])->name('complaints.step-second');
Route::post('/complaints/step-third', [ComplainantController::class, 'storeThirdStep'])->name('complaints.step-third');
