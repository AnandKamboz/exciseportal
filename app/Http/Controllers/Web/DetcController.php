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
        $allComplain = Complainant::where('district_id', Auth::user()->district)->where('is_completed', 1)->orderBy('created_at', 'desc')->get();
        $totalInformation = Complainant::where('district_id', Auth::user()->district)->where('is_completed', 1)->count();
        $forwardedForAction = DB::table('detc_actions')->where('detc_district', Auth::user()->district)->where('is_approved', 1)->count();
        $notActionable = DB::table('detc_actions')->where('detc_district', Auth::user()->district)->where('is_approved', 0)->count();
        $pendingInformation = $totalInformation - ($forwardedForAction + $notActionable);

        return view('detc.dashboard', compact('forwardedForAction', 'allComplain', 'pendingInformation', 'totalInformation', 'notActionable'));
    }

    // public function show($secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
    //     $applicationId = $complain->application_id;

    //     // detc_actions table me check karo
    //     $detcAction = DetcAction::where('user_application_id', $applicationId)->get();

    //     // boolean check
    //     $actionExists = $detcAction ? true : false;

    //     $complainantDistrictName = optional(
    //         DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
    //     )->name ?? 'Not Found';

    //     $againstDistrictId = optional(
    //         DB::table('districts')->where('id', $complain->against_district_id)->first()
    //     )->name ?? 'Not Found';

    //     $wards = DB::table('district_wards')
    //         ->where('district_id', auth()->user()->district)
    //         ->first();

    //     $wardCount = $wards->ward_count ?? 0;

    //     $wardList = [];

    //     for ($i = 1; $i <= $wardCount; $i++) {
    //         $wardList[$i] = 'Ward No '.$i;
    //     }

    //     $complaintIdCount = Complainant::where('id', $complain->id)->count();
    //     $actionExists = $detcActions->count() > 0;

    //     return view('detc.show', compact('actionExists','complaintIdCount','complain', 'complainantDistrictName', 'againstDistrictId', 'actionExists', 'detcAction', 'wardList'));
    // }

    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
        $applicationId = $complain->application_id;

        // Get all actions
        $detcActions = DetcAction::where('user_application_id', $applicationId)
            ->orderBy('id', 'asc')
            ->get();

        // Check if any action exists
        $actionExists = $detcActions->count() > 0;

        // Last action for logic
        $lastAction = $detcActions->last();

        // Fetch district names
        $complainantDistrictName = optional(
            DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
        )->name ?? 'Not Found';

        $againstDistrictId = optional(
            DB::table('districts')->where('id', $complain->against_district_id)->first()
        )->name ?? 'Not Found';

        // Wards
        $wards = DB::table('district_wards')
            ->where('district_id', auth()->user()->district)
            ->first();

        $wardCount = $wards->ward_count ?? 0;

        $wardList = [];
        for ($i = 1; $i <= $wardCount; $i++) {
            $wardList[$i] = 'Ward No '.$i;
        }
        $nonActionableTaken = DetcAction::where('user_application_id', $applicationId)
            ->where('proposed_action', 'non_actionable')
            ->exists();

        return view('detc.show', compact(
            'complain',
            'detcActions',
            'lastAction',
            'actionExists',
            'wardList',
            'complainantDistrictName',
            'againstDistrictId',
            'nonActionableTaken',
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

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'proposed_action' => 'required',
    //         'action_taken'    => 'required_if:proposed_action,actionable',
    //         'reason'          => 'required_if:proposed_action,non_actionable',
    //         'remarks'         => 'required',
    //     ]);

    //     DetcAction::create([
    //         'proposed_action'   => $request->proposed_action,
    //         'action_taken'      => $request->action_taken,
    //         'reason'            => $request->reason,
    //         'remarks'           => $request->remarks,
    //         'detc_user_id'      => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Action saved successfully');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     $request->validate([
    //         'proposed_action' => 'required',
    //         'action_taken' => 'required_if:proposed_action,actionable',
    //         'reason' => 'required_if:proposed_action,non_actionable',
    //         'remarks' => 'required',
    //     ]);

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     do {
    //         $newActionSecureId = Str::random(32);
    //     } while (DetcAction::where('secure_id', $newActionSecureId)->exists());

    //     DetcAction::create([
    //         'secure_id' => $newActionSecureId,
    //         'complaint_id' => $complaint->id,
    //         'proposed_action' => $request->proposed_action,
    //         'action_taken' => $request->action_taken,
    //         'reason' => $request->reason,
    //         'remarks' => $request->remarks,
    //         'detc_user_id' => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Action saved successfully');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     $request->validate([
    //         'proposed_action' => 'required',
    //         'action_taken' => 'required_if:proposed_action,actionable',
    //         'reason' => 'required_if:proposed_action,non_actionable',
    //         'remarks' => 'required',
    //     ]);

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     if (DetcAction::where('complaint_id', $complaint->id)->exists()) {
    //         return back()->with('error', 'Action already taken for this complaint');
    //     }

    //     do {
    //         $newActionSecureId = Str::random(32);
    //     } while (DetcAction::where('secure_id', $newActionSecureId)->exists());

    //     DetcAction::create([
    //         'secure_id' => $newActionSecureId,
    //         'complaint_id' => $complaint->id,
    //         'proposed_action' => $request->proposed_action,
    //         'action_taken' => $request->action_taken,
    //         'reason' => $request->reason,
    //         'remarks' => $request->remarks,
    //         'detc_user_id' => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Action saved successfully');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     $request->validate([
    //         'proposed_action' => 'required',
    //         'action_taken' => 'required_if:proposed_action,actionable',
    //         'reason' => 'required_if:proposed_action,non_actionable',
    //         'remarks' => 'required',
    //         'upload_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',

    //     ]);

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     if (DetcAction::where('complaint_id', $complaint->id)->exists()) {
    //         return back()->with('error', 'Action already taken for this complaint');
    //     }

    //     do {
    //         $newActionSecureId = Str::random(32);
    //     } while (DetcAction::where('secure_id', $newActionSecureId)->exists());

    //     DetcAction::create([
    //         'secure_id' => $newActionSecureId,
    //         'complaint_id' => $complaint->id,
    //         'user_application_id' => $complaint->application_id,
    //         'detc_district' => Auth::user()->district,
    //         'proposed_action' => $request->proposed_action,
    //         'action_taken' => $request->action_taken,
    //         'reason' => $request->reason,
    //         'remarks' => $request->remarks,
    //         'detc_user_id' => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Action saved successfully');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     dd($request->toArray());
    //     $request->validate([
    //         'proposed_action' => 'required',
    //         'reason' => 'required_if:proposed_action,non_actionable',
    //         'upload_file' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
    //     ]);

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     if (DetcAction::where('complaint_id', $complaint->id)->exists()) {
    //         return back()->with('error', 'Action already taken for this complaint');
    //     }

    //     do {
    //         $newActionSecureId = Str::random(32);
    //     } while (DetcAction::where('secure_id', $newActionSecureId)->exists());

    //     $fileName = null;

    //     if ($request->hasFile('upload_file')) {

    //         $file = $request->file('upload_file');

    //         $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

    //         $path = 'complaints/'.$complaint->application_id.'/';

    //         $file->storeAs($path, $fileName, 'public');
    //     }

    //     DetcAction::create([
    //         'secure_id' => $newActionSecureId,
    //         'complaint_id' => $complaint->id,
    //         'user_application_id' => $complaint->application_id,
    //         'detc_district' => Auth::user()->district,
    //         'proposed_action' => $request->proposed_action,
    //         'is_approved'     => $request->proposed_action === 'actionable' ? 1 : 0,
    //         'ward_no' => $request->ward_no,
    //         'reason' => $request->reason,
    //         'remarks' => $request->remarks ?? null,
    //         'detc_user_id' => auth()->id(),
    //         'file_name' => $fileName,
    //     ]);

    //     return back()->with('success', 'Action saved successfully');
    // }

    public function store(Request $request, $secure_id)
    {
        // dd($request->toArray());
        // dd($secure_id);
        // $request->validate([
        //     'proposed_action' => 'required',
        //     'ward_no' => 'required_if:proposed_action,forward_to_eto',
        //     'upload_file' => 'required_if:proposed_action,uploaded_report|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //     'reason' => 'required_if:proposed_action,non_actionable',
        //     'missing_info' => 'required_if:reason,information_incomplete',
        //     'remarks' => 'required',
        // ], [

        //     // CUSTOM MESSAGES
        //     'proposed_action.required' => 'Please select Proposed Action',

        //     // ETO
        //     'ward_no.required_if' => 'Ward number is required for Forward to ETO',

        //     // Upload case
        //     'upload_file.required_if' => 'Please upload a file for Uploaded Report',
        //     'upload_file.mimes' => 'File must be JPG, JPEG, PNG, or PDF',
        //     'upload_file.max' => 'File size must be under 2MB',

        //     // Non-actionable
        //     'reason.required_if' => 'Please select a reason',

        //     // Information incomplete
        //     'missing_info.required_if' => 'Please select what information is missing',

        //     // Remarks
        //     'remarks.required' => 'Remarks field is required',
        // ]);

        // dd('ww');

        $userComplaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        // $alreadySubmitted = !is_null($userComplaint->missing_gst_number) || !is_null($userComplaint->missing_firm_location) || !is_null($userComplaint->missing_address);

        // if ($alreadySubmitted) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Missing information has already been submitted. You cannot submit it again.',
        //     ], 400);
        // }

        $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        // Prevent duplicate entries
        // if (DetcAction::where('complaint_id', $complaint->id)->exists()) {
        //     return back()->with('error', 'Action already taken for this complaint');
        // }

        // ========================
        // GENERATE UNIQUE SECURE ID
        // ========================
        do {
            $newSecureId = Str::random(32);
        } while (DetcAction::where('secure_id', $newSecureId)->exists());

        // ========================
        // FILE UPLOAD
        // ========================
        $fileName = null;

        if ($request->hasFile('upload_file')) {

            $file = $request->file('upload_file');

            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = 'complaints/'.$complaint->application_id.'/';

            $file->storeAs($path, $fileName, 'public');
        }

        // ========================
        // BUTTON ACTION
        // ========================
        $buttonAction = $request->btn;
        // btn values:
        // submit, reject, send_to_hq, send_back_to_applicant

        // ========================
        // STORE DATA IN DATABASE
        // ========================

        // SENT TO (HQ / ETO / NONE)
        $sendTo = null;

        if ($request->proposed_action == 'forward_to_eto') {
            $sendTo = 'eto';
        } elseif ($request->proposed_action == 'uploaded_report' && $buttonAction == 'send_to_hq') {
            $sendTo = 'hq';
        } else {
            $sendTo = 'none';
        }

        DetcAction::create([
            'secure_id' => $newSecureId,
            'complaint_id' => $complaint->id,
            'user_application_id' => $complaint->application_id,
            'detc_district' => Auth::user()->district,

            'proposed_action' => $request->proposed_action,

            // Forward to ETO
            'ward_no' => $request->ward_no,

            // Non Actionable
            'reason' => $request->reason,
            'missing_info' => $request->missing_info,

            'remarks' => $request->remarks,

            // Save uploaded file
            'file_name' => $fileName,

            // Action type (submit/reject/send_to_hq/send_back_to_applicant)
            'button_action' => $buttonAction,

            'send_to' => $sendTo,

            // Auto approval logic
            'is_approved' => in_array($buttonAction, ['submit', 'send_to_hq']) ? 1 : 0,

            'detc_user_id' => auth()->id(),
        ]);

        if ($request->reason == 'information_incomplete') {
            // Update missing info fields in complainants table
            Complainant::where('secure_id', $secure_id)->update([
                'detc_rise_issue' => 1,
                'detc_issue' => $request->missing_info,
            ]);
        }

        return back()->with('success', 'Action saved successfully');
    }
}
