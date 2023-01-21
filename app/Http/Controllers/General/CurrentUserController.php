<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    /**
     * Get balance for the current user
     *
     * @param Request $request
     * @return void
     */
    public function getBalance(Request $request)
    {
        $balance = $request->user()->balance;
        
        return response()->json([
            'balance' => floatval($balance)
        ]);
    }
}