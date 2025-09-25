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
        $complaint = Complainant::where('mobile', $request->mobile)->first();
    
        if ($complaint && $complaint->is_completed == 1) {
            return redirect()->back()->withErrors([
                'mobile' => 'Complaint already submitted for this mobile number.'
            ])->withInput();
        }

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

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
            'otp' => 'required|numeric|digits:6',
        ]);

        $mobile = $request->mobile;
        $otpInput = $request->otp;

        $complaint = Complainant::where('mobile', $mobile)->first();
        if ($complaint && $complaint->is_completed == 1) {
            return back()->withErrors([
                'mobile' => 'Complaint already submitted for this mobile number.'
            ])->withInput();
        }

        $otpRecord = Otp::where('mobile', $mobile)
                        ->where('otp', $otpInput)
                        ->where('is_used', false)
                        ->where('expires_at', '>=', Carbon::now())
                        ->latest()
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP'
            ])->withInput();
        }

        $otpRecord->update(['is_used' => true]);

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

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
        ]);
    }

}
