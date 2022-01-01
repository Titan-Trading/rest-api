<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\MarketIndicator;
use Illuminate\Http\Request;

class MarketIndicatorController extends Controller
{
    /**
     * List market indicators
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $indicators = MarketIndicator::query()->get();

        return response()->json($indicators, 200);
    }

    /**
     * Create a market indicator
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:market_indicators,name',
            'is_active' => 'required'
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name is not unique',
            'is_active' => 'Is active is required'
        ]);

        $indicator = new MarketIndicator();
        $indicator->name = $request->name;
        $indicator->is_active = $request->is_active;
        $indicator->save();

        return response()->json($indicator, 201);
    }

    /**
     * Get a market indicator by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Indicator id is required'
            ], 404);
        }

        $indicator = MarketIndicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Indicator not found'
            ], 404);
        }

        return response()->json($indicator, 200);
    }

    /**
     * Update a market indicator by id
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Indicator id is required'
            ], 404);
        }

        $indicator = MarketIndicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Indicator not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($request->name == $indicator->name) {
            $nameRules[] = 'unique:market_indicators,name';
        }

        $this->validate($request, [
            'name' => $nameRules,
            'is_active' => 'required'
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name is not unique',
            'is_active_required' => 'Is active is required'
        ]);

        return response()->json($indicator, 200);
    }

    /**
     * Delete a market indicator by id
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Indicator id is required'
            ], 404);
        }

        $indicator = MarketIndicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Indicator not found'
            ], 404);
        }

        $indicator->delete();

        return response('Success', 200);
    }
}