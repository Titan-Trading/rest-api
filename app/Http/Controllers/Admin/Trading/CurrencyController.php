<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Currencies list (system-wide)
     */
    public function index(Request $request)
    {
        $currencies = Currency::with(['type' => function($q) {
            $q->select('currency_types.id', 'name');
        }])->get();

        return response()->json($currencies);
    }
    
    /**
     * Add a new currency
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type_id' => ['required', 'integer', 'exists:currency_types,id'],
            'name' => ['required', 'string', 'unique:currencies,name,NULL,id,deleted_at,NULL'],
        ]);

        // if record found and soft deleted, then restore
        $deletedCurrency = Currency::onlyTrashed()->whereName($request->name)->first();
        if($deletedCurrency) {
            $deletedCurrency->restore();

            return response()->json($deletedCurrency, 201);
        }

        $currency = new Currency();
        $currency->type_id = $request->type_id;
        $currency->name = $request->name;
        $currency->save();

        return response()->json($currency, 201);
    }

    /**
     * Remove a currency by ID
     */
    public function delete(Request $request, $id)
    {
        $currency = Currency::find($id);
        if(!$currency) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $currency->delete();

        return response()->json($currency);
    }
}
