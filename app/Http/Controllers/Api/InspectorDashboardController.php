<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\InspectionReport;

class InspectorDashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $allComplain = Complainant::where('is_completed', 1)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($allComplain->isEmpty()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'No completed complaints found',
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Completed complaints fetched successfully',
                'data'    => $allComplain
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
