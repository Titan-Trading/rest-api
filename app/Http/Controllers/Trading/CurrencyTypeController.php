<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\CurrencyType;
use Illuminate\Http\Request;

class CurrencyTypeController extends Controller
{
    /**
     * Currency type list (system-wide)
     */
    public function index(Request $request)
    {
        $currencyTypes = CurrencyType::all();

        return response()->json($currencyTypes, 200);
    }
}
