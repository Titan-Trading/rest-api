<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    /**
     * Get list of product types
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = ProductType::query();

        $productTypes = $query->get();

        return response()->json($productTypes);
    }

    /**
     * Create a new product type
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:product_types,name']
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $type = new ProductType();
        $type->name = $request->name;
        $type->save();
    }

    /**
     * Update a product type by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $type = ProductType::find($id);
        if(!$type) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // only use unique rule when name changes
        $nameRules = ['required'];
        if($request->name != $type->name) {
            $nameRules[] = 'unique:product_types,name';
        }

        $this->validate($request, [
            'name' => $nameRules
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $type = new ProductType();
        $type->name = $request->name;
        $type->save();
    }

    /**
     * Delete a product type by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $type = ProductType::find($id);
        if(!$type) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $type->delete();

        return response()->json($type);
    }
}