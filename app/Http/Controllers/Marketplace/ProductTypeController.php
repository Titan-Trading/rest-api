<?php

namespace App\Http\Controllers\Marketplace;

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
}