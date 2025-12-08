<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetcAction;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;


class EtoController extends Controller
{
    public function dashboard()
    {
    //    dd(Auth()->user()->district);
       $etoActions = DetcAction::where('detc_district', auth()->user()->district)->first();
    //    dd($etoActions->complaint_id);
       $informations = Complainant::where('id', $etoActions->complaint_id)->get();
    //    dd($information);
       return view('eto.dashboard',compact('informations'));
    }
}
