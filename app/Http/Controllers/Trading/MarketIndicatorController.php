<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\MarketIndicator;
use Illuminate\Http\Request;

class MarketIndicatorController extends Controller
{
    /**
     * Market indicator list (system-wide)
     */
    public function index(Request $request)
    {
        $indicators = MarketIndicator::all();

        return response()->json($indicators, 200);
    }
}