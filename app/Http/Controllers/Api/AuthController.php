<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Models\RoleGroup;
use App\Models\Complainant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
 {
    public function sendOtp( Request $request )
    {
        $request->validate( [
            'mobile' => 'required|numeric|digits:10',
        ] );


        if ( env( 'APP_ENV' ) === 'local' ) {
            $otp = '111111';
        } else {
            $otp = rand( 100000, 999999 );
        }

        Otp::create( [
            'mobile' => $request->mobile,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes( 5 ),
        ] );


        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ], 200);
    }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp'    => 'required|numeric|digits:6',
    //     ]);

    //     $otpRecord = Otp::where('mobile', $request->mobile)
    //         ->where('otp', $request->otp)
    //         ->where('is_used', false)
    //         ->where('expires_at', '>=', Carbon::now())
    //         ->latest()
    //         ->first();

    //     if (!$otpRecord) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid or expired OTP'
    //         ], 422);
    //     }

    //     $otpRecord->update(['is_used' => true]);

    //     $user = User::where('mobile', $request->mobile)->first();

    //     if (!$user) {
    //         do {
    //             $secureId = Str::random(32);
    //         } while (User::where('secure_id', $secureId)->exists());

    //         $user = User::create([
    //             'mobile'    => $request->mobile,
    //             'secure_id' => $secureId,
    //         ]);

    //         $defaultRole = RoleGroup::where('role_name', 'user')->first();
    //         if ($defaultRole) {
    //             $user->roles()->attach($defaultRole->id);
    //         }
    //     }

    //     $token = $user->createToken('api_token')->plainTextToken;

    //     $recordExists = Complainant::where('complainant_phone', $request->mobile)
    //         ->where('is_completed', 1)
    //         ->exists();

    //     $isNewUser = $recordExists ? 1 : 0;

    //     return response()->json([
    //         'success'     => true,
    //         'message'     => 'Login successful',
    //         'token'       => $token,
    //         'user'        => $user,
    //         'is_new_user' => $isNewUser,
    //     ], 200);
    // }

    public function verifyOtp(Request $request)
{
    $request->validate([
        'mobile' => 'required|numeric|digits:10',
        'otp'    => 'required|numeric|digits:6',
    ]);

    $otpRecord = Otp::where('mobile', $request->mobile)
        ->where('otp', $request->otp)
        ->where('is_used', false)
        ->where('expires_at', '>=', now())
        ->latest()
        ->first();

    if (!$otpRecord) {
        return response()->json([
            'status'  => false,
            'message' => 'Invalid or expired OTP',
        ], 422);
    }

    // Mark OTP as used
    $otpRecord->update(['is_used' => true]);

    // Check if user exists
    $user = User::where('mobile', $request->mobile)->first();

    if (!$user) {
        // Generate secure_id
        do {
            $secureId = Str::random(32);
        } while (User::where('secure_id', $secureId)->exists());

        // Create new user
        $user = User::create([
            'mobile'    => $request->mobile,
            'secure_id' => $secureId,
        ]);

        // Assign default role "user"
        $defaultRole = RoleGroup::where('role_name', 'user')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }
    }

    // Generate API token
    $token = $user->createToken('api_token')->plainTextToken;

    // Get user's role
    $role = $user->roles()->pluck('role_name')->first() ?? 'user';

    // Check if user has already completed a complaint
    $hasCompletedComplaint = Complainant::where('complainant_phone', $request->mobile)
        ->where('is_completed', 1)
        ->exists();

    // Determine if it's a new user
    $isNewUser = !$hasCompletedComplaint; // true if no completed complaint found

    return response()->json([
        'status'       => true,
        'message'      => 'Login successful',
        'token'        => $token,
        'user'         => $user,
        'role'         => $role,
        'is_new_user'  => $isNewUser,
    ], 200);
}

}
