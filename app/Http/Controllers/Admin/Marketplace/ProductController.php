<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get list of products
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // search by categories
        if($request->has('categories') && count($request->categories)) {
            $categories = $request->categories;

            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('name', $categories);
            });
        }

        // search by seller account
        if($request->has('seller_id') && $request->seller_id) {
            $query->whereSellerId($request->seller_id);
        }

        $products = $query->get();

        return response()->json($products);
    }

    /**
     * Create a product
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'seller_id' => ['required', 'exists:seller_accounts,id'],
            'featured_image_id' => ['exists:images,id'],
            'name' => ['required'],
            'description' => ['required'],
            'quantity' => ['integer']
        ], [
            'seller_id_required' => 'Seller id is required',
            'seller_id_exists' => 'Seller account must exist',
            'featured_image_id_exists' => 'Featured image must exist',
            'name_required' => 'Name is required',
            'description_required' => 'Description is required',
            'quantity_integer' => 'Quantity must be an integer'
        ]);

        $product = new Product();
        $product->seller_id = $request->seller_id;
        $product->sellable_type = $request->sellable_type ? $request->sellable_type : null;
        $product->sellable_id = $request->sellable_id ? $request->sellable_id : null;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->is_featured = $request->is_featured ? $request->is_featured : false;
        $product->status = 'pending_approval';
        $product->rating = 0.00;
        $product->quantity = $request->quantity ? $request->quantity : 0;
        $product->sold = 0;
        $product->save();

        // TODO: add product to categories

        return response()->json($product, 201);
    }

    /**
     * Get a product by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Update a product by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        if($product->seller()->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to this product'
            ], 403);
        }

        $this->validate($request, [
            'seller_id' => ['required', 'exists:seller_accounts,id'],
            'sellable_id' => ['integer'],
            'featured_image_id' => ['exists:images,id'],
            'name' => ['required'],
            'description' => ['required'],
            'quantity' => ['integer']
        ], [
            'seller_id_required' => 'Seller id is required',
            'seller_id_exists' => 'Seller account must exist',
            'sellable_id_integer' => 'Sellable id must an integer',
            'featured_image_id_exists' => 'Featured image must exist',
            'name_required' => 'Name is required',
            'description_required' => 'Description is required',
            'quantity_integer' => 'Quantity must be an integer'
        ]);

        $product = new Product();
        $product->seller_id = $request->seller_id;
        $product->sellable_type = $request->sellable_type ? $request->sellable_type : $product->sellable_type;
        $product->sellable_id = $request->sellable_id ? $request->sellable_id : $product->sellable_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->is_featured = $request->is_featured ? $request->is_featured : $product->is_featured;
        $product->status = $request->status ? $request->status : $product->status;
        $product->rating = $request->rating ? $request->rating : $product->rating;
        $product->quantity = $request->quantity ? $request->quantity : $product->quantity;
        $product->sold = $request->sold ? $request->sold : $product->sold;
        $product->save();

        // TODO: add product to categories

        return response()->json($product);
    }

    /**
     * Delete a product
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        if($product->seller()->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to this product'
            ], 403);
        }

        $product->delete();

        return response()->json($product);
    }
}