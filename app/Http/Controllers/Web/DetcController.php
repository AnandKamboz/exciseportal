<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\DetcAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DetcController extends Controller
{
    public function dashboard()
    {
        $district = Auth::user()->district;
        $allComplain = Complainant::where('district_id', $district)
            ->where('is_completed', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInformation = $allComplain->count();
        $forwardedtoEto = DetcAction::where('detc_district', $district)->where('send_to', 'eto')->count();
        $forwardedtoHq = DetcAction::where('detc_district', $district)->where('send_to', 'hq')->count();
        $pendingFromApplicant = Complainant::where('district_id', $district)->where('is_completed', 1)->where('detc_rise_issue', 1)->whereNull('missing_info_submitted_at')->count();
        $pendingFromDetc = Complainant::where('district_id', $district)->where('is_completed', 1)->where('detc_rise_issue', 1)->whereNull('missing_info_submitted_at')->count();

        return view('detc.dashboard', compact(
            'allComplain',
            'totalInformation',
            'forwardedtoEto',
            'forwardedtoHq',
            'pendingFromApplicant',
            'pendingFromDetc',
        ));
    }


    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
        $applicationId = $complain->application_id;

      
        $detcActions = DetcAction::where('user_application_id', $applicationId)
            ->orderBy('id', 'asc')
            ->get();

        $actionCount = $detcActions->count();            
        $lastAction = $detcActions->last();              

     
        $showForm = false;

        if ($actionCount == 0) {
            $showForm = true;

        } elseif ($actionCount == 1) {
            if ($lastAction && $lastAction->reason == 'information_incomplete') {
                if ($lastAction->applicant_submitted_at !== null) {
                    $showForm = true;
                }
            }

        } elseif ($actionCount >= 2) {
            $showForm = false;
        }

        $complainantDistrictName = optional(
            DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
        )->name ?? 'Not Found';

        $againstDistrictId = optional(
            DB::table('districts')->where('id', $complain->against_district_id)->first()
        )->name ?? 'Not Found';

        $wards = DB::table('district_wards')
            ->where('district_id', auth()->user()->district)
            ->first();

        $wardCount = $wards->ward_count ?? 0;

        $wardList = [];
        for ($i = 1; $i <= $wardCount; $i++) {
            $wardList[$i] = "Ward No $i";
        }

        return view('detc.show', compact(
            'complain',
            'detcActions',
            'lastAction',
            'wardList',
            'complainantDistrictName',
            'againstDistrictId',
            'showForm'
        ));
    }

    public function updateComplaintStatus(Request $request, $secure_id)
    {

        $request->validate([
            'status' => 'required|string|in:forward_to_inspector,rejected',
            'remarks' => 'required|string|max:1000',
            'upload_file' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
        ]);

        $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        $complaint->update([
            'detc_status' => $request->status,
            'detc_remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Complaint status updated successfully!');
    }

   

    public function store(Request $request, $secure_id)
    {
        $request->validate([
            'proposed_action' => 'required',
        ]);

        if ($request->proposed_action == 'forward_to_eto') {

            $request->validate([
                'ward_no' => 'required',
                'remarks_forward' => 'required',

                'upload_file' => 'prohibited',
                'remarks_upload' => 'prohibited',
                'reason' => 'prohibited',
                'remarks_non' => 'prohibited',
                'missing_info' => 'prohibited',
            ]);

            $finalRemarks = $request->remarks_forward;
        }

        if ($request->proposed_action == 'uploaded_report') {

            $request->validate([
                'upload_file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'remarks_upload' => 'required',

                'ward_no' => 'prohibited',
                'remarks_forward' => 'prohibited',
                'reason' => 'prohibited',
                'remarks_non' => 'prohibited',
                'missing_info' => 'prohibited',
            ]);

            $finalRemarks = $request->remarks_upload;
        }

        if ($request->proposed_action == 'non_actionable') {

            $request->validate([
                'reason' => 'required',
                'remarks_non' => 'required',

                'ward_no' => 'prohibited',
                'remarks_forward' => 'prohibited',
                'upload_file' => 'prohibited',
                'remarks_upload' => 'prohibited',
            ]);

            if ($request->reason == 'information_incomplete') {
                $request->validate([
                    'missing_info' => 'required',
                ]);

            } else {
                $request->validate([
                    'missing_info' => 'prohibited',
                ]);
            }

            $finalRemarks = $request->remarks_non;
        }

        $userComplaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        do {
            $newSecureId = Str::random(32);
        } while (DetcAction::where('secure_id', $newSecureId)->exists());

        $fileName = null;

        if ($request->hasFile('upload_file')) {

            $file = $request->file('upload_file');

            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = 'complaints/'.$complaint->application_id.'/';

            $file->storeAs($path, $fileName, 'public');
        }

        $buttonAction = $request->btn;

        $action = $request->proposed_action;

        if ($action == 'forward_to_eto') {
            $finalRemarks = $request->remarks_forward;
        } elseif ($action == 'uploaded_report') {
            $finalRemarks = $request->remarks_upload;
        } elseif ($action == 'non_actionable') {
            $finalRemarks = $request->remarks_non;
        } else {
            $finalRemarks = null;
        }

        $sendTo = null;

        if ($request->proposed_action == 'forward_to_eto') {
            $sendTo = 'eto';
        } elseif ($request->proposed_action == 'uploaded_report' && $buttonAction == 'send_to_hq') {
            $sendTo = 'hq';
        } elseif ($request->proposed_action == 'non_actionable' && $buttonAction == 'send_back_to_applicant') {
            $sendTo = 'applicant';
        } else {
            $sendTo = 'none';
        }

        DetcAction::create([
            'secure_id' => $newSecureId,
            'complaint_id' => $complaint->id,
            'user_application_id' => $complaint->application_id,
            'detc_district' => Auth::user()->district,
            'proposed_action' => $request->proposed_action,
            'ward_no' => $request->ward_no,

          
            'reason' => $request->reason,
            'missing_info' => $request->missing_info,

            'remarks' => $finalRemarks,

            'file_name' => $fileName,

            'button_action' => $buttonAction,

            'send_to' => $sendTo,

            'is_approved' => in_array($buttonAction, ['submit', 'send_to_hq']) ? 1 : 0,

            'detc_user_id' => auth()->id(),
        ]);

        if ($request->reason == 'information_incomplete') {
            Complainant::where('secure_id', $secure_id)->update([
                'detc_rise_issue' => 1,
                'detc_issue' => $request->missing_info,
            ]);
        }

        if ($buttonAction == 'send_back_to_applicant') {
            return back()->with('success', 'Application sent back to applicant');
        }

        return back()->with('success', 'Action saved successfully');
    }

    public function allApplications()
    {
        $district = Auth::user()->district;

        $applications = Complainant::where('district_id', $district)
            ->where('is_completed', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.all_applications', compact('applications'));
    }

    public function forwardedToHqApplications()
    {
        $district = Auth::user()->district;

        $applications = Complainant::where('district_id', $district)
            ->where('is_completed', 1)
            ->whereIn('application_id', function ($query) use ($district) {
                $query->select('user_application_id')
                    ->from('detc_actions')
                    ->where('detc_district', $district)
                    ->where('send_to', 'hq');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.forwarded_hq_applications', compact('applications'));
    }

    public function forwardedToEto()
    {
        $district = Auth::user()->district;

        $forwardedIds = DetcAction::where('detc_district', $district)
            ->where('send_to', 'eto')
            ->pluck('user_application_id');

        $applications = Complainant::whereIn('application_id', $forwardedIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.forwarded_to_eto', compact('applications'));
    }

    public function pendingFromApplicant()
    {
        $district = Auth::user()->district;

        $applications = Complainant::where('district_id', $district)
            ->where('is_completed', 1)
            ->where('detc_rise_issue', 1)
            ->whereNull('missing_info_submitted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.pending_from_applicant', compact('applications'));
    }

    public function pendingFromDetc()
    {
        $district = Auth::user()->district;

        $applications = Complainant::where('district_id', $district)
            ->whereNotNull('missing_info_submitted_at') 
            ->where('detc_rise_issue', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('detc.pending_detc', compact('applications'));
    }

    public function viewApplication($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();

        return view('detc.show', compact('complain'));
    }
}
