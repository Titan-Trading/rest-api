<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\ExchangeKlineData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExchangeDataController extends Controller
{
    /**
     * Get exchange dataset row list (system-wide)
     */
    public function index(Request $request)
    {
        $query = ExchangeKlineData::query();

        // search by exchange id
        if($request->has('exchange_id')) {
            $query->whereExchangeId($request->exchange_id);
        }
        // search by symbol id
        if($request->has('symbol_id')) {
            $query->whereSymbolId($request->symbol_id);
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

        $datasetRows = $query->orderBy('timestamp', 'asc')->get();

        return response()->json($datasetRows);
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
            'date_required' => 'Date is required'
        ]);

        $datasetRow = new ExchangeKlineData();
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

        return response()->json($datasetRow, 201);
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
