<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;

class DetcController extends Controller
{
    public function dashboard()
    {
        $allComplain = Complainant::where('against_district_id', Auth::user()->district_id)->where('is_completed',1)->orderBy('created_at', 'desc')->get();
        return view('detc.dashboard',compact('allComplain'));
    }
}
