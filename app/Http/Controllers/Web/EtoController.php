<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\DetcAction;
use App\Models\EtoAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EtoController extends Controller
{
    public function dashboard()
    {
        $informations = Complainant::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('detc_actions')
                ->whereColumn('complainants.id', 'detc_actions.complaint_id')
                ->where('ward_no', 1)
                ->where('detc_district', 11);
        })->get();

        $action = DetcAction::where('ward_no', auth()->user()->ward_no)->first();

        return view('eto.dashboard', compact('informations'));
    }

    // public function show($secure_id)
    // {
    //     $etoAction = EtoAction::where('secure_id', $secure_id)->latest()->first() ?? '';
    //     $complain = Complainant::where('secure_id', $secure_id)->first();
    //     $etoActionCount = EtoAction::where('application_id', $complain->application_id)->count();

    //     return view('eto.show', compact('complain', 'etoAction','etoActionCount'));
    // }

    // public function show($secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     // All ETO actions for this application
    //     $etoActions = EtoAction::where('application_id', $complain->application_id)
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     // Count only information_incomplete cases
    //     $infoIncompleteCount = EtoAction::where('application_id', $complain->application_id)
    //         ->where('action', 'non_actionable')
    //         ->where('reason', 'information_incomplete')
    //         ->count();

    //     return view('eto.show', compact(
    //         'complain',
    //         'etoActions',
    //         'infoIncompleteCount'
    //     ));
    // }

    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();

        $etoActions = EtoAction::where('application_id', $complain->application_id)
            ->orderBy('id', 'asc')
            ->get();

        $infoIncompleteCount = EtoAction::where('application_id', $complain->application_id)
            ->where('action', 'non_actionable')
            ->where('reason', 'information_incomplete')
            ->count();

        $etoAction = $etoActions->last();

        return view('eto.show', compact(
            'complain',
            'etoActions',
            'etoAction',
            'infoIncompleteCount'
        ));
    }

    // public function store(Request $request, $secure_id)
    // {
    //     $user = Auth::user();
    //     if (! $user) {
    //         return back()->with('error', 'Unauthenticated user');
    //     }

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     $request->validate([
    //         'proposed_action' => 'required|in:uploaded_report,non_actionable',
    //     ]);

    //     if ($request->proposed_action === 'uploaded_report') {

    //         $request->validate([
    //             'upload_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //             'remarks_upload' => 'required|string|max:500',
    //         ]);

    //         $fileName = null;

    //         if ($request->hasFile('upload_file')) {
    //             $file = $request->file('upload_file');
    //             $fileName = 'eto_'.now()->format('YmdHis').'_'.Str::random(8)
    //                       .'.'.$file->getClientOriginalExtension();

    //             Storage::disk('public')->putFileAs(
    //                 "eto_reports/{$complaint->application_id}",
    //                 $file,
    //                 $fileName
    //             );
    //         }

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',
    //             'action' => 'uploaded_report',
    //             'report_file' => $fileName,
    //             'remarks' => $request->remarks_upload,
    //             'status' => 'sent_to_hq',
    //         ]);

    //         return back()->with('success', 'Report uploaded successfully');
    //     }

    //     if ($request->proposed_action === 'non_actionable') {

    //         $request->validate([
    //             'reason' => 'required|in:false_information,information_incomplete,any_other',
    //             'remarks_non' => 'required|string|max:500',
    //             'missing_info' => 'nullable|string',
    //         ]);

    //         $infoIncompleteCount = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'non_actionable')
    //             ->where('reason', 'information_incomplete')
    //             ->count();

    //         if (
    //             $request->reason === 'information_incomplete' &&
    //             ! empty($request->missing_info)
    //         ) {

    //             if ($infoIncompleteCount >= 2) {
    //                 return back()->with('error', 'Maximum attempts reached.');
    //             }

    //             EtoAction::create([
    //                 'application_id' => $complaint->application_id,
    //                 'secure_id' => $secure_id,
    //                 'action_by' => $user->id,
    //                 'role' => 'ETO',
    //                 'action' => 'non_actionable',
    //                 'reason' => 'information_incomplete',
    //                 'missing_info' => $request->missing_info,
    //                 'remarks' => $request->remarks_non,
    //                 'status' => $infoIncompleteCount == 1 ? 'closed' : 'pending',
    //             ]);

    //             Complainant::where('secure_id', $secure_id)->update([
    //                 'eto_rise_issue' => 1,
    //                 'eto_issue' => $request->missing_info,
    //                 'eto_missing_info_submitted_at' => now(),
    //             ]);

    //             return back()->with('success', 'Missing information saved.');
    //         }

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',
    //             'action' => 'non_actionable',
    //             'reason' => $request->reason,
    //             'missing_info' => $request->missing_info,
    //             'remarks' => $request->remarks_non,
    //             'status' => 'closed',
    //         ]);

    //         return back()->with('success', 'Action submitted successfully');
    //     }

    //     return back()->with('error', 'Invalid action');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     $user = Auth::user();
    //     if (! $user) {
    //         return back()->with('error', 'Unauthenticated user');
    //     }

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();
    //     $request->validate([
    //         'proposed_action' => 'required|in:uploaded_report,non_actionable',
    //     ]);

    //     if ($request->proposed_action === 'uploaded_report') {
    //         $alreadyUploaded = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'uploaded_report')
    //             ->exists();

    //         if ($alreadyUploaded) {
    //             return back()->with('error', 'Report already uploaded.');
    //         }

    //         $request->validate([
    //             'upload_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //             'remarks_upload' => 'required|string|max:500',
    //         ]);

    //         $file = $request->file('upload_file');
    //         $fileName = 'eto_'.now()->format('YmdHis').'_'.Str::random(8)
    //                   .'.'.$file->getClientOriginalExtension();

    //         Storage::disk('public')->putFileAs(
    //             "eto_reports/{$complaint->application_id}",
    //             $file,
    //             $fileName
    //         );

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',
    //             'action' => 'uploaded_report',
    //             'report_file' => $fileName,
    //             'remarks' => $request->remarks_upload,
    //             'status' => 'sent_to_hq',
    //             'eto_ward' => Auth::user()->ward_no,
    //             'eto_district' => Auth::user()->district,
    //         ]);

    //         return back()->with('success', 'Report uploaded successfully');
    //     }

    //     if ($request->proposed_action === 'non_actionable') {
    //         if ($complaint->eto_rise_issue == 1) {
    //             return back()->with('error', 'Action already taken. Form is locked.');
    //         }

    //         $request->validate([
    //             'reason' => 'required|in:false_information,information_incomplete,any_other',
    //             'remarks_non' => 'required|string|max:500',
    //         ]);

    //         $infoIncompleteCount = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'non_actionable')
    //             ->where('reason', 'information_incomplete')
    //             ->count();

    //         if ($request->reason === 'information_incomplete') {
    //             $request->validate([
    //                 'missing_info' => 'required|string',
    //             ]);

    //             if ($infoIncompleteCount >= 2) {
    //                 return back()->with('error', 'Maximum attempts reached.');
    //             }

    //             EtoAction::create([
    //                 'application_id' => $complaint->application_id,
    //                 'secure_id' => $secure_id,
    //                 'action_by' => $user->id,
    //                 'role' => 'ETO',
    //                 'action' => 'non_actionable',
    //                 'reason' => 'information_incomplete',
    //                 'missing_info' => $request->missing_info,
    //                 'remarks' => $request->remarks_non,
    //                 'status' => $infoIncompleteCount == 1 ? 'closed' : 'pending',
    //                 'eto_ward' => Auth::user()->ward_no,
    //                 'eto_district' => Auth::user()->district,
    //             ]);

    //             Complainant::where('secure_id', $secure_id)->update([
    //                 'eto_rise_issue' => 1,
    //                 'eto_issue' => $request->missing_info,
    //                 'eto_missing_info_submitted_at' => now(),
    //             ]);

    //             return back()->with('success', 'Missing information submitted.');
    //         }

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',
    //             'action' => 'non_actionable',
    //             'reason' => $request->reason,
    //             'missing_info' => null,
    //             'remarks' => $request->remarks_non,
    //             'status' => 'closed',
    //             'eto_ward' => Auth::user()->ward_no,
    //             'eto_district' => Auth::user()->district,
    //             'eto_id' => Auth::user()->id,
    //         ]);

    //         return back()->with('success', 'Action submitted successfully');
    //     }

    //     return back()->with('error', 'Invalid action');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     $user = Auth::user();
    //     if (! $user) {
    //         return back()->with('error', 'Unauthenticated user');
    //     }

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     $request->validate([
    //         'proposed_action' => 'required|in:uploaded_report,non_actionable',
    //     ]);

    //     if ($request->proposed_action === 'uploaded_report') {

    //         $alreadyUploaded = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'uploaded_report')
    //             ->exists();

    //         if ($alreadyUploaded) {
    //             return back()->with('error', 'Report already uploaded.');
    //         }

    //         $request->validate([
    //             'upload_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //             'remarks_upload' => 'required|string|max:500',
    //         ]);

    //         $file = $request->file('upload_file');
    //         $fileName = 'eto_'.now()->format('YmdHis').'_'.Str::random(8)
    //             .'.'.$file->getClientOriginalExtension();

    //         Storage::disk('public')->putFileAs(
    //             "eto_reports/{$complaint->application_id}",
    //             $file,
    //             $fileName
    //         );

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'user_id' => $complaint->user_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',
    //             'action' => 'uploaded_report',
    //             'report_file' => $fileName,
    //             'remarks' => $request->remarks_upload,
    //             'status' => 'sent_to_hq',
    //             'current_level' => 'HQ',
    //             'forwarded_to' => 'HQ',
    //             'is_final' => 0,
    //             'action_taken_at' => now(),
    //             'eto_ward' => $user->ward_no,
    //             'eto_district' => $user->district,
    //         ]);

    //         return back()->with('success', 'Report uploaded successfully');
    //     }

    //     if ($request->proposed_action === 'non_actionable') {

    //         if ($complaint->eto_rise_issue == 1) {
    //             return back()->with('error', 'Action already taken. Form is locked.');
    //         }

    //         $request->validate([
    //             'reason' => 'required|in:false_information,information_incomplete,any_other',
    //             'remarks_non' => 'required|string|max:500',
    //         ]);

    //         $infoIncompleteCount = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'non_actionable')
    //             ->where('reason', 'information_incomplete')
    //             ->count();

    //         if ($request->reason === 'information_incomplete') {

    //             $request->validate([
    //                 'missing_info' => 'required|string',
    //             ]);

    //             if ($infoIncompleteCount >= 2) {
    //                 return back()->with('error', 'Maximum attempts reached.');
    //             }

    //             EtoAction::create([
    //                 'application_id' => $complaint->application_id,
    //                 'secure_id' => $secure_id,
    //                 'user_id' => $complaint->user_id,
    //                 'action_by' => $user->id,
    //                 'role' => 'ETO',
    //                 'action' => 'non_actionable',
    //                 'reason' => 'information_incomplete',
    //                 'missing_info' => $request->missing_info,
    //                 'remarks' => $request->remarks_non,
    //                 'status' => $infoIncompleteCount == 1 ? 'closed' : 'pending',
    //                 'current_level' => $infoIncompleteCount == 1 ? 'CLOSED' : 'ETO',
    //                 'is_final' => $infoIncompleteCount == 1 ? 1 : 0,
    //                 'action_taken_at' => now(),
    //                 'eto_ward' => $user->ward_no,
    //                 'eto_district' => $user->district,
    //             ]);

    //             Complainant::where('secure_id', $secure_id)->update([
    //                 'eto_rise_issue' => 1,
    //                 'eto_issue' => $request->missing_info,
    //                 'eto_missing_info_submitted_at' => now(),
    //             ]);

    //             return back()->with('success', 'Missing information submitted.');
    //         }

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'user_id' => $complaint->user_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',
    //             'action' => 'non_actionable',
    //             'reason' => $request->reason,
    //             'remarks' => $request->remarks_non,
    //             'status' => 'closed',
    //             'current_level' => 'CLOSED',
    //             'is_final' => 1,
    //             'action_taken_at' => now(),
    //             'eto_ward' => $user->ward_no,
    //             'eto_district' => $user->district,
    //         ]);

    //         return back()->with('success', 'Action submitted successfully');
    //     }

    //     return back()->with('error', 'Invalid action');
    // }

    // public function store(Request $request, $secure_id)
    // {
    //     $user = Auth::user();
    //     if (! $user) {
    //         return back()->with('error', 'Unauthenticated user');
    //     }

    //     $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     $request->validate([
    //         'proposed_action' => 'required|in:uploaded_report,non_actionable',
    //     ]);

    //     if ($request->proposed_action === 'uploaded_report') {

    //         $alreadyUploaded = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'uploaded_report')
    //             ->exists();

    //         if ($alreadyUploaded) {
    //             return back()->with('error', 'Report already uploaded.');
    //         }

    //         $request->validate([
    //             'upload_file'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //             'remarks_upload' => 'required|string|max:500',
    //         ]);

    //         $file = $request->file('upload_file');
    //         $fileName = 'eto_' . now()->format('YmdHis') . '_' . Str::random(8)
    //             . '.' . $file->getClientOriginalExtension();

    //         Storage::disk('public')->putFileAs(
    //             "eto_reports/{$complaint->application_id}",
    //             $file,
    //             $fileName
    //         );

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id'      => $secure_id,
    //             'user_id'        => $complaint->user_id,
    //             'action_by'      => $user->id,
    //             'role'           => 'ETO',

    //             'proposed_action'=> 'uploaded_report',
    //             'action'         => 'uploaded_report',

    //             'reason'         => null,
    //             'missing_info'   => null,

    //             'report_file'    => $fileName,
    //             'remarks'        => $request->remarks_upload,

    //             'status'         => 'sent_to_hq',
    //             'current_status' => 'eto_report_uploaded',

    //             'current_level'  => 'HQ',
    //             'forwarded_to'   => 'HQ',
    //             'is_final'       => 0,
    //             'action_taken_at'=> now(),

    //             'eto_ward'       => $user->ward_no,
    //             'eto_district'   => $user->district,

    //             'ip_address'     => $request->ip(),
    //             'user_agent'     => $request->userAgent(),
    //         ]);

    //         return back()->with('success', 'Report uploaded successfully');
    //     }

    //     if ($request->proposed_action === 'non_actionable') {

    //         if ($complaint->eto_rise_issue == 1) {
    //             return back()->with('error', 'Action already taken. Form is locked.');
    //         }

    //         $request->validate([
    //             'reason'      => 'required|in:false_information,information_incomplete,any_other',
    //             'remarks_non' => 'required|string|max:500',
    //         ]);

    //         $infoIncompleteCount = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'non_actionable')
    //             ->where('reason', 'information_incomplete')
    //             ->count();

    //         if ($request->reason === 'information_incomplete') {

    //             $request->validate([
    //                 'missing_info' => 'required|string',
    //             ]);

    //             if ($infoIncompleteCount >= 2) {
    //                 return back()->with('error', 'Maximum attempts reached.');
    //             }

    //             EtoAction::create([
    //                 'application_id' => $complaint->application_id,
    //                 'secure_id'      => $secure_id,
    //                 'user_id'        => $complaint->user_id,
    //                 'action_by'      => $user->id,
    //                 'role'           => 'ETO',

    //                 'proposed_action'=> 'non_actionable',
    //                 'action'         => 'non_actionable',

    //                 'reason'         => 'information_incomplete',
    //                 'missing_info'   => $request->missing_info,

    //                 'report_file'    => null,
    //                 'remarks'        => $request->remarks_non,

    //                 'status'         => $infoIncompleteCount == 1 ? 'closed' : 'pending',
    //                 'current_status' => 'eto_information_incomplete',

    //                 'current_level'  => $infoIncompleteCount == 1 ? 'CLOSED' : 'ETO',
    //                 'forwarded_to'   => null,
    //                 'is_final'       => $infoIncompleteCount == 1 ? 1 : 0,
    //                 'action_taken_at'=> now(),

    //                 'eto_ward'       => $user->ward_no,
    //                 'eto_district'   => $user->district,

    //                 'ip_address'     => $request->ip(),
    //                 'user_agent'     => $request->userAgent(),
    //             ]);

    //             Complainant::where('secure_id', $secure_id)->update([
    //                 'eto_rise_issue' => 1,
    //                 'eto_issue' => $request->missing_info,
    //                 'eto_missing_info_submitted_at' => now(),
    //             ]);

    //             return back()->with('success', 'Missing information submitted.');
    //         }

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id'      => $secure_id,
    //             'user_id'        => $complaint->user_id,
    //             'action_by'      => $user->id,
    //             'role'           => 'ETO',

    //             'proposed_action'=> 'non_actionable',
    //             'action'         => 'non_actionable',

    //             'reason'         => $request->reason,
    //             'missing_info'   => null,

    //             'report_file'    => null,
    //             'remarks'        => $request->remarks_non,

    //             'status'         => 'closed',
    //             'current_status' => 'eto_non_actionable_closed',

    //             'current_level'  => 'CLOSED',
    //             'forwarded_to'   => null,
    //             'is_final'       => 1,
    //             'action_taken_at'=> now(),

    //             'eto_ward'       => $user->ward_no,
    //             'eto_district'   => $user->district,

    //             'ip_address'     => $request->ip(),
    //             'user_agent'     => $request->userAgent(),
    //         ]);

    //         return back()->with('success', 'Action submitted successfully');
    //     }

    //     return back()->with('error', 'Invalid action');
    // }

    public function store(Request $request, $secure_id)
    {
        $user = Auth::user();
        if (! $user) {
            return back()->with('error', 'Unauthenticated user');
        }

        $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        $request->validate([
            'proposed_action' => 'required|in:uploaded_report,non_actionable',
        ]);

        /* =====================================================
           UPLOAD REPORT (ONLY ONCE)
        ===================================================== */
        if ($request->proposed_action === 'uploaded_report') {

            $alreadyUploaded = EtoAction::where('application_id', $complaint->application_id)
                ->where('action', 'uploaded_report')
                ->exists();

            if ($alreadyUploaded) {
                return back()->with('error', 'Report already uploaded.');
            }

            $request->validate([
                'upload_file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'remarks_upload' => 'required|string|max:500',
            ]);

            // $file = $request->file('upload_file');
            // $fileName = 'eto_'.now()->format('YmdHis').'_'.Str::random(8)
            //     .'.'.$file->getClientOriginalExtension();

            // Storage::disk('public')->putFileAs(
            //     "eto_reports/{$complaint->application_id}",
            //     $file,
            //     $fileName
            // );

            $file = $request->file('upload_file');

            $fileName = 'eto_'.now()->format('YmdHis').'_'.Str::random(8)
                .'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                "complainant/{$complaint->application_id}",
                $file,
                $fileName
            );

            EtoAction::create([
                'application_id' => $complaint->application_id,
                'secure_id' => $secure_id,
                'user_id' => $complaint->user_id,
                'action_by' => $user->id,
                'role' => 'ETO',

                'proposed_action' => 'uploaded_report',
                'action' => 'uploaded_report',

                'reason' => null,
                'missing_info' => null,
                'report_file' => $fileName,
                'remarks' => $request->remarks_upload,

                'status' => 'sent_to_hq',
                'current_status' => 'eto_report_uploaded',

                'current_level' => 'HQ',
                'forwarded_to' => 'HQ',
                'is_final' => 1,
                'action_taken_at' => now(),

                'eto_ward' => $user->ward_no,
                'eto_district' => $user->district,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Report uploaded successfully');
        }

        /* =====================================================
           NON ACTIONABLE
        ===================================================== */
        if ($request->proposed_action === 'non_actionable') {

            if ($complaint->eto_rise_issue == 1) {
                return back()->with('error', 'Action already taken. Form is locked.');
            }

            $request->validate([
                'reason' => 'required|in:false_information,information_incomplete,any_other',
                'remarks_non' => 'required|string|max:500',
            ]);

            $infoIncompleteCount = EtoAction::where('application_id', $complaint->application_id)
                ->where('action', 'non_actionable')
                ->where('reason', 'information_incomplete')
                ->count();

            /* -------------------------------
               INFORMATION INCOMPLETE
            ------------------------------- */
            if ($request->reason === 'information_incomplete') {

                $request->validate([
                    'missing_info' => 'required|string',
                ]);

                if ($infoIncompleteCount >= 2) {
                    return back()->with('error', 'Maximum attempts reached.');
                }

                // 👉 second time = FINAL CLOSE
                $isSecondTime = ($infoIncompleteCount == 1);

                EtoAction::create([
                    'application_id' => $complaint->application_id,
                    'secure_id' => $secure_id,
                    'user_id' => $complaint->user_id,
                    'action_by' => $user->id,
                    'role' => 'ETO',

                    'proposed_action' => 'non_actionable',
                    'action' => 'non_actionable',

                    'reason' => 'information_incomplete',
                    'missing_info' => $request->missing_info,
                    'report_file' => null,
                    'remarks' => $request->remarks_non,

                    'status' => $isSecondTime ? 'closed' : 'pending',
                    'current_status' => 'eto_information_incomplete',

                    'current_level' => $isSecondTime ? 'CLOSED' : 'ETO',
                    'forwarded_to' => null,
                    'is_final' => $isSecondTime ? 1 : 0,
                    'action_taken_at' => now(),

                    'eto_ward' => $user->ward_no,
                    'eto_district' => $user->district,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                Complainant::where('secure_id', $secure_id)->update([
                    'eto_rise_issue' => 1,
                    'eto_issue' => $request->missing_info,
                    'eto_missing_info_submitted_at' => now(),
                ]);

                return back()->with(
                    'success',
                    $isSecondTime
                        ? 'Case completed after second submission.'
                        : 'Missing information submitted.'
                );
            }

            /* -------------------------------
               FALSE INFORMATION / ANY OTHER
            ------------------------------- */
            EtoAction::create([
                'application_id' => $complaint->application_id,
                'secure_id' => $secure_id,
                'user_id' => $complaint->user_id,
                'action_by' => $user->id,
                'role' => 'ETO',

                'proposed_action' => 'non_actionable',
                'action' => 'non_actionable',

                'reason' => $request->reason,
                'missing_info' => null,
                'report_file' => null,
                'remarks' => $request->remarks_non,

                'status' => 'closed',
                'current_status' => 'eto_non_actionable_closed',

                'current_level' => 'CLOSED',
                'forwarded_to' => null,
                'is_final' => 1,
                'action_taken_at' => now(),

                'eto_ward' => $user->ward_no,
                'eto_district' => $user->district,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Action submitted successfully');
        }

        return back()->with('error', 'Invalid action');
    }
}
