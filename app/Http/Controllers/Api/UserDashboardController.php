<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use Illuminate\Http\Request;
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

    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->first();

        if (! $complain) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'complaint' => $complain,
            ],
        ], 200);
    }
}
