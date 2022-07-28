<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get list of news categories
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $categories = Category::query()->get();

        return response()->json($categories);
    }

    /**
     * Create a news category
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:categories,name']
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return response()->json($category, 201);
    }

    /**
     * Get a news category by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($category);
    }

    /**
     * Update a news category by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($category->name != $request->name)
        {
            $nameRules[] = 'unique:categories,name';
        }

        $this->validate($request, [
            'name' => $nameRules
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $category->name = $request->name;
        $category->save();

        return response()->json($category);
    }

    /**
     * Delete a news category by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $category->delete();

        return response()->json($category);
    }
}