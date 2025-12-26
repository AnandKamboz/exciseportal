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

        // Special district handling
        $districtIds = ($district == 3) ? [3, 12] : [$district];

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY (DETC Dashboard)
        |--------------------------------------------------------------------------
        */
        $baseQuery = Complainant::whereIn('district_id', $districtIds)
            ->where('is_completed', 1);

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD COUNTS (As per Screenshot)
        |--------------------------------------------------------------------------
        */

        // 1. Total Information
        $totalInformation = (clone $baseQuery)->count();

        // 2. Open / New Information
        $newInformation = (clone $baseQuery)
            ->where('current_status', 'pending')
            ->where('is_final', 0)
            ->count();

        // 3. Pending with Applicant
        $pendingWithApplicant = (clone $baseQuery)
            ->where('current_owner', 'applicant')
            ->where('is_final', 0)
            ->count();

        // 4. Under Review
        $underReview = (clone $baseQuery)
            ->where('current_status', 'in_process')
            ->where('is_final', 0)
            ->count();

        // 5. Closed Information
        $closedInformation = (clone $baseQuery)
            ->where('is_final', 1)
            ->count();

        // 6. With DETC
        $withDetc = (clone $baseQuery)
            ->where('current_owner', 'detc')
            ->where('is_final', 0)
            ->count();

        // 7. With ETO
        $withEto = (clone $baseQuery)
            ->where('current_owner', 'eto')
            ->where('is_final', 0)
            ->count();

        // 8. With HQ
        $withHq = (clone $baseQuery)
            ->where('current_owner', 'hq')
            ->where('is_final', 0)
            ->count();


        // No action taken
         $noActionTaken = (clone $baseQuery)
            ->where('is_final', 0)
            ->where(function ($q) {
                $q->whereNull('current_owner')
                ->orWhere('current_owner', '')
                ->orWhereNull('current_level')
                ->orWhere('current_level', '')
                ->orWhereNull('current_status')
                ->orWhere('current_status', 'pending');
        })
        ->count();

        // dd($noActionTaken);

        if ($district == 3) {
            $allComplain = Complainant::whereIn('district_id', [3, 12])
                ->where('is_completed', 1)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $allComplain = Complainant::where('district_id', $district)
                ->where('is_completed', 1)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('detc.dashboard', compact(
            'totalInformation',
            'newInformation',
            'pendingWithApplicant',
            'underReview',
            'closedInformation',
            'withDetc',
            'withEto',
            'withHq',
            'allComplain',
            'noActionTaken',
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

        $additionalChargeDistrict = null;

        if (Auth::user()->district == 3) {
            $additionalChargeDistrict = 12;
        }

        DetcAction::create([
            'secure_id' => $newSecureId,
            'complaint_id' => $complaint->id,
            'user_application_id' => $complaint->application_id,
            'detc_district' => Auth::user()->district,
            'additional_charge_district' => $additionalChargeDistrict,

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

        /*
        |--------------------------------------------------------------------------
        | ⭐ NEW: MASTER STATE UPDATE (DET C SIDE)
        |--------------------------------------------------------------------------
        */

        // DETC → ETO
        if ($request->proposed_action == 'forward_to_eto') {
            $complaint->update([
                'current_owner' => 'ETO',
                'current_level' => 'ETO',
                'current_status' => 'forwarded_to_eto',
                'is_final' => false,
            ]);
        }

        // DETC → HQ
        if ($request->proposed_action == 'uploaded_report' && $buttonAction == 'send_to_hq') {
            $complaint->update([
                'current_owner' => 'HQ',
                'current_level' => 'HQ',
                'current_status' => 'detc_report_uploaded',
                'is_final' => false,
            ]);
        }

        // DETC → Applicant (information incomplete)
        if ($request->reason == 'information_incomplete') {

            Complainant::where('secure_id', $secure_id)->update([
                'detc_rise_issue' => 1,
                'detc_issue' => $request->missing_info,
            ]);

            $complaint->update([
                'current_owner' => 'APPLICANT',
                'current_level' => 'DETC',
                'current_status' => 'information_incomplete',
                'is_final' => false,
            ]);
        }

        // DETC → CLOSED (false_information / any_other)
        if (
            $request->proposed_action == 'non_actionable' &&
            in_array($request->reason, ['false_information', 'any_other'])
        ) {
            $complaint->update([
                'current_owner' => 'CLOSED',
                'current_level' => 'CLOSED',
                'current_status' => 'detc_non_actionable_closed',
                'is_final' => true,
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

        // $query = Complainant::where('is_completed', 1)
        //     ->where('detc_rise_issue', 1)
        //     ->whereNull('missing_info_submitted_at');

        $query = Complainant::where('is_completed', 1)
            ->where(function ($q) {
                $q->where('detc_rise_issue', 1)
                ->orWhere('eto_rise_issue', 1);
            });
            // ->whereNull('missing_info_submitted_at');


        // dd()




        if ($district == 3) {
            $query->whereIn('district_id', [3, 12]);
        } else {
            $query->where('district_id', $district);
        }

        $applications = $query->orderBy('created_at', 'desc')->get();


        // dd($applications);

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

    public function underReview()
    {
        $district = Auth::user()->district;

        $query = Complainant::where('is_completed', 1)
            ->where('detc_rise_issue', 1)
            ->whereNotNull('missing_info_submitted_at');

        if ($district == 3) {
            $query->whereIn('district_id', [3, 12]);
        } else {
            $query->where('district_id', $district);
        }

        // 👉 Sabhi records lao
        $applications = $query
            ->orderBy('missing_info_submitted_at', 'desc')
            ->get();

        return view('detc.under_review', compact('applications'));
    }

    public function closedInformation(){

        $closed = Complainant::where('district_id', Auth::user()->district)
            ->where('current_owner', 'CLOSED')
            ->where('current_level', 'CLOSED')
            ->where('is_final', 1)
            ->get();
        
        return view('detc.closed_application', compact('closed'));

    }

    public function noActionTaken()
    {
        $district = Auth::user()->district;
        $districtIds = ($district == 3) ? [3, 12] : [$district];

        $applications = Complainant::whereIn('district_id', $districtIds)
            ->where('is_completed', 1)
            ->where('is_final', 0)
            ->where(function ($q) {
                $q->whereNull('current_owner')
                ->orWhere('current_owner', '')
                ->orWhereNull('current_level')
                ->orWhere('current_level', '')
                ->orWhereNull('current_status')
                ->orWhere('current_status', 'pending');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.no_action_taken', compact('applications'));
    }

    public function withEto()
    {
        $district = Auth::user()->district;

        $applications = Complainant::where('district_id', $district)
            ->where('current_owner', 'ETO')
            ->where('current_level', 'ETO')
            ->where('is_final', 0)
            ->get();

        return view('detc.with_eto', compact('applications'));
    }

    public function withHq()
    {
        $district = Auth::user()->district;

        $applications = Complainant::where('district_id', $district)
            ->where('current_owner', 'HQ')
            ->where('current_level', 'HQ')
            ->where('is_final', 0)
            ->get();

        return view('detc.with_hq', compact('applications'));
    }

    public function pendingWithDetc()
    {
        $district = Auth::user()->district;
        $districtIds = ($district == 3) ? [3, 12] : [$district];

        $applications = Complainant::whereIn('district_id', $districtIds)
            ->where('is_completed', 1)
            ->where('is_final', 0)
            ->where('current_owner', 'detc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detc.pending_with_detc', compact('applications'));
    }







}
