<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\DetcAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\District;
use Illuminate\Validation\Rule;
use App\Models\DetcDeleteLog;
use App\Models\DetcTransferLog;
use App\Models\DetcUpdateLog;

class HqController extends Controller
{
    // public function dashboard()
    // {
    //     $allComplain = Complainant::where('is_completed', 1)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $totalInformation = $allComplain->count();
    //     $forwardedtoEto = DetcAction::where('send_to', 'eto')->count();
    //     $forwardedtoHq = DetcAction::where('send_to', 'hq')->count();
    //     $pendingFromApplicant = Complainant::where('is_completed', 1)
    //         ->where('detc_rise_issue', 1)
    //         ->whereNull('missing_info_submitted_at')
    //         ->count();
    //     $pendingFromDetc = Complainant::where('is_completed', 1)
    //         ->where('detc_rise_issue', 1)
    //         ->whereNull('missing_info_submitted_at')
    //         ->count();

    //     $informations = Complainant::latest()->get();

    //     return view('hq.dashboard', compact('informations','allComplain','totalInformation','forwardedtoEto','forwardedtoHq','pendingFromApplicant','pendingFromDetc'));
    // }

    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | FETCH ALL COMPLETED COMPLAINTS (HQ CAN SEE ALL)
        |--------------------------------------------------------------------------
        */
        $allComplain = Complainant::where('is_completed', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD CARDS (HQ VIEW)
        |--------------------------------------------------------------------------
        */

        // 🟢 CARD 1: OPEN / NEW
        $openNew = Complainant::where('is_completed', 1)
            ->where(function ($q) {
                $q->whereNull('current_owner')
                    ->orWhereIn('current_owner', ['DETC', 'ETO']);
            })
            ->where('is_final', 0)
            ->count();

        // 🟡 CARD 2: ACTION REQUIRED (Applicant)
        $pendingFromApplicant = Complainant::where('is_completed', 1)
            ->where('current_owner', 'APPLICANT')
            ->where('is_final', 0)
            ->count();

        // 🔵 CARD 3: UNDER REVIEW (Dept / HQ)
        $underReview = Complainant::where('is_completed', 1)
            ->whereIn('current_owner', ['DETC', 'ETO', 'HQ'])
            ->where('is_final', 0)
            ->count();

        // 🔴 CARD 4: CLOSED
        $closed = Complainant::where('is_completed', 1)
            ->where(function ($q) {
                $q->where('current_owner', 'CLOSED')
                    ->orWhere('is_final', 1);
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ADD STATUS SUMMARY (ONE COLUMN – FULL STORY)
        |--------------------------------------------------------------------------
        */
        $informations = $allComplain->map(function ($c) {

            if (is_null($c->current_owner)) {
                $c->status_summary = '🆕 Complaint Submitted – Awaiting Review';

            } elseif ($c->current_owner === 'APPLICANT') {
                $c->status_summary = '⚠️ Action Required from Applicant ('.$c->current_level.')';

            } elseif ($c->current_owner === 'DETC') {
                $c->status_summary = '🕵️ Under Review by DETC';

            } elseif ($c->current_owner === 'ETO') {
                $c->status_summary = '🕵️ Under Review by ETO';

            } elseif ($c->current_owner === 'HQ') {
                $c->status_summary = '📄 Report Received – Pending HQ Decision';

            } elseif ($c->current_owner === 'CLOSED') {
                $c->status_summary = '✅ Complaint Closed';

            } else {
                $c->status_summary = 'Status Unknown';
            }

            return $c;
        });

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('hq.dashboard', compact(
            'informations',
            'allComplain',
            'openNew',
            'pendingFromApplicant',
            'underReview',
            'closed'
        ));
    }

    public function show($secure_id)
    {
        $information = Complainant::where('secure_id', $secure_id)
            ->with('detcAction')
            ->firstOrFail();

        $detcAction = $information->detcAction;

        $stateName = null;
        if ($information->complainant_state) {
            $stateName = \DB::table('states')->where('id', $information->complainant_state)->value('name');
        }

        $districtName = null;
        if ($information->complainant_district) {
            $districtName = \DB::table('india_districts')
                ->where('id', $information->complainant_district)
                ->value('name');
        }

        return view('hq.details', compact('information', 'stateName', 'districtName', 'detcAction'));
    }

    public function viewAll($type)
    {
        $query = Complainant::where('is_completed', 1);

        switch ($type) {

            case 'all':
                break;

            case 'open':
                $query->where(function ($q) {
                    $q->whereNull('current_owner')
                        ->orWhereIn('current_owner', ['DETC', 'ETO']);
                })->where('is_final', 0);
                break;

            case 'pending-applicant':
                $query->where('current_owner', 'APPLICANT')
                    ->where('is_final', 0);
                break;

            case 'under-review':
                $query->whereIn('current_owner', ['DETC', 'ETO', 'HQ'])
                    ->where('is_final', 0);
                break;

            case 'with-detc':
                $query->where('current_owner', 'DETC');
                break;

            case 'with-eto':
                $query->where('current_owner', 'ETO');
                break;

            case 'with-hq':
                $query->where('current_owner', 'HQ');
                break;

            case 'closed':
                $query->where(function ($q) {
                    $q->where('current_owner', 'CLOSED')
                        ->orWhere('is_final', 1);
                });
                break;

            default:
                break;
        }

        $complaints = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hq.complaints.list', compact('complaints', 'type'));
    }

    public function etoList()
    {
        $users = User::join('role_types', 'role_types.user_id', '=', 'users.id')
            ->leftJoin('districts', 'districts.id', '=', 'users.district')
            ->where('role_types.role_id', 7)
             ->where('users.is_deleted', 0) 
            ->select(
                'users.*',
                'districts.name as district_name'
            )
            ->orderBy('users.id', 'desc')
            ->get();

        return view('hq.eto.index', compact('users'));
    }

    public function etoCreate()
    {
        $districts = DB::table('districts')->whereNotIn('id', [1, 4])->orderBy('name', 'asc')->get();

        return view('hq.eto.create', compact('districts'));
    }

    public function etoStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|integer',
            'ward_no' => 'required|integer',
            'mobile' => 'required|digits:10',
        ]);

