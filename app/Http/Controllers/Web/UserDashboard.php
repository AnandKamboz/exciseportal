<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complainant;



class UserDashboard extends Controller
{
    public function userDashboard()
    {
        if (!Auth::check()) {
          return redirect('/');
        }
        
        $allComplain = Complainant::where('complainant_phone', Auth::user()->mobile)->where('is_completed', 1)
        ->orderBy('created_at', 'desc')
        ->get();

       return view("users.dashboard",compact('allComplain'));
    }
}
