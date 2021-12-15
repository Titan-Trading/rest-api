<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\ConnectedExchange;
use Illuminate\Http\Request;

class ConnectedExchangeController extends Controller
{
    /**
     * Connected exchange account list (system wide)
     */
    public function index(Request $request)
    {
        $query = ConnectedExchange::select('id', 'api_key', 'api_key_secret', 'wallet_private_key')
            ->with([
                'user' => function($q) {
                    $q->select('id', 'name', 'email');
                },
                'exchange' => function($q) {
                    $q->select('id', 'name');
                }
            ]);

        $connectedExchanges = $query->get();

        return response($connectedExchanges, 200);
    }
}