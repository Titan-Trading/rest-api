<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Exchange;
use App\Models\Trading\Symbol;
use Illuminate\Http\Request;
use App\Services\InfluxDB;
use Exception;

class ExchangeController extends Controller
{
    /**
     * Exchange list (system wide)
     */
    public function index(Request $request)
    {
        $query = Exchange::select('id', 'name', 'website_url', 'is_active', 'is_dex', 'symbol_template')
            ->with(['supportedMarketTypes' => function($q) {
                $q->select('market_types.id', 'market_types.name');
            }]);

        $exchanges = $query->get();

        return response()->json($exchanges);
    }

    /**
     * Get symbols for an exchange
     */
    public function getSymbols(Request $request, $exchangeId)
    {
        $exchange = Exchange::find($exchangeId);
        if(!$exchange) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $symbols = Symbol::select('id', 'base_currency_id', 'target_currency_id')
            ->whereHas('exchanges', function($q) use ($exchangeId) {
                $q->where('exchanges.id', $exchangeId)->where('exchange_symbol.is_active', true);
            })
            ->with([
                'baseCurrency' => function($q) {
                    $q->select('id', 'name');
                },
                'targetCurrency' => function($q) {
                    $q->select('id', 'name');
                }
            ])
            ->get();

        return response()->json($symbols);
    }

    /**
     * Get historical market data from an exchange
     */
    public function historicalData(Request $request, $exchangeId)
    {
        $exchange = Exchange::find($exchangeId);
        if(!$exchange) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        
        $this->validate($request, [
            'type' => 'required',
            'interval' => 'required',
            'aggregate_interval' => 'required',
            'symbol' => 'required'
        ], [
            'type_required' => 'A type is required',
            'interval_required' => 'An interval is required',
            'aggregate_interval_required' => 'An aggregate interval is required',
            'symbol_required' => 'A symbol is required'
        ]);

        $records = [];

        try {
            $queryString = 'from(bucket: "simple-trader")
                |> range(start: -7d, stop: now())
                |> filter(fn: (r) => r["_measurement"] == "' . $request->type . '")
                |> filter(fn: (r) => r["_field"] == "open" or r["_field"] == "close" or r["_field"] == "high" or r["_field"] == "low" or r["_field"] == "volume")
                |> filter(fn: (r) => r["exchange"] == "' . $exchange->name . '")
                |> filter(fn: (r) => r["interval"] == "' . $request->interval . '")
                |> filter(fn: (r) => r["symbol"] == "' . $request->symbol . '")
                |> aggregateWindow(every: ' . $request->aggregate_interval . ', fn: max, createEmpty: false)
                |> yield(name: "mean")';

            $influx = new InfluxDB();

            $results = $influx->query($queryString);

            if(!count($results)) {
                return response()->json([]);
            }

            $recordCount = count($results[0]->records);
            for($recordIndex = 0; $recordIndex < $recordCount; $recordIndex++) {
                $records[] = [
                    'open' => $results[0]->records[$recordIndex]->values['_value'],
                    'close' => $results[1]->records[$recordIndex]->values['_value'],
                    'high' => $results[2]->records[$recordIndex]->values['_value'],
                    'low' => $results[3]->records[$recordIndex]->values['_value'],
                    'volume' => $results[4]->records[$recordIndex]->values['_value'],
                    'timestamp' => $results[0]->records[$recordIndex]->values['_time']
                ];
            }
        }
        catch(Exception $ex) {
            return response()->json([]);
        }

        return response()->json($records);
    }
}