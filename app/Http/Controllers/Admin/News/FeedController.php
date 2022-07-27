<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News\Feed;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Get list of news feeds
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $feeds = Feed::query()->get();

        return response()->json($feeds);
    }

    /**
     * Create a news feed
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'source_id' => ['required', 'exists:sources,id'],
            'name' => ['required', 'unique:feeds,name']
        ], [
            'source_id_required' => 'Source id is required',
            'source_id_exists' => 'Source id must exist',
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $feed = new Feed();
        $feed->source_id = $request->source_id;
        $feed->name = $request->name;
        $feed->url = $request->url ? $request->url : null;
        $feed->save();

        return response()->json($feed, 201);
    }

    /**
     * Get a news feed by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $feed = Feed::find($id);
        if(!$feed) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($feed);
    }

    /**
     * Update a news feed by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $feed = Feed::find($id);
        if(!$feed) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($feed->name != $request->name)
        {
            $nameRules[] = 'unique:feeds,name';
        }

        $this->validate($request, [
            'source_id' => ['required', 'exists:sources,id'],
            'name' => $nameRules
        ], [
            'source_id_required' => 'Source id is required',
            'source_id_exists' => 'Source must exist',
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $feed->source_id = $request->source_id;
        $feed->name = $request->name;
        $feed->url = $request->url ? $request->url : $feed->url;
        $feed->save();

        return response()->json($feed);
    }

    /**
     * Delete a news feed by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $feed = Feed::find($id);
        if(!$feed) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $feed->delete();

        return response()->json($feed);
    }
}