<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\ConditionalTrade;
use Illuminate\Http\Request;

class ConditionalTradeController extends Controller
{
    //
    public function index(Request $request)
    {
        $conditionalTrades = ConditionalTrade::query()->get();
        return response()->json($conditionalTrades);
    }
}