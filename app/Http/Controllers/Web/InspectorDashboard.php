<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;


class InspectorDashboard extends Controller
{
    public function dashboard()
    {
        $allComplain = Complainant::where('is_completed',1)->orderBy('created_at', 'desc')->get();
        return view('inspector.dashboard',compact('allComplain'));
    }
}
