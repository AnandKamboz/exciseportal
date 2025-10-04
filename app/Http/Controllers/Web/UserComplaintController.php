<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\DB;



class UserComplaintController extends Controller
{
    public function show($secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->firstOrFail();
        $complainantDistrictName = optional(
            DB::table('districts')->where('id', $complain->complainant_dist_id)->first()
        )->name ?? 'Not Found';

        $againstDistrictId = optional(
            DB::table('districts')->where('id', $complain->against_district_id)->first()
        )->name ?? 'Not Found';



        
        

        return view('users.complaints.show', compact('complain','complainantDistrictName','againstDistrictId'));
    }
}
