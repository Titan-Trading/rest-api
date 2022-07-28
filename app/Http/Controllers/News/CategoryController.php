<?php

namespace App\Http\Controllers\News;

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
}