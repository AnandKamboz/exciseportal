<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\Otp;
use App\Models\RoleGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



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
            'message' => 'OTP sent successfully to '.$request->mobile,
        ]);
    }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:6',
    //         'captcha' => 'required|size:6',
    //     ]);

    //     $mobile = $request->mobile;
    //     $otpInput = $request->otp;
    //     $captcha = $request->captcha;

    //     if ($captcha !== session('captcha')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid captcha'
    //         ]);
    //     }

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

    //     $role = $user->roles()->pluck('role_name')->first();

    //     if ($role === 'detc') {
    //         $redirectUrl = route('detc.dashboard');
    //     } elseif ($role === 'excise inspector') {
    //         $redirectUrl = route('inspector.dashboard');
    //     } else {
    //         // ✅ Always redirect to user dashboard
    //         $redirectUrl = route('user.dashboard');
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'redirect_url' => $redirectUrl,
    //         'message' => 'Login successful!',
    //     ]);
    // }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:6',
    //         'captcha' => 'required|size:6',
    //     ]);

    //     $mobile = $request->mobile;
    //     $otpInput = $request->otp;
    //     $captcha = $request->captcha;

    //     if ($captcha !== session('captcha')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid captcha'
    //         ]);
    //     }

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

    //     $role = $user->roles()->pluck('role_name')->first();

    //     $recordExists = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 1)
    //         ->exists();

    //         if ($role === 'detc') {
    //             $redirectUrl = route('detc.dashboard');
    //         } elseif ($role === 'excise inspector') {
    //             $redirectUrl = route('inspector.dashboard');
    //         } else {
    //             $redirectUrl = $recordExists ? route('user.dashboard') : route('complainant');
    //         }

    //     return response()->json([
    //         'success' => true,
    //         'redirect_url' => $redirectUrl,
    //         'message' => 'Login successful!',
    //     ]);
    // }

    public function verifyOtp(Request $request)
    {

        // $request->validate([
        //     'mobile'  => 'required|numeric|digits:10',
        //     'otp'     => 'required|numeric|digits:6',
        //     'captcha' => 'required|size:6|captcha',
        // ], [
        //     'mobile.required'  => 'Mobile number is required.',
        //     'mobile.numeric'   => 'Mobile number must be numeric.',
        //     'mobile.digits'    => 'Mobile number must be exactly 10 digits.',
        //     'otp.required'     => 'OTP is required.',
        //     'otp.numeric'      => 'OTP must be numeric.',
        //     'otp.digits'       => 'OTP must be exactly 6 digits.',
        //     'captcha.required' => 'Captcha is required.',
        //     'captcha.size'     => 'Captcha must be 6 characters.',
        //     'captcha.captcha'  => 'Captcha is invalid. Please try again.',
        // ]);

            $validator = Validator::make($request->all(), [
            'mobile'  => 'required|numeric|digits:10',
            'otp'     => 'required|numeric|digits:6',
            'captcha' => 'required|size:6|captcha',
        ], [
            'mobile.required'  => 'Mobile number is required.',
            'mobile.numeric'   => 'Mobile number must be numeric.',
            'mobile.digits'    => 'Mobile number must be exactly 10 digits.',
            'otp.required'     => 'OTP is required.',
            'otp.numeric'      => 'OTP must be numeric.',
            'otp.digits'       => 'OTP must be exactly 6 digits.',
            'captcha.required' => 'Captcha is required.',
            'captcha.size'     => 'Captcha must be 6 characters.',
            'captcha.captcha'  => 'Captcha is invalid. Please try again.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(), 
                'new_captcha' => captcha_src(),           
            ]);
        }

    
        $mobile = $request->mobile;
        $otpInput = $request->otp;
        $captcha = $request->captcha;




        // ✅ OTP check
        $otpRecord = Otp::where('mobile', $mobile)
            ->where('otp', $otpInput)
            ->where('is_used', false)
            ->where('expires_at', '>=', Carbon::now())
            ->latest()
            ->first();

        if (! $otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ]);
        }

        // ✅ Mark OTP used
        $otpRecord->update(['is_used' => true]);

        // ✅ Generate new secure_id
        do {
            $secureId = Str::random(32);
        } while (User::where('secure_id', $secureId)->exists());

        // ✅ Create user record (insert if not exists)
        $user = User::firstOrCreate(
            ['mobile' => $mobile],
            ['secure_id' => $secureId]
        );

        // ✅ Assign default role if newly created
        if ($user->wasRecentlyCreated) {
            $defaultRole = RoleGroup::where('role_name', 'user')->first();
            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id);
            }
        }

        // ✅ Login and session
        auth()->login($user);
        $request->session()->put('mobile', $mobile);

        // ✅ Get role name
        $role = $user->roles()->pluck('role_name')->first();

        // ✅ Dashboard redirection based on role
        if ($role === 'detc') {
            $redirectUrl = route('detc.dashboard');
        } elseif ($role === 'excise inspector') {
            $redirectUrl = route('inspector.dashboard');
        } else {
            $redirectUrl = route('user.dashboard');
        }

        if ($role === 'detc') {
                $redirectUrl = route('detc.dashboard');
            } elseif ($role === 'excise inspector') {
                $redirectUrl = route('inspector.dashboard');
            } else {
                $mobile = Auth::user()->mobile;
                $complaintExists = DB::table('complainants')
                    ->where('complainant_phone', $mobile)
                    ->where('is_completed', 1)
                    ->exists();

                if ($complaintExists) {
                    $redirectUrl = route('user.dashboard');
                } else {
                    $redirectUrl = route('complainant');
                }
        }

        return response()->json([
            'success' => true,
            'redirect_url' => $redirectUrl,
            'message' => 'Login successful!',
        ]);
    }
}
