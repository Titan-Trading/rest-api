<?php

namespace App\Http\Controllers\Admin\News;

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
     * Create an news author
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'source_id' => ['required', 'exists:sources,id'],
            'name' => ['required', 'unique:authors,name']
        ], [
            'source_id_required' => 'Source id is required',
            'source_id_exists' => 'Source id must exist',
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $author = new Author();
        $author->source_id = $request->source_id;
        $author->name = $request->name;
        $author->save();

        return response()->json($author, 201);
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

    /**
     * Update a news author by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if(!$author) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($author->name != $request->name)
        {
            $nameRules[] = 'unique:authors,name';
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

        $author->source_id = $request->source_id;
        $author->name = $request->name;
        $author->save();

        return response()->json($author);
    }

    /**
     * Delete a news author by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $author = Author::find($id);
        if(!$author) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $author->delete();

        return response()->json($author);
    }
}