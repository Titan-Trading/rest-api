<?php

namespace Database\Seeders;

use App\Models\Marketplace\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productTypes = [
            'Trade Strategies', // a bot
            'Trade Signal', // a signal of when to buy or sell
            'Indicators' // a market indicator to indicate some state of the market
        ];

        foreach($productTypes as $productTypeName) {
            $productType = ProductType::whereName($productTypeName)->first();
            if(!$productType) {
                $productType = new ProductType();
                $productType->name = $productTypeName;
                $productType->save();
            }
        }
    }
}
