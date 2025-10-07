<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function userDashboard(Request $request)
    {
        $allComplain = Complainant::where('complainant_phone', $request->user()->mobile)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'All complaints fetched successfully',
            'data' => $allComplain
        ], 200);
    }
}
