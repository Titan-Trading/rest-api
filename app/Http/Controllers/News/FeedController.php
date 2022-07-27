<?php

namespace App\Http\Controllers\News;

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
}