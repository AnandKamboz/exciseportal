<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\Otp;
use App\Models\RoleGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ], 200);
    }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:6',
    //     ]);

    //     $otpRecord = Otp::where('mobile', $request->mobile)
    //         ->where('otp', $request->otp)
    //         ->where('is_used', false)
    //         ->where('expires_at', '>=', now())
    //         ->latest()
    //         ->first();

    //     if (! $otpRecord) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid or expired OTP',
    //         ], 422);
    //     }

    //     $otpRecord->update(['is_used' => true]);

    //     $user = User::where('mobile', $request->mobile)->first();

    //     if (! $user) {

    //         do {
    //             $secureId = Str::random(32);
    //         } while (User::where('secure_id', $secureId)->exists());

    //         $user = User::create([
    //             'mobile' => $request->mobile,
    //             'secure_id' => $secureId,
    //         ]);

    //         $defaultRole = RoleGroup::where('role_name', 'user')->first();
    //         if ($defaultRole) {
    //             $user->roles()->attach($defaultRole->id);
    //         }
    //     }

    //     $token = $user->createToken('api_token')->plainTextToken;

    //     $role = $user->roles()->pluck('role_name')->first() ?? 'user';

    //     $hasCompletedComplaint = Complainant::where('complainant_phone', $request->mobile)
    //         ->where('is_completed', 1)
    //         ->exists();

    //     $isNewUser = ! $hasCompletedComplaint;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login successful',
    //         'token' => $token,
    //         'user' => $user,
    //         'role' => $role,
    //         'is_new_user' => $isNewUser,
    //     ], 200);
    // }

    public function verifyOtp(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'mobile' => 'required|numeric|digits:10',
                'otp' => 'required|numeric|digits:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $mobile = $request->mobile;
            $otpInput = $request->otp;

            $otpRecord = Otp::where([
                'mobile' => $mobile,
                'otp' => $otpInput,
                'is_used' => false,
            ])
                ->where('expires_at', '>=', now())
                ->latest()
                ->first();

            if (! $otpRecord) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired OTP',
                ], 400);
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

            $role = $user->roles()->pluck('role_name')->first();

            $complaintExists = DB::table('complainants')
                ->where('complainant_phone', $mobile)
                ->where('is_completed', 1)
                ->exists();

            $isNewUser = $complaintExists ? false : true;

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful!',
                'user' => [
                    'mobile' => $user->mobile,
                    'secure_id' => $user->secure_id,
                    'role' => $role,
                ],
                'is_new_user' => $isNewUser,
                'token' => $token,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
