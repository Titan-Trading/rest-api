<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Exchange;
use App\Models\Trading\ExchangeKlineData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExchangeDataController extends Controller
{
    /**
     * Get exchange dataset row list (system-wide)
     */
    public function index(Request $request)
    {
        $query = ExchangeKlineData::query();

        $currentPage = $request->page;
        if(!$request->has('page')) {
            $currentPage = 1;
        }

        // search by exchange id
        if($request->has('exchange_id')) {
            $query->whereExchangeId($request->exchange_id);
        }

        // search by symbol id or by symbol base and target currency
        if($request->has('symbol_id')) {
            $query->whereSymbolId($request->symbol_id);
        }
        else if($request->has('symbol')) {
            $exchange = Exchange::find($request->exchange_id);

            $targetCurrency = '';
            $baseCurrency = '';

            // get symbol template separator
            $symbolTemplate = str_replace('[target]', '', $exchange->symbol_template);
            $symbolTemplateSeparator = str_replace('[base]', '', $symbolTemplate);

            // check if target or base comes first
            $symbolParts = explode($symbolTemplateSeparator, $request->symbol);
            if(strpos($exchange->symbol_template, '[target]') === 0) {
                $targetCurrency = $symbolParts[0];
                $baseCurrency = $symbolParts[1];
            }
            else {
                $targetCurrency = $symbolParts[1];
                $baseCurrency = $symbolParts[0];
            }

            $query->whereHas('symbol', function($q) use ($targetCurrency, $baseCurrency) {
                $q
                    ->whereHas('targetCurrency', function($q1) use ($targetCurrency) {
                        $q1->whereName($targetCurrency);
                    })
                    ->whereHas('baseCurrency', function($q1) use ($baseCurrency) {
                        $q1->whereName($baseCurrency);
                    });
            });
        }

        // search by interval
        if($request->has('interval')) {
            $query->whereInterval($request->interval);
        }
        // search by from date
        if($request->has('from_date')) {
            $query->where('timestamp', '>=', Carbon::parse($request->from_date)->timestamp);
        }
        // search by to date
        if($request->has('to_date')) {
            $query->where('timestamp', '<=', Carbon::parse($request->to_date)->timestamp);
        }

        $datasetRows = $query->orderBy('timestamp', 'asc')->paginate(1000)->toArray();
        if(!$datasetRows['total']) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'per_page'     => $datasetRows['per_page'],
                    'current_page' => 1,
                    'last_page'    => 1,
                    'total'        => 0
                ]
            ]);
        }

        return response()->json([
            'data' => $datasetRows['data'],
            'meta' => [
                'per_page'     => $datasetRows['per_page'],
                'current_page' => (int) $currentPage,
                'last_page'    => $datasetRows['last_page'],
                'total'        => $datasetRows['total']
            ]
        ]);
    }

    /**
     * Create a new exchange dataset row
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data' => ['required', 'array', 'min:1'],
            'data.*.creator_id' => ['required', 'exists:users,id'],
            'data.*.exchange_dataset_id' => ['required', 'exists:exchange_datasets,id'],
            'data.*.exchange_id' => ['required', 'exists:exchanges,id'],
            'data.*.symbol_id' => ['required', 'exists:symbols,id'],
            'data.*.interval' => ['required'],
            'data.*.open' => ['required'],
            'data.*.high' => ['required'],
            'data.*.low' => ['required'],
            'data.*.close' => ['required'],
            'data.*.volume' => ['required'],
            'data.*.base_volume' => ['required'],
            'data.*.timestamp' => ['required'],
            'data.*.date' => ['required']
        ], [
            'data.required' => 'Data is required',
            'data.array' => 'Data must be an array',
            'data.min' => 'Data must have at least one item',
            'data.*.creator_id.required' => 'Creator id is required',
            'data.*.creator_id.exists' => 'Creator must exist',
            'data.*.exchange_dataset_id.required' => 'Exchange dataset id is required',
            'data.*.exchange_dataset_id.exists' => 'Exchange dataset must exist',
            'data.*.exchange_id.required' => 'Exchange id is required',
            'data.*.exchange_id.exists' => 'Exchange must exist',
            'data.*.symbol_id.required' => 'Symbol id is required',
            'data.*.symbol_id.exists' => 'Symbol id must exist',
            'data.*.interval_required' => 'Interval is required',
            'data.*.open.required' => 'Open is required',
            'data.*.high.required' => 'High is required',
            'data.*.low.required' => 'Low is required',
            'data.*.close.required' => 'Close is required',
            'data.*.volume.required' => 'Volume is required',
            'data.*.base_volume.required' => 'Base volume is required',
            'data.*.timestamp.required' => 'Timestamp is required',
            'data.*.date.required' => 'Date is required'
        ]);
        
        $inserted = ExchangeKlineData::insert($request->data);

        return response()->json([
            'inserted' => $inserted
        ], 201);
    }

    /**
     * Update dataset row by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $datasetRow = ExchangeKlineData::find($id);
        if(!$datasetRow) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'exchange_dataset_id' => ['required', 'exists:exchange_datasets,id'],
            'exchange_id' => ['required', 'exists:exchanges,id'],
            'symbol_id' => ['required', 'exists:symbols,id'],
            'interval' => ['required'],
            'open' => ['required'],
            'high' => ['required'],
            'low' => ['required'],
            'close' => ['required'],
            'volume' => ['required'],
            'base_volume' => ['required'],
            'timestamp' => ['required'],
            'date' => ['required']
        ], [
            'exchange_dataset_id_required' => 'Exchange dataset id is required',
            'exchange_dataset_id_exists' => 'Exchange dataset must exist',
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange must exist',
            'symbol_id_required' => 'Symbol id is required',
            'symbol_id_exists' => 'Symbol id must exist',
            'interval_required' => 'Interval is required',
            'open_required' => 'Open is required',
            'high_required' => 'High is required',
            'low_required' => 'Low is required',
            'close_required' => 'Close is required',
            'volume_required' => 'Volume is required',
            'base_volume_required' => 'Base volume is required',
            'timestamp_required' => 'Timestamp is required',
            'date_required' => 'Date is required',
        ]);

        $datasetRow->creator_id = $request->user()->id;
        $datasetRow->exchange_dataset_id = $request->exchange_dataset_id;
        $datasetRow->exchange_id = $request->exchange_id;
        $datasetRow->symbol_id = $request->symbol_id;
        $datasetRow->interval = $request->interval;
        $datasetRow->open = $request->open;
        $datasetRow->high = $request->high;
        $datasetRow->low = $request->low;
        $datasetRow->close = $request->close;
        $datasetRow->volume = $request->volume;
        $datasetRow->base_volume = $request->base_volume;
        $datasetRow->timestamp = $request->timestamp;
        $datasetRow->date = $request->date;
        $datasetRow->save();

        // increment dataset period count
        $datasetRow->dataset()->increment('periods');

        return response()->json($datasetRow);
    }

    /**
     * Delete exchange dataset row by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $datasetRow = ExchangeKlineData::find($id);
        if(!$datasetRow) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $datasetRow->delete();

        return response()->json($datasetRow);
    }
}
