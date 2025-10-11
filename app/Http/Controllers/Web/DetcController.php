<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetcController extends Controller
{
    public function dashboard()
    {
        $allComplain = Complainant::where('against_district_id', Auth::user()->district)->where('is_completed',1)->orderBy('created_at', 'desc')->get();
        return view('detc.dashboard',compact('allComplain','allComplain'));
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
        return view('detc.show', compact('complain','complainantDistrictName','againstDistrictId'));
    }

    public function updateComplaintStatus(Request $request, $secure_id)
    {
       
        $request->validate([
            'status' => 'required|string|in:forward_to_inspector,rejected',
            'remarks' => 'required|string|max:1000',
        ]);

        //  dd('dddss!');

        // Find complaint by secure_id
        $complaint = Complainant::where('secure_id', $secure_id)->firstOrFail();

        $complaint->update([
            'detc_status' => $request->status,
            'detc_remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Complaint status updated successfully!');
    }

}
