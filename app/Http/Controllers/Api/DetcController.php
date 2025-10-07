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

    public function getComplaintDetails($secure_id)
    {
        try {
            // Fetch complaint by secure_id
            $complain = Complainant::where('secure_id', $secure_id)->first();

            if (!$complain) {
                return response()->json([
                    'status' => false,
                    'message' => 'Complaint not found'
                ], 404);
            }

            // Fetch complainant district name
            $complainantDistrict = DB::table('districts')
                ->select('id', 'name')
                ->where('id', $complain->complainant_dist_id)
                ->first();

            // Fetch against district name
            $againstDistrict = DB::table('districts')
                ->select('id', 'name')
                ->where('id', $complain->against_district_id)
                ->first();

            // Prepare response
            return response()->json([
                'status' => true,
                'message' => 'Complaint details fetched successfully',
                'data' => [
                    'complaint' => $complain,
                    'complainant_district' => $complainantDistrict ?? ['id' => null, 'name' => 'Not Found'],
                    'against_district' => $againstDistrict ?? ['id' => null, 'name' => 'Not Found'],
                ],
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
