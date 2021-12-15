<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return response()->json($symbols, 200);
    }

    /**
     * Add a new currency pair
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'base_currency_id' => 'required|integer|exists:currencies,id',
            'target_currency_id' => 'required|integer|exists:currencies,id',
        ]);

        $symbol = Symbol::whereBaseCurrencyId($request->base_currency_id)
            ->whereTargetCurrencyId($request->target_currency_id)
            ->first();
        if($symbol) {
            return response()->json([
                'message' => 'Symbol already exists'
            ], 422);
        }

        $symbol = new Symbol();
        $symbol->base_currency_id = $request->base_currency_id;
        $symbol->target_currency_id = $request->target_currency_id;
        $symbol->save();

        return response()->json($symbol, 201);
    }

    /**
     * Currency pair details by id
     */
    public function show(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Symbol id is required'
            ], 404);
        }

        $symbol = Symbol::whereId($id)->with(['exchanges' => function($q) {
            $q->select('exchanges.id', 'name');
        }])->first();
        if(!$symbol) {
            return response()->json([
                'message' => 'Symbol not found'
            ], 404);
        }

        return response()->json($symbol, 200);
    }

    /**
     * Update a currency pair 
     */
    public function update(Request $request, $id) 
    {
        if(!$id) {
            return response()->json([
                'message' => 'Symbol id is required'
            ], 404);
        }

        $symbol = Symbol::find($id);
        if(!$symbol) {
            return response()->json([
                'message' => 'Symbol not found'
            ], 404);
        }

        $this->validate($request, [
            'base_currency_id' => 'required|integer|exists:currencies,id',
            'target_currency_id' => 'required|integer|exists:currencies,id',
        ]);

        $foundSymbol = Symbol::whereBaseCurrencyId($request->base_currency_id)
            ->whereTargetCurrencyId($request->target_currency_id)
            ->first();
        if($foundSymbol) {
            return response()->json([
                'message' => 'Symbol already exists'
            ], 422);
        }

        $symbol->base_currency_id = $request->base_currency_id;
        $symbol->target_currency_id = $request->target_currency_id;
        $symbol->save();

        return response()->json($symbol, 200);
    }

    /**
     * Delete (soft delete) a currency pair
     */
    public function delete(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Symbol id is required'
            ], 404);
        }

        $symbol = Symbol::find($id);
        if(!$symbol) {
            return response()->json([
                'message' => 'Symbol not found'
            ], 404);
        }

        $symbol->delete();

        return response()->json([
            'message' => 'Symbol deleted successfully'
        ], 200);
    }
}