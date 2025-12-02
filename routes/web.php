<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ComplainantController;
use App\Http\Controllers\Web\DetcController;
use App\Http\Controllers\Web\InspectorDashboard;
use App\Http\Controllers\Web\LogoutController;
use App\Http\Controllers\Web\UserComplaintController;
use App\Http\Controllers\Web\HqController;
use App\Http\Controllers\Web\UserDashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mews\Captcha\Facades\Captcha;


Route::get('/inspector/dashboard', [InspectorDashboard::class, 'dashboard'])->name('inspector.dashboard');

Route::get('/', function () {
    return view('entry');
})->name('entry');

Route::get('/login', function () {
    $captcha_value = Str::random(6);
    session(['captcha' => $captcha_value]);

    return view('welcome');
})->name('login');

Route::get('captcha', function () {
    return Captcha::create();
});

Route::get('captcha/{config?}', function ($config = 'default') {
    return Captcha::create($config);
});

Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');

Route::middleware(['auth', 'userrole'])->group(function () {
    Route::get('user/complainant', [ComplainantController::class, 'complainant'])->name('complainant');
    Route::post('/save-informer', [ComplainantController::class, 'saveInformer'])->name('save.informer');
    Route::post('/save-complaint-type', [ComplainantController::class, 'saveComplaintType'])->name('save.complaint.type');
    Route::post('/submit-complaint', [ComplainantController::class, 'submitComplaint'])->name('submit.complaint');
    Route::get('/user/complaint/{secure_id}', [UserComplaintController::class, 'show'])->name('user.setails');
    Route::get('/user/dashboard', [UserDashboard::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/get-dist', [ComplainantController::class, 'getDistrict'])->name('get-dist');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::prefix('detc')->name('detc.')->middleware(['auth', 'detcrole'])->group(function () {
    Route::get('/dashboard', [DetcController::class, 'dashboard'])->name('dashboard');
    Route::get('/complaint/{secure_id}', [DetcController::class, 'show'])->name('details');
    Route::post('/action/store/{secure_id}', [DetcController::class, 'store'])->name('action.store');
});

Route::prefix('hq')->name('hq.')->middleware(['auth','hq'])->group(function () {
    Route::get('dashboard', [HqController::class, 'dashboard'])->name('dashboard');
    Route::get('/information/{secure_id}', [HqController::class, 'show'])->name('details');
});