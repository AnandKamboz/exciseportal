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

    // Here Code
    // public function viewAll($type)
    // {
    //     $query = Complainant::where('is_completed', 1);

    //     switch ($type) {

    //         case 'all':
    //         break;

    //         case 'open':
    //             $query->where(function ($q) {
    //                 $q->whereNull('current_owner')
    //                     ->orWhereIn('current_owner', ['DETC', 'ETO']);
    //             })->where('is_final', 0);
    //             break;

    //         case 'pending-applicant':
    //             $query->where('current_owner', 'APPLICANT')
    //                 ->where('is_final', 0);
    //             break;

    //         case 'under-review':
    //             $query->whereIn('current_owner', ['DETC', 'ETO', 'HQ'])
    //                 ->where('is_final', 0);
    //             break;

    //         case 'with-detc':
    //             $query->where('current_owner', 'DETC');
    //             break;

    //         case 'with-eto':
    //             $query->where('current_owner', 'ETO');
    //             break;

    //         case 'with-hq':
    //             $query->where('current_owner', 'HQ');
    //             break;

    //         case 'closed':
    //             $query->where(function ($q) {
    //                 $q->where('current_owner', 'CLOSED')
    //                     ->orWhere('is_final', 1);
    //             });
    //             break;

    //         default:
    //             break;
    //     }

    //     $complaints = $query
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('hq.complaints.list', compact('complaints', 'type'));
    // }

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
            'mobile' => 'required|digits:10|unique:users,mobile',
        ]);

        $districtWard = DB::table('district_wards')->where('district_id', $request->district_id)->first();
        if (! $districtWard) {
            return redirect()->back()->with('error', 'Ward configuration not found for this district.');
        }

        if ($request->ward_no > $districtWard->ward_count) {
            return redirect()->back()->with('error', "Invalid ward number. Allowed ward numbers are 1 to {$districtWard->ward_count}.");

        }

        $exists = User::where('district', $request->district_id)
            ->where('ward_no', $request->ward_no)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This ward number already exists in the selected district.');
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
            'hq_mobile' => Auth::user()->mobile,
            'eto_user_id' => $userId,
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
    //     $user = User::where('secure_id', $secure_id)->first();

    //     if (! $user) {
    //         return response()->json([
    //             'message' => 'User not found',
    //         ], 404);
    //     }

    //     $user->delete();

    //     return redirect()->route('hq.eto.list')->with('success', 'ETO deleted successfully');

    // }

    public function destroy($secure_id)
    {
        $eto = User::where('secure_id', $secure_id)->firstOrFail();
        DB::table('eto_deletion_logs')->insert([
            'hq_user_id' => Auth::id(),
            'hq_mobile' => Auth::user()->mobile,
            'eto_user_id' => $eto->id,
            'eto_district_id' => $eto->district,
            'eto_ward_number' => $eto->ward_no,
            'eto_mobile' => $eto->mobile,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_types')->where('user_id', $eto->id)->delete();
        $eto->delete();

        return redirect()->back()->with('success', 'ETO deleted successfully');
    }
}
