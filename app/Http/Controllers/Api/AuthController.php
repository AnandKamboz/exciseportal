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

        // $complaint = Complainant::where( 'mobile', $request->mobile )->first();

        // if ( $complaint && $complaint->is_completed == 1 ) {
        //     return response()->json( [
        //         'message' => 'Complaint already submitted for this mobile number.'
        // ], 403 );
        // }

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

        // return response()->json( [
        //     'message' => 'OTP sent successfully',
        // ] );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ], 200);
    }

    // public function verifyOtp( Request $request )
    // {
    //     $request->validate( [
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:6',
    //     ] );

    //     $otpRecord = Otp::where( 'mobile', $request->mobile )
    //     ->where( 'otp', $request->otp )
    //     ->where( 'is_used', false )
    //     ->where( 'expires_at', '>=', Carbon::now() )
    //     ->latest()
    //     ->first();

    //     if ( !$otpRecord ) {
    //         return response()->json( [ 'message' => 'Invalid or expired OTP' ], 422 );
    //     }

    //     $otpRecord->update( [ 'is_used' => true ] );

    //     $exists = User::where( 'mobile', $request->mobile )->exists();
        
    //     if ( !$exists ) {
    //         do {
    //             $secureId = Str::random( 32 );
    //         }
    //         while ( User::where( 'secure_id', $secureId )->exists() );

    //         $user = User::firstOrCreate(
    //             [ 'mobile' => $request->mobile ],
    //             [
    //                 'secure_id' => $secureId,
    //             ]
    //         );

    //         if ( $user->wasRecentlyCreated ) {
    //             $defaultRole = RoleGroup::where( 'role_name', 'user' )->first();

    //             if ( $defaultRole ) {
    //                 $user->roles()->attach( $defaultRole->id );
    //             }
    //         }
    //     }

    //     $token = $user->createToken( 'api_token' )->plainTextToken;

    //     return response()->json( [
    //         'message' => 'Login successful',
    //         'token' => $token,
    //         // 'user' => $user
    //     ] );
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
        ->where('expires_at', '>=', Carbon::now())
        ->latest()
        ->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid or expired OTP'], 422);
    }

    $otpRecord->update(['is_used' => true]);

    // Check user existence
    $user = User::where('mobile', $request->mobile)->first();

    if (!$user) {
        do {
            $secureId = Str::random(32);
        } while (User::where('secure_id', $secureId)->exists());

        $user = User::create([
            'mobile'    => $request->mobile,
            'secure_id' => $secureId,
        ]);

        // Attach default role
        $defaultRole = RoleGroup::where('role_name', 'user')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }
    }

    // Create token for both existing & new user
    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'token'   => $token,
        'user'    => $user, // optional, helpful in frontend
    ]);
}

}
