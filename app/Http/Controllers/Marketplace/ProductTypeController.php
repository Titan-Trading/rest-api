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
        $productTypes = ProductType::query();

        $productTypes = $productTypes->get();

        return response()->json($productTypes);
    }
}