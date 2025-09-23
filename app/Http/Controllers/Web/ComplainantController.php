<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ComplainantController extends Controller
{
    public function complainant()
    {
        if (!Auth::check()) {
          return redirect('/');
        }
        return view('complainant.create');
    }
}
