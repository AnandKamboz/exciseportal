<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ComplainantController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');


Route::get('/complainant', [ComplainantController::class, 'complainant'])->name('complainant');
Route::post('/complaints/step-first', [ComplainantController::class, 'storeFirstStep'])->name('complaints.step-first');
Route::post('/complaints/step-second', [ComplainantController::class, 'storeSecondStep'])->name('complaints.step-second');
Route::post('/complaints/step-third', [ComplainantController::class, 'store'])->name('complaints.store');
Route::get('/user-data', [ComplainantController::class, 'getUserData'])->name('user.data');

