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
                ->where('ward_no', Auth::user()->ward_no)
                ->where('detc_district', Auth::user()->district);
        })->get();

        $action = DetcAction::where('ward_no', auth()->user()->ward_no)->first();

        return view('eto.dashboard', compact('informations'));
    }

    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();

        $etoActions = EtoAction::where('application_id', $complain->application_id)
            ->orderBy('id', 'asc')
            ->get();
        
        $detcActions = DetcAction::where('user_application_id',$complain->application_id)->get();

        $infoIncompleteCount = EtoAction::where('application_id', $complain->application_id)
            ->where('action', 'non_actionable')
            ->where('reason', 'information_incomplete')
            ->count();

        $etoAction = $etoActions->last();

        return view('eto.show', compact(
            'complain',
            'etoActions',
            'etoAction',
            'infoIncompleteCount',
            'detcActions'
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

    //         $alreadyUploaded = EtoAction::where('application_id', $complaint->application_id)
    //             ->where('action', 'uploaded_report')
    //             ->exists();

    //         if ($alreadyUploaded) {
    //             return back()->with('error', 'Report already uploaded.');
    //         }

    //         $request->validate([
    //             'upload_file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
    //             'remarks_upload' => 'required|string|max:500',
    //         ]);

    //         $file = $request->file('upload_file');

    //         $fileName = 'eto_'.now()->format('YmdHis').'_'.Str::random(8)
    //             .'.'.$file->getClientOriginalExtension();

    //         Storage::disk('public')->putFileAs(
    //             "complainant/{$complaint->application_id}",
    //             $file,
    //             $fileName
    //         );

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'user_id' => $complaint->user_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',

    //             'proposed_action' => 'uploaded_report',
    //             'action' => 'uploaded_report',

    //             'reason' => null,
    //             'missing_info' => null,
    //             'report_file' => $fileName,
    //             'remarks' => $request->remarks_upload,

    //             'status' => 'sent_to_hq',
    //             'current_status' => 'eto_report_uploaded',

    //             'current_level' => 'HQ',
    //             'forwarded_to' => 'HQ',
    //             'is_final' => 1,
    //             'action_taken_at' => now(),

    //             'eto_ward' => $user->ward_no,
    //             'eto_district' => $user->district,
    //             'ip_address' => $request->ip(),
    //             'user_agent' => $request->userAgent(),
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

    //             $isSecondTime = ($infoIncompleteCount == 1);

    //             EtoAction::create([
    //                 'application_id' => $complaint->application_id,
    //                 'secure_id' => $secure_id,
    //                 'user_id' => $complaint->user_id,
    //                 'action_by' => $user->id,
    //                 'role' => 'ETO',

    //                 'proposed_action' => 'non_actionable',
    //                 'action' => 'non_actionable',

    //                 'reason' => 'information_incomplete',
    //                 'missing_info' => $request->missing_info,
    //                 'report_file' => null,
    //                 'remarks' => $request->remarks_non,

    //                 'status' => $isSecondTime ? 'closed' : 'pending',
    //                 'current_status' => 'eto_information_incomplete',

    //                 'current_level' => $isSecondTime ? 'CLOSED' : 'ETO',
    //                 'forwarded_to' => null,
    //                 'is_final' => $isSecondTime ? 1 : 0,
    //                 'action_taken_at' => now(),

    //                 'eto_ward' => $user->ward_no,
    //                 'eto_district' => $user->district,
    //                 'ip_address' => $request->ip(),
    //                 'user_agent' => $request->userAgent(),
    //             ]);

    //             Complainant::where('secure_id', $secure_id)->update([
    //                 'eto_rise_issue' => 1,
    //                 'eto_issue' => $request->missing_info,
    //                 'eto_missing_info_submitted_at' => now(),
    //             ]);

    //             return back()->with(
    //                 'success',
    //                 $isSecondTime
    //                     ? 'Case completed after second submission.'
    //                     : 'Missing information submitted.'
    //             );
    //         }

    //         EtoAction::create([
    //             'application_id' => $complaint->application_id,
    //             'secure_id' => $secure_id,
    //             'user_id' => $complaint->user_id,
    //             'action_by' => $user->id,
    //             'role' => 'ETO',

    //             'proposed_action' => 'non_actionable',
    //             'action' => 'non_actionable',

    //             'reason' => $request->reason,
    //             'missing_info' => null,
    //             'report_file' => null,
    //             'remarks' => $request->remarks_non,

    //             'status' => 'closed',
    //             'current_status' => 'eto_non_actionable_closed',

    //             'current_level' => 'CLOSED',
    //             'forwarded_to' => null,
    //             'is_final' => 1,
    //             'action_taken_at' => now(),

    //             'eto_ward' => $user->ward_no,
    //             'eto_district' => $user->district,
    //             'ip_address' => $request->ip(),
    //             'user_agent' => $request->userAgent(),
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

    /*
    |--------------------------------------------------------------------------
    | CASE 1: UPLOADED REPORT → FILE GOES TO HQ
    |--------------------------------------------------------------------------
    */
    if ($request->proposed_action === 'uploaded_report') {

        $alreadyUploaded = EtoAction::where('application_id', $complaint->application_id)
            ->where('action', 'uploaded_report')
            ->exists();

        if ($alreadyUploaded) {
            return back()->with('error', 'Report already uploaded.');
        }

        $request->validate([
            'upload_file'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'remarks_upload'  => 'required|string|max:500',
        ]);

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
            'secure_id'      => $secure_id,
            'user_id'        => $complaint->user_id,
            'action_by'      => $user->id,
            'role'           => 'ETO',

            'proposed_action' => 'uploaded_report',
            'action'          => 'uploaded_report',

            'reason'        => null,
            'missing_info'  => null,
            'report_file'   => $fileName,
            'remarks'       => $request->remarks_upload,

            'status'         => 'sent_to_hq',
            'current_status' => 'eto_report_uploaded',
            'current_level'  => 'HQ',
            'forwarded_to'   => 'HQ',
            'is_final'       => 1,
            'action_taken_at'=> now(),

            'eto_ward'     => $user->ward_no,
            'eto_district' => $user->district,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        // ✅ NEW (ADD ONLY)
        $complaint->update([
            'current_owner'  => 'HQ',
            'current_level'  => 'HQ',
            'current_status' => 'eto_report_uploaded',
            'is_final'       => false,
        ]);

        return back()->with('success', 'Report uploaded successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | CASE 2: NON ACTIONABLE
    |--------------------------------------------------------------------------
    */
    if ($request->proposed_action === 'non_actionable') {

        // 🔒 ORIGINAL LOCK
        if ($complaint->eto_rise_issue == 1) {
            return back()->with('error', 'Action already taken. Form is locked.');
        }

        $request->validate([
            'reason'       => 'required|in:false_information,information_incomplete,any_other',
            'remarks_non'  => 'required|string|max:500',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUB CASE 2A: INFORMATION INCOMPLETE → FILE GOES TO APPLICANT
        |--------------------------------------------------------------------------
        */
        if ($request->reason === 'information_incomplete') {

            $request->validate([
                'missing_info' => 'required|string',
            ]);

            $infoIncompleteCount = EtoAction::where('application_id', $complaint->application_id)
                ->where('action', 'non_actionable')
                ->where('reason', 'information_incomplete')
                ->count();

            if ($infoIncompleteCount >= 2) {
                return back()->with('error', 'Maximum attempts reached.');
            }

            $isSecondTime = ($infoIncompleteCount == 1);

            EtoAction::create([
                'application_id' => $complaint->application_id,
                'secure_id'      => $secure_id,
                'user_id'        => $complaint->user_id,
                'action_by'      => $user->id,
                'role'           => 'ETO',

                'proposed_action'=> 'non_actionable',
                'action'         => 'non_actionable',
                'reason'         => 'information_incomplete',
                'missing_info'   => $request->missing_info,
                'remarks'        => $request->remarks_non,

                'status'         => $isSecondTime ? 'closed' : 'pending',
                'current_status' => 'eto_information_incomplete',
                'current_level'  => $isSecondTime ? 'CLOSED' : 'ETO',
                'forwarded_to'   => null,
                'is_final'       => $isSecondTime ? 1 : 0,
                'action_taken_at'=> now(),

                'eto_ward'     => $user->ward_no,
                'eto_district' => $user->district,
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
            ]);

            // 🔒 ORIGINAL CODE (AS-IS)
            Complainant::where('secure_id', $secure_id)->update([
                'eto_rise_issue' => 1,
                'eto_issue' => $request->missing_info,
                'eto_missing_info_submitted_at' => now(),
            ]);

            // ✅ NEW (ADD ONLY)
            $complaint->update([
                'current_owner'  => $isSecondTime ? 'CLOSED' : 'APPLICANT',
                'current_level'  => $isSecondTime ? 'CLOSED' : 'ETO',
                'current_status' => 'information_incomplete',
                'is_final'       => $isSecondTime,
            ]);

            return back()->with(
                'success',
                $isSecondTime
                    ? 'Case completed after second submission.'
                    : 'Missing information requested from applicant.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUB CASE 2B: FALSE INFORMATION / ANY OTHER → CLOSED
        |--------------------------------------------------------------------------
        */
        EtoAction::create([
            'application_id' => $complaint->application_id,
            'secure_id'      => $secure_id,
            'user_id'        => $complaint->user_id,
            'action_by'      => $user->id,
            'role'           => 'ETO',

            'proposed_action'=> 'non_actionable',
            'action'         => 'non_actionable',
            'reason'         => $request->reason,
            'missing_info'   => null,
            'remarks'        => $request->remarks_non,

            'status'         => 'closed',
            'current_status' => 'eto_non_actionable_closed',
            'current_level'  => 'CLOSED',
            'forwarded_to'   => null,
            'is_final'       => 1,
            'action_taken_at'=> now(),

            'eto_ward'     => $user->ward_no,
            'eto_district' => $user->district,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        // ✅ NEW (ADD ONLY)
        $complaint->update([
            'current_owner'  => 'CLOSED',
            'current_level'  => 'CLOSED',
            'current_status' => 'eto_non_actionable_closed',
            'is_final'       => true,
        ]);

        return back()->with('success', 'Action submitted successfully');
    }

    return back()->with('error', 'Invalid action');
}


}
