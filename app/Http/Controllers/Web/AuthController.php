<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\Complainant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RoleGroup;
use Illuminate\Support\Str;



class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
        ]);

        if (env('APP_ENV') === 'local') {
            $otp = '111111';
        } else {
            $otp = rand(100000, 999999);
        }

        Otp::create([
            'mobile' => $request->mobile,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $request->session()->put('mobile', $request->mobile);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to ' . $request->mobile
        ]);
    }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:6',
    //     ]);

    //     $mobile = $request->mobile;
    //     $otpInput = $request->otp;

       
    //     $otpRecord = Otp::where('mobile', $mobile)
    //         ->where('otp', $otpInput)
    //         ->where('is_used', false)
    //         ->where('expires_at', '>=', Carbon::now())
    //         ->latest()
    //         ->first();

    //     if (!$otpRecord) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid or expired OTP'
    //         ]);
    //     }

    //     $otpRecord->update(['is_used' => true]);

      
    //     $recordExists = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 1)
    //         ->exists();

    //     if ($recordExists) {
    //         $user = User::firstOrCreate(['mobile' => $mobile]);
    //         auth()->login($user);

    //         return response()->json([
    //             'success' => true,
    //             'redirect_url' => route('user.dashboard'),
    //             'message' => 'Login successful!',
    //         ]);
    //     }

       
    //     do {
    //         $secureId = Str::random(32);
    //     } while (User::where('secure_id', $secureId)->exists());

    //     $user = User::firstOrCreate(
    //         ['mobile' => $mobile],
    //         ['secure_id' => $secureId]
    //     );

       
    //     if ($user->wasRecentlyCreated) {
    //         $defaultRole = RoleGroup::where('role_name', 'user')->first();
    //         if ($defaultRole) {
    //             $user->roles()->attach($defaultRole->id);
    //         }
    //     }

       
    //     auth()->login($user);

       
    //     $request->session()->put('mobile', $mobile);

    //     return response()->json([
    //         'success' => true,
    //         'redirect_url' => route('complainant'),
    //         'message' => 'Login successful!',
    //     ]);
    // }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:6',
    //     ]);

    //     $mobile = $request->mobile;
    //     $otpInput = $request->otp;

    //     $otpRecord = Otp::where('mobile', $mobile)
    //         ->where('otp', $otpInput)
    //         ->where('is_used', false)
    //         ->where('expires_at', '>=', Carbon::now())
    //         ->latest()
    //         ->first();

    //     if (!$otpRecord) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid or expired OTP'
    //         ]);
    //     }

    //     $otpRecord->update(['is_used' => true]);

    //     $recordExists = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 1)
    //         ->exists();

    //     if ($recordExists) {
    //         $user = User::firstOrCreate(['mobile' => $mobile]);

    //         // **Role check for existing user**
    //         $role = $user->roles()->pluck('role_name')->first(); 

    //         auth()->login($user);

    //         $redirectUrl = route('user.dashboard'); // default

    //         if ($role === 'detc') {
    //             $redirectUrl = route('detc.dashboard');
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'redirect_url' => $redirectUrl,
    //             'message' => 'Login successful!',
    //         ]);
    //     }

    //     do {
    //         $secureId = Str::random(32);
    //     } while (User::where('secure_id', $secureId)->exists());

    //     $user = User::firstOrCreate(
    //         ['mobile' => $mobile],
    //         ['secure_id' => $secureId]
    //     );

    //     if ($user->wasRecentlyCreated) {
    //         $defaultRole = RoleGroup::where('role_name', 'user')->first();
    //         if ($defaultRole) {
    //             $user->roles()->attach($defaultRole->id);
    //         }
    //     }

    //     auth()->login($user);

    //     $request->session()->put('mobile', $mobile);

    //     return response()->json([
    //         'success' => true,
    //         'redirect_url' => route('complainant'),
    //         'message' => 'Login successful!',
    //     ]);
    // }


    public function verifyOtp(Request $request)
{
    $request->validate([
        'mobile' => 'required|numeric|digits:10',
        'otp' => 'required|numeric|digits:6',
    ]);

    $mobile = $request->mobile;
    $otpInput = $request->otp;

    // OTP check
    $otpRecord = Otp::where('mobile', $mobile)
        ->where('otp', $otpInput)
        ->where('is_used', false)
        ->where('expires_at', '>=', Carbon::now())
        ->latest()
        ->first();

    if (!$otpRecord) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP'
        ]);
    }

    $otpRecord->update(['is_used' => true]);

    // Get or create user
    do {
        $secureId = Str::random(32);
    } while (User::where('secure_id', $secureId)->exists());

    $user = User::firstOrCreate(
        ['mobile' => $mobile],
        ['secure_id' => $secureId]
    );

    if ($user->wasRecentlyCreated) {
        $defaultRole = RoleGroup::where('role_name', 'user')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }
    }

    auth()->login($user);
    $request->session()->put('mobile', $mobile);

    $role = $user->roles()->pluck('role_name')->first(); 

    $recordExists = Complainant::where('complainant_phone', $mobile)
        ->where('is_completed', 1)
        ->exists();

    // if ($role === 'detc') {
    //     $redirectUrl = route('detc.dashboard');
    // } elseif ($role === 'excise inspector') {
    //     $redirectUrl = route('inspector.dashboard'); 
    // } elseif ($recordExists || $role === 'user') {
    //     $redirectUrl = $recordExists ? route('user.dashboard') : route('complainant');
    // } else {
    //     $redirectUrl = route('home'); 
    // }

        // if ($role === 'detc') {
        //     $redirectUrl = route('detc.dashboard');
        // } elseif ($role === 'excise inspector') {
        //     $redirectUrl = route('inspector.dashboard'); 
        // } else {
        //     $redirectUrl = $recordExists ? route('user.dashboard') : route('complainant');
        // } 
        if ($role === 'detc') {
            $redirectUrl = route('detc.dashboard');
        } elseif ($role === 'excise inspector') {
            $redirectUrl = route('inspector.dashboard'); 
        } else {
            $redirectUrl = $recordExists ? route('user.dashboard') : route('complainant');
        }


    return response()->json([
        'success' => true,
        'redirect_url' => $redirectUrl,
        'message' => 'Login successful!',
    ]);
}



}
