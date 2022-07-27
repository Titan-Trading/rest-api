<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Get list of news authors
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $authors = Author::query()->get();

        return response()->json($authors);
    }

    /**
     * Get a new author by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $author = Author::find($id);
        if(!$author) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($author);
    }
}