<?php

namespace App\Http\Controllers;

use App\Models\DetcAction;
use Illuminate\Http\Request;

class DetcActionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'complaint_id'      => 'required',
            'proposed_action'   => 'required',
            'remarks'           => 'required',
        ]);

        DetcAction::create([
            'complaint_id'      => $request->complaint_id,
            'proposed_action'   => $request->proposed_action,
            'action_taken'      => $request->action_taken,
            'reason'            => $request->reason,
            'remarks'           => $request->remarks,
            'detc_user_id'      => auth()->id(),
            'status'            => 'pending',
        ]);

        return back()->with('success', 'Action saved successfully');
    }
}

