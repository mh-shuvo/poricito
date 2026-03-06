<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Ward;

class LocationController extends Controller
{
    public function locations()
    {
        return response()->json([
            'districts' => District::all(),
            'thanas' => Thana::all(),
            'unions' => Union::all(),
            'wards' => Ward::all(),
        ]);
    }
}
