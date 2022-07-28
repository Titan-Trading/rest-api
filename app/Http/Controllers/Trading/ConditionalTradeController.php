<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\ConditionalTrade;
use Illuminate\Http\Request;

class ConditionalTradeController extends Controller
{
    /**
     * Get list of conditional trades
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $conditionalTrades = ConditionalTrade::whereUserId($request->user()->id)->get();

        return response()->json($conditionalTrades);
    }
}