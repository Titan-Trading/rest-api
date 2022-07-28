<?php

namespace App\Http\Controllers\News;

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
}