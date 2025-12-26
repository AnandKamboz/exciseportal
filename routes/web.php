<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ComplainantController;
use App\Http\Controllers\Web\HQDistrictApplicationController;
use App\Http\Controllers\Web\DetcController;
use App\Http\Controllers\Web\InspectorDashboard;
use App\Http\Controllers\Web\LogoutController;
use App\Http\Controllers\Web\UserComplaintController;
use App\Http\Controllers\Web\HqController;
use App\Http\Controllers\Web\UserDashboard;
use App\Http\Controllers\Web\JcController;
use App\Http\Controllers\Web\EtoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



Route::get('/inspector/dashboard', [InspectorDashboard::class, 'dashboard'])->name('inspector.dashboard');

Route::get('/apk',function(){
   return view('apk');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::get('/user-delete-request', function () {
    return view('user_delete_requests');
})->name('user.delete.request');



Route::post('/verify-delete-otp', function (Request $request) {

    $mobile = $request->mobile;
    $otp    = $request->otp;

    // $user = = DB::table('user_delete_requests')->where('mobile', $mobile)->first();
    $user = DB::table('user_delete_requests')
            ->where('mobile', $mobile)
            ->first();


    if (! $user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    // 2️⃣ OTP check
    if ($otp !== '111111') {
        return response()->json([
            'success' => false,
            'message' => 'Wrong OTP'
        ], 422);
    }

    // dd("Hey");

    DB::table('user_delete_requests')->where('mobile', $mobile)->delete();


   

    return response()->json([
        'success' => true,
        'message' => 'Your Account Deleted Successfully',
    ]);

});

Route::post('/check-mobile-and-send-otp', function (Request $request) {

    $mobile = $request->mobile;

    $user = DB::table('user_delete_requests')->where('mobile', $mobile)->first();

    if (! $user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    // Dummy OTP send success
    return response()->json([
        'success' => true,
        'message' => 'A 6-digit OTP has been sent to your registered mobile number'
    ]);

});





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


    Route::get('/all-applications', [DetcController::class, 'allApplications'])->name('allApplications');
    Route::get('/application/{secure_id}', [DetcController::class, 'show'])->name('application.view');
    
    Route::get('/applications/forwarded-hq', [DetcController::class, 'forwardedToHqApplications'])->name('forwarded.hq');
    Route::get('/applications/forwarded-to-eto',[DetcController::class, 'forwardedToEto'])->name('forwarded.eto');
    Route::get('/forwarded-to-eto', [DetcController::class, 'forwardedToEto'])->name('forwarded.to.eto');
    Route::get('/pending-from-applicant', [DetcController::class, 'pendingFromApplicant'])->name('pending.from.applicant');
    Route::get('/applications/pending-detc', [DetcController::class, 'pendingFromDetc'])->name('applications.pending_detc');

    // Here 
    Route::get('/applications/under-review',[DetcController::class, 'underReview'])->name('status.underreview');
    Route::get('/closed-information', [DetcController::class, 'closedInformation'])->name('final.yes');
    Route::get('/no-action', [DetcController::class, 'noActionTaken'])->name('no.action');
    Route::get('/with-eto',[DetcController::class, 'withEto'])->name('owner.eto');
    Route::get('/with-hq',[DetcController::class, 'withHq'])->name('owner.hq');

    Route::get('/applications/pending-detc', [DetcController::class, 'pendingWithDetc'])->name('applications.pending_detc');



    
});

Route::prefix('hq')->name('hq.')->middleware(['auth','hq'])->group(function () {
    Route::get('dashboard', [HqController::class, 'dashboard'])->name('dashboard');
    Route::get('/information/{secure_id}', [HqController::class, 'show'])->name('details');
    Route::get('complaints/{type}', [HqController::class, 'viewAll']);

    // Create Eto
    Route::get('/eto/list', [HqController::class, 'etoList'])->name('eto.list');
    Route::get('/eto/create', [HqController::class, 'etoCreate'])->name('eto.create');
    Route::post('/eto/store', [HqController::class, 'etoStore'])->name('eto.store');
    Route::delete('/eto/{secure_id}', [HqController::class, 'destroy'])->name('eto.delete');

    // HQ list
    Route::get('/list', [HqController::class, 'hqList'])->name('list');
    Route::get('/user/create', [HqController::class, 'createHqUser'])->name('user.create');
    Route::post('/user/store', [HqController::class, 'hqUserStore'])->name('user.store');
    Route::delete('/hq_user/{secure_id}', [HqController::class, 'hqDestroy'])->name('hq_user.delete');

    // Detc List 
    Route::get('detc/list', [HqController::class, 'detcList'])->name('detc.list');
    Route::delete('/detc/{secure_id}', [HqController::class, 'destroyDetc'])->name('detc.delete');
    Route::get('/detc/{secure_id}/edit', [HqController::class, 'editDetc'])->name('detc.edit');
    Route::put('/detc/{secure_id}', [HqController::class, 'updateDetc'])->name('detc.update');



    Route::get('/detc/transfer', [HqController::class, 'transferDetc'])->name('detc.transfer');
    Route::post('/detc/transfer/store', [HqController::class, 'transferStore'])->name('detc.transfer.store');

    Route::get('/district-applications',[HQDistrictApplicationController::class, 'districtList'])->name('district.applications');
    Route::get('/district-applications/{district_id}',[HQDistrictApplicationController::class, 'districtApplications'])->name('district.applications.list');

    // STEP 3: Application details
    Route::get('/district-application/view/{secure_id}',[HQDistrictApplicationController::class, 'applicationView'])->name('district.application.view');
    Route::get('/district-complaints/{district_name}',[HQDistrictApplicationController::class, 'districtApplications'])->name('district.complaints.list');

    Route::get('/district-complaints/application/{secure_id}',[HQDistrictApplicationController::class, 'applicationDetails'])->name('district.complaints.view');

    Route::post('/eto/toggle-status/{secure_id}',[HqController::class, 'toggleStatus'])->name('eto.toggle.status');

    Route::put('/detc.deactivate/{secure_id}', [HqController::class, 'deactivate'])->name('detc.deactivate');
    Route::post('/detc/toggle-status', [HqController::class, 'detcToggle'])->name('detc.toggle');


});

Route::prefix('jc')->name('jc.')->middleware(['auth', 'jc'])->group(function () {
    Route::get('dashboard', [JcController::class, 'dashboard'])->name('dashboard');
    Route::get('details/{secure_id}', [JcController::class, 'details'])->name('details');
    Route::post('assign/{secure_id}', [JcController::class, 'assign'])->name('assign');
});


Route::prefix('eto')->name('eto.')->middleware(['auth', 'eto'])->group(function () {
    Route::get('dashboard', [EtoController::class, 'dashboard'])->name('dashboard');
    Route::get('/information/view/{id}', [EtoController::class, 'show'])->name('information.view');
    Route::post('/action/store/{secure_id}',[EtoController::class, 'store'])->name('action.store');

});

// Route::get('eto/dashboard', [ComplainantController::class, 'dashboarda'])->name('eto.dashboard');






