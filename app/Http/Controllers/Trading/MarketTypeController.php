<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\MarketType;
use Illuminate\Http\Request;

class MarketTypeController extends Controller
{
    /**
     * Market type list (system-wide)
     */
    public function index(Request $request)
    {
        $marketTypes = MarketType::all();

        return response()->json($marketTypes, 200);
    }
}
