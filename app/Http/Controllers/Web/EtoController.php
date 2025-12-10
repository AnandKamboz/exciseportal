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
      $action = DetcAction::where('ward_no', auth()->user()->ward_no)->first();
      $informations = $action ? Complainant::where('id', $action->complaint_id)->get() : collect();
      return view('eto.dashboard', compact('informations'));
   }

   public function show($secure_id)
   {
      $complain = Complainant::where('secure_id',$secure_id)->first();
      return view('eto.show', compact('complain'));
   }

}
