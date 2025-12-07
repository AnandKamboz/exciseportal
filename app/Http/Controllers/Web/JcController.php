<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JcController extends Controller
{
    public function dashboard()
    {
        $informations = Complainant::where('district_id', Auth::user()->district)->get();

        return view('jc.dashboard', compact('informations'));
    }

    public function details($secure_id)
    {
        // Complain record fetch karo
        $information = DB::table('complainants')
            ->where('secure_id', $secure_id)
            ->first();

        if (! $information) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        if ($information->district_id == 4) {
            $inspectors = DB::table('users')
                ->whereIn('district', [27, 28, 29, 30])
                ->get();
        } elseif ($information->district_id == 1) {
            $inspectors = DB::table('users')
                ->whereIn('district', [23, 24, 25, 26])
                ->get();
        } else {

        }

        // dd($inspectors);

        return view('jc.show', compact('information', 'inspectors'));
    }

    //     public function assign(Request $request, $secure_id)
    //    {

    //         DB::table('complainants')
    //             ->where('secure_id', $secure_id)
    //             ->update([
    //                 'district_id' => $request->detc_id,
    //                 'updated_at' => now(),
    //             ]);

    //         return redirect()->route('jc.dashboard')->with('success', 'Assigned successfully!');
    //     }

    public function assign(Request $request, $secure_id)
    {
        // dd(Auth::user()->district == 4);
        // $request->validate([
        //     'detc_id' => 'required|integer',
        // ]);

        $jcDistrict = Auth::user()->district;
        $allowedDetc = [];

        if ($jcDistrict == 4) {
            $allowedDetc = [27, 28, 29, 30];
        } elseif ($jcDistrict == 1) {
            $allowedDetc = [23, 24, 25, 26];
        } else {
            $allowedDetc = [];
        }

        // Validate detc_id MUST be inside allowedDetc[]
        $request->validate([
            'detc_id' => [
                'required',
                'integer',
                Rule::in($allowedDetc),
            ],
        ]);

        $detcName = User::where('district', $request->detc_id)->value('name');
        $detcId = User::where('district', $request->detc_id)->value('id');

        $complaint = DB::table('complainants')
            ->where('secure_id', $secure_id)
            ->first();

        $alreadyAssigned = DB::table('jc_action_logs')
            ->where('complaint_id', $complaint->application_id)
            ->exists();

        if ($alreadyAssigned) {
            return redirect()
                ->back()
                ->with('error', 'Action already taken! You cannot assign again.');
        }

        DB::table('jc_action_logs')->insert([
            'complaint_id' => $complaint->id,
            'application_id' => $complaint->application_id,

            'jc_id' => Auth::user()->id,
            'jc_name' => Auth::user()->name,
            'jc_mobile' => Auth::user()->mobile,
            'jc_district' => Auth::user()->district,

            'action_type' => 'assigned_to_detc',

            'assigned_detc_id' => $detcId,
            'assigned_detc_name' => $detcName,

            'remarks' => $request->remarks ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('complainants')
            ->where('secure_id', $secure_id)
            ->update([
                'district_id' => $request->detc_id,
                'updated_at' => now(),
            ]);

        return redirect()->route('jc.dashboard')->with('success', 'Assigned successfully!');
    }
}
