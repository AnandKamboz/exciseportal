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

        return view('detc.dashboard', compact('allComplain', 'allComplain'));
    }

    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
        $applicationId = $complain->application_id;

        // detc_actions table me check karo
        $detcAction = DetcAction::where('user_application_id', $applicationId)->first();

        // boolean check
        $actionExists = $detcAction ? true : false;

        $complainantDistrictName = optional(
            DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
        )->name ?? 'Not Found';

        $againstDistrictId = optional(
            DB::table('districts')->where('id', $complain->against_district_id)->first()
        )->name ?? 'Not Found';

        return view('detc.show', compact('complain', 'complainantDistrictName', 'againstDistrictId', 'actionExists', 'detcAction'));
    }

    public function updateComplaintStatus(Request $request, $secure_id)
    {

        $request->validate([
            'status' => 'required|string|in:forward_to_inspector,rejected',
            'remarks' => 'required|string|max:1000',
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

    public function store(Request $request, $secure_id)
    {
        // dd($request->toArray());
        $request->validate([
            'proposed_action' => 'required',
            'action_taken' => 'required_if:proposed_action,actionable',
            'reason' => 'required_if:proposed_action,non_actionable',
            'upload_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
        ]);

        // dd('sdsd');

        $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        if (DetcAction::where('complaint_id', $complaint->id)->exists()) {
            return back()->with('error', 'Action already taken for this complaint');
        }

        do {
            $newActionSecureId = Str::random(32);
        } while (DetcAction::where('secure_id', $newActionSecureId)->exists());

        // ===============================
        // FILE UPLOAD LOGIC
        // ===============================
        // $fileName = null;

        // if ($request->hasFile('upload_file')) {

        //     $file = $request->file('upload_file');

        //     // unique filename
        //     $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        //     // final path
        //     $path = 'public/complaints/'.$complaint->application_id.'/';

        //     // store
        //     $file->storeAs($path, $fileName);
        // }

        $fileName = null;

        if ($request->hasFile('upload_file')) {

            $file = $request->file('upload_file');

            // unique filename
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            // final path
            $path = 'complaints/'.$complaint->application_id.'/';

            // store in PUBLIC
            $file->storeAs($path, $fileName, 'public');
        }

        DetcAction::create([
            'secure_id' => $newActionSecureId,
            'complaint_id' => $complaint->id,
            'user_application_id' => $complaint->application_id,
            'detc_district' => Auth::user()->district,
            'proposed_action' => $request->proposed_action,
            'action_taken' => $request->action_taken,
            'reason' => $request->reason,
            'remarks' => $request->remarks ?? null,
            'detc_user_id' => auth()->id(),
            'file_name' => $fileName, // store file name
        ]);

        return back()->with('success', 'Action saved successfully');
    }
}
