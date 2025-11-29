<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\DetcAction;

class HqController extends Controller
{
    public function dashboard()
    {
        $informations = Complainant::latest()->get();

        return view('hq.dashboard', compact('informations'));
    }

    // public function show($secure_id)
    // {
    //     $information = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     $district = null;
    //     if ($information->complainant_district) {
    //         $district = \App\Models\District::where('id', $information->complainant_district)->value('name');
    //     }

    //     $detcAction = DetcAction::where('user_application_id', $information->application_id)->first();

    //     return view('hq.details', compact('information', 'district', 'detcAction'));
    // }

    // public function show($secure_id)
    // {
    //     $information = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     $district = null;
    //     if ($information->complainant_district) {
    //         $district = \App\Models\District::where('id', $information->complainant_district)->value('name');
    //     }

    //     $detcAction = DetcAction::where('user_application_id', $information->application_id)->first();

    //     return view('hq.details', compact('information', 'district', 'detcAction'));
    // }

    // public function show($secure_id)
    // {
    //     $information = Complainant::where('secure_id', $secure_id)->firstOrFail();

    //     $applicationId = $information->application_id;
    //     $detc_record = DetcAction::where('user_application_id', $applicationId)->first();

    //     // state name
    //     $stateName = null;
    //     if ($information->complainant_state) {
    //         $stateName = \DB::table('states')->where('id', $information->complainant_state)->value('name');
    //     }

    //     // district name
    //     $districtName = null;
    //     if ($information->complainant_district) {
    //         $districtName = \DB::table('india_districts')->where('id', $information->complainant_district)->value('name');
    //     }

    //     return view('hq.details', compact('information', 'stateName', 'districtName','detc_record'));
    // }

//     public function show($secure_id)
// {
//     $information = Complainant::where('secure_id', $secure_id)
//         ->with('detcAction')
//         ->firstOrFail();

//     // state name
//     $stateName = null;
//     if ($information->complainant_state) {
//         $stateName = \DB::table('states')->where('id', $information->complainant_state)->value('name');
//     }

//     // district name
//     $districtName = null;
//     if ($information->complainant_district) {
//         $districtName = \DB::table('india_districts')
//             ->where('id', $information->complainant_district)
//             ->value('name');
//     }

//     return view('hq.details', compact('information', 'stateName', 'districtName'));
// }

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


}
