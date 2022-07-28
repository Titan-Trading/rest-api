<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Get list of news sources
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $sources = Source::query()->get();

        return response()->json($sources);
    }

    /**
     * Create a news source
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'logo_id' => ['required', 'exists:images,id'],
            'name' => ['required', 'unique:sources,name']
        ], [
            'logo_id_required' => 'Logo id is required',
            'logo_id_exists' => 'Logo image must exist',
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $source = new Source();
        $source->logo_id = $request->logo_id;
        $source->name = $request->name;
        $source->description = $request->description ? $request->description : null;
        $source->main_feed_id = $request->main_feed_id ? $request->main_feed_id : null;
        $source->website_url = $request->website_url ? $request->website_url : null;
        $source->save();

        return response()->json($source, 201);
    }

    /**
     * Get a news source by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $source = Source::find($id);
        if(!$source) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($source);
    }

    /**
     * Update a news source by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $source = Source::find($id);
        if(!$source) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($source->name != $request->name)
        {
            $nameRules[] = 'unique:sources,name';
        }

        $this->validate($request, [
            'logo_id' => ['required', 'exists:images,id'],
            'name' => $nameRules
        ], [
            'logo_id_required' => 'Logo id is required',
            'logo_id_exists' => 'Logo image must exist',
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $source->logo_id = $request->logo_id;
        $source->name = $request->name;
        $source->description = $request->description ? $request->description : $source->description;
        $source->main_feed_id = $request->main_feed_id ? $request->main_feed_id : $source->main_feed_id;
        $source->website_url = $request->website_url ? $request->website_url : $source->website_url;
        $source->save();

        return response()->json($source);
    }

    /**
     * Delete a news source by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $source = Source::find($id);
        if(!$source) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $source->delete();

        return response()->json($source);
    }
}