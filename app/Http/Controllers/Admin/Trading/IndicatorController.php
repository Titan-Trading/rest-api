<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Indicator;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    /**
     * Get list of indicators
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = Indicator::query();

        // search by indicator name
        if($request->has('search_text')) {
            $query->whereName('like', '%' . $request->search_text . '%');
        }
        
        $indicators = $query->get();

        return response()->json($indicators);
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
            'name' => ['required', 'unique:indicators,name'],
            'is_active' => ['required']
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name is not unique',
            'is_active' => 'Is active is required'
        ]);

        $indicator = new Indicator();
        $indicator->name = $request->name;
        $indicator->is_active = $request->is_active;
        $indicator->algorithm_text = $request->algorithm_text ? $request->algorithm_text : null;
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
        $indicator = Indicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($indicator);
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
        $indicator = Indicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($request->name == $indicator->name) {
            $nameRules[] = 'unique:indicators,name';
        }

        $this->validate($request, [
            'name' => $nameRules,
            'is_active' => 'required'
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name is not unique',
            'is_active_required' => 'Is active is required'
        ]);

        $indicator->name = $request->name;
        $indicator->is_active = $request->is_active;
        $indicator->algorithm_text = $request->algorithm_text ? $request->algorithm_text : $indicator->algorithm_text;
        $indicator->save();

        return response()->json($indicator);
    }

    /**
     * Delete a market indicator by id
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $indicator = Indicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $indicator->delete();

        return response()->json($indicator);
    }
}