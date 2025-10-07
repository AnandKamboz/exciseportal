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

    public function showComplaint($secure_id)
    {
        try {
            $complain = Complainant::where('secure_id', $secure_id)->first();

            if (!$complain) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Complaint not found',
                ], 404);
            }

            $complainantDistrict = DB::table('districts')
                ->where('id', $complain->complainant_dist_id)
                ->first();

            $againstDistrict = DB::table('districts')
                ->where('id', $complain->against_district_id)
                ->first();

            $complainData = $complain->toArray();
            $complainData['complainant_dist_name'] = $complainantDistrict->name ?? 'Not Found';
            $complainData['against_district_name'] = $againstDistrict->name ?? 'Not Found';

            return response()->json([
                'status'  => true,
                'message' => 'Complaint fetched successfully',
                'data'    => $complainData
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
