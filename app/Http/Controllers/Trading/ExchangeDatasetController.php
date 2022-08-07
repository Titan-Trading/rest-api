<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\ExchangeDataset;
use Illuminate\Http\Request;

class ExchangeDatasetController extends Controller
{
    /**
     * Get exchange dataset list (system-wide)
     */
    public function index(Request $request)
    {
        $query = ExchangeDataset::query()->whereCreatorId($request->user()->id);

        // search by exchange id
        if($request->has('exchange_id') && $request->exchange_id) {
            $query->whereExchangeId($request->exchange_id);
        }
        // search by symbol id
        if($request->has('symbol_id') && $request->symbol_id) {
            $query->whereSymbolId($request->symbol_id);
        }

        $datasets = $query->get();

        return response()->json($datasets);
    }

    /**
     * Create a new exchange dataset
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'source' => ['required'],
            'exchange_id' => ['required', 'exists:exchanges,id'],
            'symbol_id' => ['required', 'exists:symbols,id'],
            'interval' => ['required'],
            'name' => ['required'],
            'started_at' => ['required'],
            'ended_at' => ['required']
        ], [
            'source_required' => 'Source is required',
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange must exist',
            'symbol_id_required' => 'Symbol id is required',
            'symbol_id_exists' => 'Symbol id must exist',
            'interval_required' => 'Interval is required',
            'name_required' => 'Name is required',
            'started_at_required' => 'Started at date is required',
            'ended_at_required' => 'Ended at date is requried'
        ]);

        $dataset = new ExchangeDataset();
        $dataset->creator_id = $request->user()->id;
        $dataset->exchange_id = $request->exchange_id;
        $dataset->symbol_id = $request->symbol_id;
        $dataset->interval = $request->interval;
        $dataset->source = $request->source;
        $dataset->name = $request->name;
        $dataset->periods = 0;
        $dataset->started_at = $request->started_at;
        $dataset->ended_at = $request->ended_at;
        $dataset->save();

        return response()->json($dataset, 201);
    }

    /**
     * Update dataset by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $dataset = ExchangeDataset::find($id);
        if(!$dataset) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'source' => ['required'],
            'exchange_id' => ['required', 'exists:exchanges,id'],
            'symbol_id' => ['required', 'exists:symbols,id'],
            'interval' => ['required'],
            'name' => ['required'],
            'started_at' => ['required'],
            'ended_at' => ['required']
        ], [
            'source_required' => 'Source is required',
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange must exist',
            'symbol_id_required' => 'Symbol id is required',
            'symbol_id_exists' => 'Symbol id must exist',
            'interval_required' => 'Interval is required',
            'name_required' => 'Name is required',
            'started_at_required' => 'Started at date is required',
            'ended_at_required' => 'Ended at date is requried'
        ]);

        // check if data was created by current user
        if($dataset->creator_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes on this exchange dataset'
            ], 403);
        }

        $dataset->exchange_id = $request->exchange_id;
        $dataset->symbol_id = $request->symbol_id;
        $dataset->interval = $request->interval;
        $dataset->source = $request->source;
        $dataset->name = $request->name;
        $dataset->started_at = $request->started_at;
        $dataset->ended_at = $request->ended_at;
        $dataset->save();

        return response()->json($dataset);
    }

    /**
     * Delete exchange dataset by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $dataset = ExchangeDataset::find($id);
        if(!$dataset) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if dataset was created by current user
        if($dataset->creator_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes on this exchange dataset'
            ], 403);
        }

        $dataset->delete();

        return response()->json($dataset);
    }
}
