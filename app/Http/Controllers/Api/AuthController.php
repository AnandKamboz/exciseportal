<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\RoleGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // public function sendOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|numeric|digits:10',
    //     ]);

    //     if (env('APP_ENV') === 'local') {
    //         $otp = '111111';
    //     } else {
    //         $otp = rand(100000, 999999);
    //     }

    //     Otp::create([
    //         'mobile' => $request->mobile,
    //         'otp' => $otp,
    //         'expires_at' => Carbon::now()->addMinutes(5),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'OTP sent successfully',
    //     ], 200);
    // }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
        ]);
        Otp::where('mobile', $request->mobile)->delete();

        $otp = rand(100000, 999999);

        Otp::create([
            'mobile' => $request->mobile,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $message = "Dear User, $otp is OTP for Kar Hiteshi, Excise Department, Government of Haryana";

        $template_id = '1407176526044359486';

        $this->sendSMS($request->mobile, $message, $template_id);

        $mobileMasked = '******'.substr($request->mobile, -4);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to '.$mobileMasked,
        ]);
    }

    public function sendSMS($mobile, $message, $temp_id)
    {
        $username = 'haryanait-saksham';
        $password = 'Saksham@123';
        $senderid = 'GOVHRY';
        $dept_key = '29d9b6c5-3477-4cb5-90eb-b0e5b478717d';

        $encryp_password = sha1(trim($password));

        return $this->sendSingleSMS(
            $username,
            $encryp_password,
            $senderid,
            $message,
            $mobile,
            $dept_key,
            $temp_id
        );
    }

    public function sendSingleSMS($username, $encryp_password, $senderid, $message, $mobileno, $deptSecureKey, $temp_id)
    {
        $key = hash('sha512', trim($username).trim($senderid).trim($message).trim($deptSecureKey));

        $data = [
            'username' => trim($username),
            'password' => trim($encryp_password),
            'senderid' => trim($senderid),
            'content' => trim($message),
            'smsservicetype' => 'otpmsg',
            'mobileno' => trim($mobileno),
            'key' => trim($key),
            'templateid' => trim($temp_id),
        ];

        return $this->postToUrl('https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT', $data);
    }

    public function postToUrl($url, $data)
    {
        $fields = '';

        foreach ($data as $key => $value) {
            $fields .= $key.'='.$value.'&';
        }

        rtrim($fields, '&');

        $post = curl_init();
        curl_setopt($post, CURLOPT_SSLVERSION, 6);
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($post);
        curl_close($post);

        return $result;
    }

    // public function verifyOtp(Request $request)
    // {
    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'mobile' => 'required|numeric|digits:10',
    //             'otp' => 'required|numeric|digits:6',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $validator->errors()->first(),
    //             ], 422);
    //         }

    //         $mobile = $request->mobile;
    //         $otpInput = $request->otp;

    //         $otpRecord = Otp::where([
    //             'mobile' => $mobile,
    //             'otp' => $otpInput,
    //             'is_used' => false,
    //         ])
    //             ->where('expires_at', '>=', now())
    //             ->latest()
    //             ->first();

    //         if (! $otpRecord) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Invalid or expired OTP',
    //             ], 400);
    //         }

    //         $otpRecord->update(['is_used' => true]);

    //         do {
    //             $secureId = Str::random(32);
    //         } while (User::where('secure_id', $secureId)->exists());

    //         $user = User::firstOrCreate(
    //             ['mobile' => $mobile],
    //             ['secure_id' => $secureId]
    //         );

    //         if ($user->wasRecentlyCreated) {
    //             $defaultRole = RoleGroup::where('role_name', 'user')->first();
    //             if ($defaultRole) {
    //                 $user->roles()->attach($defaultRole->id);
    //             }
    //         }

    //         auth()->login($user);

    //         $role = $user->roles()->pluck('role_name')->first();

    //         $complaintExists = DB::table('complainants')
    //             ->where('complainant_phone', $mobile)
    //             ->where('is_completed', 1)
    //             ->exists();

    //         $isNewUser = $complaintExists ? false : true;

    //         $isNewUser = empty(Auth::user()->name);

    //         $token = $user->createToken('api-token')->plainTextToken;

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Login successful!',
    //             'user' => [
    //                 'mobile' => $user->mobile,
    //                 'secure_id' => $user->secure_id,
    //                 'role' => $role,
    //             ],
    //             'is_new_user' => $isNewUser,
    //             'token' => $token,
    //         ], 200);

    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong!',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
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

            $isNewUser = empty(Auth::user()->name);

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
