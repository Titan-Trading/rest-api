<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Symbol;
use Illuminate\Http\Request;

class SymbolController extends Controller
{
    /**
     * Currency pair list (system-wide supported or watched by trade condition or bot)
     */
    public function index(Request $request)
    {
        $query = Symbol::select('id', 'base_currency_id', 'target_currency_id')
            ->with([
                'baseCurrency' => function($q) {
                    $q->select('id', 'name');
                },
                'targetCurrency' => function($q) {
                    $q->select('id', 'name');
                },
                'exchanges' => function($q) {
                    $q->select('exchange_symbol.exchange_id as id', 'name');
                }
            ]);

        // (FUTURE) watched currency pairs (ones pertaining to an active conditional trade or bot)
        if($request->has('watched') && $request->watched) {

        }

        $symbols = $query->get();

        return response()->json($symbols);
    }

    /**
     * Currency pair details by id
     */
    public function show(Request $request, $id)
    {
        $symbol = Symbol::whereId($id)->with([
            'exchanges' => function($q) {
                $q->select('exchanges.id', 'name');
            },
            'baseCurrency' => function($q) {
                $q->select('id', 'name');
            },
            'targetCurrency' => function($q) {
                $q->select('id', 'name');
            }
        ])->first();
        if(!$symbol) {
            return response()->json([
                'message' => 'Symbol not found'
            ], 404);
        }

        return response()->json($symbol);
    }
}