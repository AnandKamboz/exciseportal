<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetcAction;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;


class EtoController extends Controller
{
   //  public function dashboard()
   //  {
   //  //    dd(Auth()->user()->district);
   //     $etoActions = DetcAction::where('detc_district', auth()->user()->district)->first() ?? "";
   //     dd($etoActions);
   //  //    dd($etoActions->complaint_id);
   //     $informations = Complainant::where('id', $etoActions->complaint_id)->get() ?? "";
   //  //    dd($information);
   //     return view('eto.dashboard',compact('informations'));
   //  }

   public function dashboard()
   {
      $action = DetcAction::where('ward_no', auth()->user()->ward_no)->first();
      $informations = $action ? Complainant::where('id', $action->complaint_id)->get() : collect();
      return view('eto.dashboard', compact('informations'));
   }
}
