<?php

namespace App\Http\Controllers\Api;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistrictController extends Controller
{
    public function getAllDistricts()
    {
        $districts = District::all(['id', 'name']);

        return response()->json([
            'success' => true,
            'message' => 'Districts fetched successfully',
            'data'    => $districts
        ], 200);
    }
}
