<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Exchange;
use App\Models\Trading\Symbol;
use Illuminate\Http\Request;
use App\Services\InfluxDB;
use App\Services\MessageBus;
use Illuminate\Support\Str;
use Exception;

class ExchangeController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Exchange list (system wide)
     */
    public function index(Request $request)
    {
        $query = Exchange::select('id', 'name', 'website_url', 'is_active', 'is_dex', 'symbol_template')
            ->with(['supportedMarketTypes' => function($q) {
                $q->select('market_types.id', 'market_types.name');
            }]);

        if($request->is_active) {
            $query->whereIsActive(true);
        }

        $exchanges = $query->get();

        return response()->json($exchanges);
    }

    /**
     * Update an exchange
     */
    public function update(Request $request, $id)
    {
        $exchange = Exchange::find($id);
        if(!$exchange) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'website_url' => 'required|string',
            'is_active' => 'required|boolean',
            'is_dex' => 'required|boolean',
            'symbol_template' => 'required|string'
        ]);

        $exchange->website_url = $request->website_url;
        $exchange->is_active = $request->is_active;
        $exchange->is_dex = $request->is_dex;
        $exchange->symbol_template = $request->symbol_template;
        $exchange->save();

        /**
         * Update exchange onto message bus
         */

        $this->messageBus->sendMessage('exchanges', [
            'topic' => 'exchanges',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'UPDATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $exchange->toArray()
        ]);

        return response()->json($exchange);
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
     * Add symbol to an exchange
     */
    public function addSymbol(Request $request, $exchangeId, $symbolId)
    {
        $exchange = Exchange::find($exchangeId);
        if(!$exchange) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $symbol = Symbol::find($symbolId);
        if(!$symbol) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $symbolFound = Symbol::whereId($request->symbol_id)->whereHas('exchanges', function($q) use ($exchangeId) {
            $q->where('exchanges.id', $exchangeId)->where('exchange_symbol.is_active', true);
        })->first();
        if($symbolFound) {
            return response()->json([
                'message' => 'Exchange already has symbol'
            ], 422);
        }

        $exchange->symbols()->attach($symbol);

        /**
         * Add symbol to exchange onto message bus
         */

        $this->messageBus->sendMessage('exchanges', [
            'topic' => 'exchanges',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'SYMBOL_ADDED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $exchange->toArray()
        ]);

        return response()->json([]);
    }

    /**
     * Remove symbol from an exchange
     */
    public function removeSymbol(Request $request, $exchangeId, $symbolId)
    {
        $exchange = Exchange::find($exchangeId);
        if(!$exchange) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $symbol = Symbol::find($symbolId);
        if(!$symbol) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $symbolFound = Symbol::whereId($symbolId)->whereHas('exchanges', function($q) use ($exchangeId) {
            $q->where('exchanges.id', $exchangeId)->where('exchange_symbol.is_active', true);
        })->first();
        if(!$symbolFound) {
            return response()->json([
                'message' => 'Exchange does not have symbol'
            ], 422);
        }

        $exchange->symbols()->detach($symbol);

        /**
         * Add symbol to exchange onto message bus
         */

        $this->messageBus->sendMessage('exchanges', [
            'topic' => 'exchanges',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'SYMBOL_REMOVED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $exchange->toArray()
        ]);

        return response()->json([]);
    }

    /**
     * Get historical market data from an exchange
     */
    public function historicalData(Request $request, $exchangeId)
    {
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

        $exchange = Exchange::find($exchangeId);
        if(!$exchange) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

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