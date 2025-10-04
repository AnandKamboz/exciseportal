<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserDashboard;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ComplainantController;
use App\Http\Controllers\Web\LogoutController;
use App\Http\Controllers\Web\UserComplaintController;
use App\Http\Controllers\Web\DetcController;
use App\Http\Controllers\Web\InspectorDashboard;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
Route::get('/complainant', [ComplainantController::class, 'complainant'])->name('complainant');


Route::post('/complaints/step-first', [ComplainantController::class, 'storeFirstStep'])->name('complaints.step-first');
Route::post('/complaints/step-second', [ComplainantController::class, 'storeSecondStep'])->name('complaints.step-second');
Route::post('/complaints/step-third', [ComplainantController::class, 'storeThirdStep'])->name('complaints.step-third');
Route::post('/complaints/step-fourth', [ComplainantController::class, 'store'])->name('complaints.store');
Route::get('/user-data', [ComplainantController::class, 'getUserData'])->name('user.data');
Route::get('/user/dashboard', [UserDashboard::class, 'userDashboard'])->name('user.dashboard');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/user/complaint/{secure_id}', [UserComplaintController::class, 'show'])->name('user.setails');

// inspector.dashboard

Route::get('/detc/dashboard', [DetcController::class, 'dashboard'])->name('detc.dashboard');
Route::get('detc/complaint/{secure_id}', [DetcController::class, 'show'])->name('detc.details');


// Here 
Route::post('/user/complaint/update/{secure_id}', [DetcController::class, 'updateComplaintStatus'])
    ->name('user.updateComplaintStatus');



// Inspector routes
Route::get('/inspector/dashboard', [InspectorDashboard::class, 'dashboard'])->name('inspector.dashboard');
Route::get('inspector/complaint/{secure_id}', [InspectorDashboard::class, 'show'])->name('inspector.details');