        $existingUser = User::where('mobile', $request->mobile)->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'This mobile number is already registered.');
        }
        
        $districtWard = DB::table('district_wards')->where('district_id', $request->district_id)->first();

        if (! $districtWard) {
            return redirect()->back()->with('error', 'Ward configuration not found for this district.');
        }

        if ($request->ward_no > $districtWard->ward_count) {
            return redirect()->back()->with('error', "Invalid ward number. Allowed ward numbers are 1 to {$districtWard->ward_count}.");

        }

        $exists = User::where('district', $request->district_id)->where('ward_no', $request->ward_no)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'An ETO is already assigned to this district and ward.');
        }

        do {
            $secureId = (string) Str::uuid();
        } while (
            DB::table('users')->where('secure_id', $secureId)->exists()
        );

        $userId = DB::table('users')->insertGetId([
            'secure_id' => $secureId,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'district' => $request->district_id,
            'ward_no' => $request->ward_no,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_types')->insert([
            'user_id' => $userId,
            'role_id' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('eto_creation_logs')->insert([
            'hq_user_id' => Auth::id(),
            'hq_name'    => Auth::user()->name, 
            'hq_mobile' => Auth::user()->mobile,
            'eto_user_id' => $userId,
            'eto_name'    => $request->name, 
            'eto_district_id' => $request->district_id,
            'eto_ward_number' => $request->ward_no,
            'eto_mobile' => $request->mobile,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('hq.eto.list')->with('success', 'ETO created successfully');
    }

    // public function destroy($secure_id)
    // {
    //     $eto = User::where('secure_id', $secure_id)->firstOrFail();

    //     DB::table('eto_deletion_logs')->insert([
    //         'hq_user_id' => Auth::id(),
    //         'hq_name'    => Auth::user()->name,
    //         'hq_mobile' => Auth::user()->mobile,
    //         'eto_user_id' => $eto->id,
    //         'eto_name'    => $eto->name, 
    //         'eto_district_id' => $eto->district,
    //         'eto_ward_number' => $eto->ward_no,
    //         'eto_mobile' => $eto->mobile,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     DB::table('role_types')->where('user_id', $eto->id)->delete();
    //     $eto->delete();

    //     return redirect()->back()->with('success', 'ETO deleted successfully');
    // }

    public function destroy($secure_id)
    {
        $eto = User::where('secure_id', $secure_id)
                ->where('is_deleted', 0)
                ->firstOrFail();

        DB::table('eto_deletion_logs')->insert([
            'hq_user_id'        => Auth::id(),
            'hq_name'           => Auth::user()->name,
            'hq_mobile'         => Auth::user()->mobile,

            'eto_user_id'       => $eto->id,
            'eto_name'          => $eto->name,
            'eto_district_id'   => $eto->district,
            'eto_ward_number'   => $eto->ward_no,
            'eto_mobile'        => $eto->mobile,

            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $eto->update([
            'is_active'  => 0,   
            'is_deleted' => 1, 
        ]);

        // DB::table('role_types')
        //     ->where('user_id', $eto->id)
        //     ->update([
        //         'is_active' => 0
        //     ]);

        return redirect()->back()->with('success', 'ETO deleted successfully');
    }


    public function hqList()
    {
        $users = User::join('role_types', 'role_types.user_id', '=', 'users.id')
            ->leftJoin('districts', 'districts.id', '=', 'users.district')
            ->where('role_types.role_id', 5)
            ->select(
                'users.*',
                'districts.name as district_name'
            )
            ->orderByRaw('
        CASE 
            WHEN users.id = 91 THEN 1
            WHEN users.id = 93 THEN 2
            WHEN users.id = 1  THEN 3
            ELSE 4
        END
    ')
            ->orderBy('users.id', 'asc')
            ->get();

        return view('hq.hq_user.index', compact('users'));
    }

    // createHqUser

    public function createHqUser()
    {
        return view('hq.hq_user.create');
    }

    public function hqUserStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile',
        ]);

        do {
            $secureId = (string) Str::uuid();
        } while (
            DB::table('users')->where('secure_id', $secureId)->exists()
        );

        $userId = DB::table('users')->insertGetId([
            'secure_id' => $secureId,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_types')->insert([
            'user_id' => $userId,
            'role_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('hq_creation_logs')->insert([
            'created_by_hq_id' => Auth::id(),
            'created_hq_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('hq.list')->with('success', 'HQ User created successfully');
    }

    public function detcList(Request $request){
        $users = User::join('role_types', 'role_types.user_id', '=', 'users.id')
            ->leftJoin('districts', 'districts.id', '=', 'users.district')
            ->where('role_types.role_id', 3)
             ->where('users.is_deleted', 0)
            ->select(
                'users.*',
                'districts.name as district_name'
            )
            ->orderBy('districts.name', 'asc')
            ->orderBy('users.id', 'asc')
            ->get();

        return view("hq.detc.index",compact("users"));
    }

    public function editDetc($secure_id)
    {
        $detc = User::where('secure_id', $secure_id)->firstOrFail();
        $districtName = District::where('id', $detc->district)->value('name');

        return view('hq.detc.edit', compact('detc','districtName'));
    }

    public function updateDetc(Request $request, $secure_id)
    {
        $detc = User::where('secure_id', $secure_id)->firstOrFail();

        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => [
                'required',
                'digits:10',
                Rule::unique('users', 'mobile')->ignore($detc->id),
            ],
        ]);

        $oldName   = $detc->name;
        $oldMobile = $detc->mobile;

        $detc->update([
            'name'   => $request->name,
            'mobile' => $request->mobile,
        ]);

        DetcUpdateLog::create([
            'detc_user_id' => $detc->id,
            'updated_by'   => auth()->id(),
            'hq_name'      => auth()->user()->name,
            'hq_mobile'    => auth()->user()->mobile,
            'old_name'     => $oldName,
            'new_name'     => $request->name,
            'old_mobile'   => $oldMobile,
            'new_mobile'   => $request->mobile,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        return redirect()
            ->route('hq.detc.list')
            ->with('success', 'DETC details updated successfully');
    }

    public function destroyDetc($secure_id)
    {
        $detc = User::where('secure_id', $secure_id)->firstOrFail();

        $districtName = District::where('id', $detc->district)->value('name');

        DetcDeleteLog::create([
            'detc_user_id'  => $detc->id,
            'detc_name'     => $detc->name,
            'detc_mobile'   => $detc->mobile,
            'district_id'   => $detc->district,
            'district_name' => $districtName,
            'deleted_by'    => auth()->id(), 
            'hq_name'     => Auth::user()->name,     
            'hq_mobile'      => Auth::user()->mobile,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);

        // $detc->delete();
        $detc->update([
            'is_active' => 0,
            'is_deleted' => 1,
        ]);

        return redirect()->route('hq.detc.list')->with('success', 'DETC deleted successfully');
    }

    public function transferDetc(Request $request)
    {
        $detcs = User::join('role_types', 'role_types.user_id', '=', 'users.id')
            ->leftJoin('districts', 'districts.id', '=', 'users.district')
            ->where('role_types.role_id', 3) 
            ->select(
                'users.id',
                'users.secure_id',
                'users.name',
                'users.district as district_id',     
                'districts.name as district_name',
                DB::raw("CONCAT(districts.name, ' (', users.name, ')') as detc_label")
            )
            ->orderBy('districts.name', 'asc')
            ->get();

            return view('hq.detc.transfer', compact('detcs'));
    }


    public function transferStore(Request $request)
    {
        $request->validate([
            'from_detc' => 'required|different:to_detc',
            'to_detc'   => 'required',
            'remarks'   => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $fromDetc = User::where('secure_id', $request->from_detc)->firstOrFail();
            $toDetc   = User::where('secure_id', $request->to_detc)->firstOrFail();

            $fromDistrictId = $fromDetc->district;
            $toDistrictId   = $toDetc->district;

            $fromDistrictName = District::where('id', $fromDistrictId)->value('name');
            $toDistrictName   = District::where('id', $toDistrictId)->value('name');

            $fromDetc->update([
                'district' => $toDistrictId,
            ]);

            $toDetc->update([
                'district' => $fromDistrictId,
            ]);

            DetcTransferLog::create([
                'from_detc_id'        => $fromDetc->id,
                'from_detc_name'      => $fromDetc->name,
                'from_district_id'    => $fromDistrictId,
                'from_district_name'  => $fromDistrictName,
                'from_detc_mobile'    => $fromDetc->mobile, 

                'to_detc_id'          => $toDetc->id,
                'to_detc_name'        => $toDetc->name,
                'to_district_id'      => $toDistrictId,
                'to_district_name'    => $toDistrictName,
                'to_detc_mobile'      => $toDetc->mobile,     


                'transferred_by'      => auth()->id(), 
                'hq_name'             => auth()->user()->name,    
                'hq_mobile'           => auth()->user()->mobile,   
                'remarks'             => $request->remarks,


                'ip_address'          => request()->ip(),
                'user_agent'          => request()->userAgent(),
            ]);
        });

        return redirect()
            ->route('hq.detc.list')
            ->with('success', 'DETC transferred successfully');
    }

    public function toggleStatus($secure_id)
    {
        $eto = User::where('secure_id', $secure_id)->firstOrFail();

        $eto->update([
            'is_active' => $eto->is_active == 1 ? 0 : 1,
        ]);

        return back()->with(
            'success',
            $eto->is_active == 1
                ? 'ETO activated successfully'
                : 'ETO deactivated successfully'
        );
    }

    // public function deactivate($secure_id)
    // {
    //     $detc = User::where('secure_id', $secure_id)
    //                 ->where('is_deleted', 0)
    //                 ->firstOrFail();

    //     $detc->update([
    //         'is_active' => 0
    //     ]);

    //     return redirect()->back()->with('success', 'DETC deactivated successfully.');
    // }

    public function detcToggle(Request $request)
    {
        $detc = User::where('secure_id', $request->secure_id)
                    ->where('is_deleted', 0)
                    ->firstOrFail();

        $detc->update([
            'is_active' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->status
                ? 'DETC activated successfully.'
                : 'DETC deactivated successfully.'
        ]);
    }


}
