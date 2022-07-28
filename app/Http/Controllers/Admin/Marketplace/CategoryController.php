<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get list of product categories
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = ProductCategory::query();

        $categories = $query->get();

        return response()->json($categories);
    }

    /**
     * Create a new category
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:product_categories,name']
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $category = new ProductCategory();
        $category->name = $request->name;
        $category->save();
    }

    /**
     * Update a category by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);
        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // only use unique rule when name changes
        $nameRules = ['required'];
        if($request->name != $category->name) {
            $nameRules[] = 'unique:product_categories,name';
        }

        $this->validate($request, [
            'name' => $nameRules
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $category = new ProductCategory();
        $category->name = $request->name;
        $category->save();
    }

    /**
     * Delete a category by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $category = ProductCategory::find($id);
        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $category->delete();

        return response()->json($category);
    }
}