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

    // public function show($secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
    //     $applicationId = $complain->application_id;

    //     // Get all actions
    //     $detcActions = DetcAction::where('user_application_id', $applicationId)
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     // Check if any action exists
    //     $actionExists = $detcActions->count() > 0;

    //     // Last action for logic
    //     $lastAction = $detcActions->last();

    //     // Fetch district names
    //     $complainantDistrictName = optional(
    //         DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
    //     )->name ?? 'Not Found';

    //     $againstDistrictId = optional(
    //         DB::table('districts')->where('id', $complain->against_district_id)->first()
    //     )->name ?? 'Not Found';

    //     // Wards
    //     $wards = DB::table('district_wards')
    //         ->where('district_id', auth()->user()->district)
    //         ->first();

    //     $wardCount = $wards->ward_count ?? 0;

    //     $wardList = [];
    //     for ($i = 1; $i <= $wardCount; $i++) {
    //         $wardList[$i] = 'Ward No '.$i;
    //     }
    //     $nonActionableTaken = DetcAction::where('user_application_id', $applicationId)
    //         ->where('proposed_action', 'non_actionable')
    //         ->exists();

    //     return view('detc.show', compact(
    //         'complain',
    //         'detcActions',
    //         'lastAction',
    //         'actionExists',
    //         'wardList',
    //         'complainantDistrictName',
    //         'againstDistrictId',
    //         'nonActionableTaken',
    //     ));
    // }

    // public function show($secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
    //     $applicationId = $complain->application_id;

    //     // All DETC actions
    //     $detcActions = DetcAction::where('user_application_id', $applicationId)
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     $actionExists = $detcActions->count() > 0;
    //     $lastAction = $detcActions->last();

    //     // District names
    //     $complainantDistrictName = optional(
    //         DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
    //     )->name ?? 'Not Found';

    //     $againstDistrictId = optional(
    //         DB::table('districts')->where('id', $complain->against_district_id)->first()
    //     )->name ?? 'Not Found';

    //     // Ward list
    //     $wards = DB::table('district_wards')
    //         ->where('district_id', auth()->user()->district)
    //         ->first();

    //     $wardCount = $wards->ward_count ?? 0;
    //     $wardList = [];
    //     for ($i = 1; $i <= $wardCount; $i++) {
    //         $wardList[$i] = 'Ward No '.$i;
    //     }

    //     // Check if Non-actionable already taken
    //     $nonActionableTaken = DetcAction::where('user_application_id', $applicationId)
    //         ->where('proposed_action', 'non_actionable')
    //         ->exists();

    //     // ------------------------------------
    //     //  🔥 New Condition: Form should appear only:
    //     //    1️⃣ If NO action taken at all
    //     //    2️⃣ If last action = information_incomplete AND applicant has submitted back
    //     // ------------------------------------

    //     $showForm = false;

    //     if (! $actionExists) {
    //         // First time show form
    //         $showForm = true;
    //     } else {

    //         // If last action was send back to applicant
    //         if ($lastAction->reason == 'information_incomplete'
    //             && $lastAction->send_to == 'applicant'
    //             && $lastAction->applicant_submitted_at != null   // applicant submitted
    //         ) {
    //             $showForm = true;
    //         } else {
    //             $showForm = false; // default block opening
    //         }
    //     }

    //     // Applicant has already corrected 1 time — now form must never show
    //     if ($detcActions->count() >= 2) {
    //         $showForm = false;
    //     }

    //     return view('detc.show', compact(
    //         'complain',
    //         'detcActions',
    //         'lastAction',
    //         'actionExists',
    //         'wardList',
    //         'complainantDistrictName',
    //         'againstDistrictId',
    //         'nonActionableTaken',
    //         'showForm'
    //     ));
    // }

    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
        $applicationId = $complain->application_id;

        // All actions
        $detcActions = DetcAction::where('user_application_id', $applicationId)
            ->orderBy('id', 'asc')
            ->get();

        $actionCount = $detcActions->count();             // Number of times DETC took action
        $lastAction = $detcActions->last();               // Last/Latest action

        // ------------------------------
        // FORM SHOWING LOGIC
        // ------------------------------
        $showForm = false;

        if ($actionCount == 0) {
            // First time → always show form
            $showForm = true;

        } elseif ($actionCount == 1) {
            // Only one record exists → Check if incomplete
            if ($lastAction && $lastAction->reason == 'information_incomplete') {
                // Applicant has submitted data? If not → DETC cannot take action yet
                if ($lastAction->applicant_submitted_at !== null) {
                    $showForm = true; // DETC can now take 2nd & last final action
                }
            }

        } elseif ($actionCount >= 2) {
            // 2 बार Action हो चुका → अब form कभी नहीं दिखेगा
            $showForm = false;
        }

        // Fetch district names
        $complainantDistrictName = optional(
            DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
        )->name ?? 'Not Found';

        $againstDistrictId = optional(
            DB::table('districts')->where('id', $complain->against_district_id)->first()
        )->name ?? 'Not Found';

        // Ward List
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
        $request->validate([
            'proposed_action' => 'required',
        ]);

        if ($request->proposed_action == 'forward_to_eto') {
            $request->validate([
                'ward_no' => 'required',
                'remarks' => 'required',
            ]);
        }

        if ($request->proposed_action == 'uploaded_report') {
            $request->validate([
                'upload_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'remarks' => 'required',
            ]);
        }

        if ($request->proposed_action == 'non_actionable') {
            $request->validate([
                'reason' => 'required',
                'remarks' => 'required',
            ]);

            if ($request->reason == 'information_incomplete') {
                $request->validate([
                    'missing_info' => 'required',
                ]);
            }

            if ($request->reason == 'other') {
                $request->validate([
                    'missing_info' => 'required',
                ]);
            }
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
            Complainant::where('secure_id', $secure_id)->update([
                'detc_rise_issue' => 1,
                'detc_issue' => $request->missing_info,
            ]);
        }
        // dd($buttonAction); send_back_to_applicant

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

        // DetcAction table se woh records jaha send_to = "eto"
        $forwardedIds = DetcAction::where('detc_district', $district)
            ->where('send_to', 'eto')
            ->pluck('user_application_id');

        // Ab complaint table me actual applications fetch karenge
        $applications = Complainant::whereIn('application_id', $forwardedIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.forwarded_to_eto', compact('applications'));
    }

    public function pendingFromApplicant()
    {
        $district = Auth::user()->district;

        // जिन applications में missing info submit नहीं हुई है
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
            ->whereNotNull('missing_info_submitted_at')  // user ने submit कर दी
            ->where('detc_rise_issue', 1)                // DETC ने issue raise किया हुआ था
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
