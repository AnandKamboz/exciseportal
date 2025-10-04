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
        // $complaint = Complainant::where('mobile', $request->mobile)->first();
    
        // if ($complaint && $complaint->is_completed == 1) {
        //     return redirect()->back()->withErrors([
        //         'mobile' => 'Complaint already submitted for this mobile number.'
        //     ])->withInput();
        // }

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
    //                     ->where('otp', $otpInput)
    //                     ->where('is_used', false)
    //                     ->where('expires_at', '>=', Carbon::now())
    //                     ->latest()
    //                     ->first();

    //     if (!$otpRecord) {
    //         return back()->withErrors([
    //             'otp' => 'Invalid or expired OTP'
    //         ])->withInput();
    //     }

    //     $otpRecord->update(['is_used' => true]);


    //     $recordExicts = Complainant::where('mobile', $userMobile)->where('is_completed',1)->first();

    //     if($recordExists) {
    //         auth()->login($user);
    //         return response()->json([
    //             'success' => true,
    //             'redirect_url' => route('complainant'),
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
    //         ->first();
        

    //     if ($recordExists) {
    //         $user = User::where('mobile', $mobile)->first();
    //         if ($user) {
    //             auth()->login($user);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'redirect_url' => route('user.dashboard'), 
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

        // Check OTP
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

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Check if mobile exists in complainants table and is completed
        $recordExists = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 1)
            ->exists();

        if ($recordExists) {
            // Login the user if exists
            $user = User::firstOrCreate(['mobile' => $mobile]);
            auth()->login($user);

            return response()->json([
                'success' => true,
                'redirect_url' => route('user.dashboard'),
                'message' => 'Login successful!',
            ]);
        }

        // If no completed complainant exists, create or get user
        do {
            $secureId = Str::random(32);
        } while (User::where('secure_id', $secureId)->exists());

        $user = User::firstOrCreate(
            ['mobile' => $mobile],
            ['secure_id' => $secureId]
        );

        // Assign default role if new user
        if ($user->wasRecentlyCreated) {
            $defaultRole = RoleGroup::where('role_name', 'user')->first();
            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id);
            }
        }

        // Log in the user
        auth()->login($user);

        // Save mobile in session for later use
        $request->session()->put('mobile', $mobile);

        return response()->json([
            'success' => true,
            'redirect_url' => route('complainant'),
            'message' => 'Login successful!',
        ]);
    }

}
