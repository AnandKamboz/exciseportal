<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetcController extends Controller
{
    public function getDashboardData(Request $request)
    {
        try {
            $user = Auth::user();

            $complaints = Complainant::where('against_district_id', $user->district_id)
                ->where('is_completed', 1)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($complaints->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'No complaints found for this district',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'All completed complaints fetched successfully',
                'data' => $complaints,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
