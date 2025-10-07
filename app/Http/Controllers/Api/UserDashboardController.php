<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complainant;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function userDashboard(Request $request)
    {
        // $allComplain = Complainant::where('complainant_phone', Auth::user()->mobile)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        $allComplain = Complainant::where('complainant_phone', Auth::user()->mobile)
        ->with(['complainantDistrict:id,name', 'againstDistrict:id,name'])
        ->orderBy('created_at', 'desc')
        ->get();

        

        return response()->json([
            'status' => true,
            'message' => 'All complaints fetched successfully',
            'data' => $allComplain
        ], 200);
    }
}
