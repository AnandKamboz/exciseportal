<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Complainant;
use Illuminate\Support\Facades\DB;

class HQDistrictApplicationController extends Controller
{
    public function districtList()
    {
        $districtWiseApplications = Complainant::select(
            'district_name',
            DB::raw('COUNT(*) as total_applications')
        )
        ->groupBy('district_name')
        ->orderBy('district_name')
        ->get();

        return view('hq.district_applications.district_list',compact('districtWiseApplications'));
    }

    public function districtApplications($district_name)
    {
        $applications = Complainant::where('district_name', $district_name)
            ->latest()
            ->get();
        // dd($applications);

        return view('hq.district_applications.application_list',compact('applications', 'district_name'));
    }

    public function applicationDetails($secure_id)
    {
        $application = Complainant::where('secure_id', $secure_id)->firstOrFail();
        return view('hq.district_applications.application_view',compact('application'));
    }

}
