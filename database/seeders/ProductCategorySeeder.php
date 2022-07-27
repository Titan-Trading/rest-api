<?php

namespace Database\Seeders;

use App\Models\Marketplace\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productCategories = [
            'Market Makers',
            'Limit',
            'Arbitrage'
        ];

        foreach($productCategories as $categoryName) {
            $productCategory = ProductCategory::whereName($categoryName)->first();
            if(!$productCategory) {
                $productCategory = new ProductCategory();
                $productCategory->name = $categoryName;
                $productCategory->save();
            }
        }
    }
}
