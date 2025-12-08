<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function userDashboard()
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $allComplain = Complainant::where('complainant_phone', Auth::user()->mobile)
            ->where('is_completed', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $allComplain->count(),
            'data' => $allComplain,
        ], 200);
    }

    // public function show($secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->first();

    //     if (! $complain) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Complaint not found',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'complaint' => $complain,
    //         ],
    //     ], 200);
    // }

    public function show($secure_id)
    {
        if (! auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access!',
            ], 401);
        }
        
        $complain = Complainant::where('secure_id', $secure_id)->first();

        if (! $complain) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found',
            ], 404);
        }

        // ============================
        //  GET STATE NAME
        // ============================
        $user_state = null;

        if (! empty($complain->complainant_state)) {
            $user_state = DB::table('states')
                ->where('id', $complain->complainant_state)
                ->value('name');
        }

        // ============================
        //  GET DISTRICT NAME
        // ============================
        $user_dist = null;

        if (! empty($complain->complainant_district)) {
            $user_dist = DB::table('india_districts')
                ->where('id', $complain->complainant_district)
                ->value('name'); // ← your district name column
        }

        // ============================
        //  SEND JSON RESPONSE
        // ============================
        return response()->json([
            'success' => true,
            'data' => [
                'complaint' => $complain,
                'user_state' => $user_state,
                'user_dist' => $user_dist,
            ],
        ], 200);
    }
}
