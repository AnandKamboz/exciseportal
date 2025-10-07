<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\InspectionReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function storeInspectionReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_grading'         => 'required|string',
            'information_grading'    => 'required|string',
            'process_complain'       => 'required|string',
            'remarks'                => 'required|string',
            'proposed_action'        => 'nullable|string',
            'commodities_reported'   => 'nullable|string',
            'estimated_value'        => 'nullable|numeric',
            'place_to_search'        => 'nullable|string',
            'upload_file'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'secure_id'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $report = new InspectionReport();
            $report->source_grading = $request->source_grading;
            $report->information_grading = $request->information_grading;
            $report->process_complain = $request->process_complain;
            $report->remarks = $request->remarks;
            $report->inspector_id = Auth::id();
            $report->complain_secure_id = $request->secure_id;
            $report->secure_id = uniqid('IR_');

            if ($request->process_complain === 'accept') {
                $report->proposed_action = $request->proposed_action;
                $report->commodities_reported = $request->commodities_reported;
                $report->estimated_value = $request->estimated_value;
                $report->place_to_search = $request->place_to_search;

                if ($request->hasFile('upload_file')) {
                    $file = $request->file('upload_file');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('public/inspection_reports', $fileName);
                    $report->upload_file = $fileName;
                }
            }

            $report->save();

            return response()->json([
                'status'         => true,
                'message'        => 'Inspection report submitted successfully.',
                'inspection_id'  => $report->secure_id,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function inspectorFeedback(Request $request)
    {
        try {
            $secure_id = $request->query('secure_id');

            if (!$secure_id) {
                return response()->json([
                    'status'  => false,
                    'message' => 'secure_id is required',
                ], 400);
            }

            $inspectionReport = InspectionReport::where('complain_secure_id', $secure_id)->first();

            if (!$inspectionReport) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Inspection report not found',
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Inspection report fetched successfully',
                'data'    => $inspectionReport,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
