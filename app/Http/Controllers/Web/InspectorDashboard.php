<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\InspectionReport;


class InspectorDashboard extends Controller
{
    public function dashboard()
    {
        $allComplain = Complainant::where('is_completed',1)->orderBy('created_at', 'desc')->get();
        return view('inspector.dashboard',compact('allComplain'));
    }

    public function show($secure_id)
    {
        
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
        $complainantDistrictName = optional(
            DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
        )->name ?? 'Not Found';

        $againstDistrictId = optional(
            DB::table('districts')->where('id', $complain->against_district_id)->first()
        )->name ?? 'Not Found';
        return view('inspector.show', compact('complain','complainantDistrictName','againstDistrictId'));
    }

    public function inspectorFedback(Request $request)
    {
        $secure_id = $request->query('secure_id');
        $inspectionReport = InspectionReport::where('complain_secure_id', $secure_id)->first();
        return view('inspector.feedback',compact('inspectionReport'));
    }

    public function storeAction(Request $request)
    {
         
        $request->validate([
            'source_grading' => 'required|string',
            'information_grading' => 'required|string',
            'process_complain' => 'required|string',
            'remarks' => 'required|string',
            'proposed_action' => 'nullable|string',
            'commodities_reported' => 'nullable|string',
            'estimated_value' => 'nullable|numeric',
            'place_to_search' => 'nullable|string',
            'upload_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'secure_id' => 'required',
        ]);
       

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
                $file->storeAs('public/inspection_reports', $fileName);
                $report->upload_file = $fileName;
            }
        }

        $report->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Inspection report submitted successfully.',
            'inspection_id' => $request->secure_id,
        ]);

        //  dd('aaa');
        // return back()->with('success', 'Inspection report submitted successfully.');

        // return redirect()->route('inspector.dashboard')->with('success', 'Inspection report submitted successfully.');

    }
}
