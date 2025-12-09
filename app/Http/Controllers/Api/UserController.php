<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = Auth()->user();

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'name'    => 'required|string|maxLength:255',
    //         'mobile'  => 'required|digits:10|unique:users,mobile,' . $request->user_id,
    //     ]);

        

    //     $user = User::find($request->user_id);

    //     $user->name = $request->name;
    //     $user->mobile = $request->mobile;
    //     $user->save();

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Profile updated successfully',
    //         'data'    => [
    //             'name'   => $user->name,
    //             'mobile' => $user->mobile,
    //         ]
    //     ]);
    // }

    public function update(Request $request)
    {
        // Logged-in user
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated user'
            ], 401);
        }

        // Validation (unique mobile but ignore same user)
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
        ]);

        // Update user
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
            'data'    => [
                'secure_id' => $user->secure_id,
                'name'      => $user->name,
                'mobile'    => $user->mobile,
            ]
        ]);
    }
    
}
