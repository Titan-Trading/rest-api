<?php

namespace App\Http\Controllers\Marketplace;

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
        $categories = ProductCategory::query();

        $categories = $categories->get();

        return response()->json($categories);
    }
}