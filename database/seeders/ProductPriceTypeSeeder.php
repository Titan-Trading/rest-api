<?php

namespace Database\Seeders;

use App\Models\Marketplace\ProductPriceType;
use Illuminate\Database\Seeder;

class ProductPriceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productPriceTypes = [
            'free', // free
            'flat', // flat rate in dollars (one-time)
            'flat_monthly', // flat rate in dollars (monthly)
            'percent_monthly', // percent of trade (monthly)
            'percent_profit_monthly' // percent of profit (monthly)
        ];

        foreach($productPriceTypes as $productPriceTypeName) {
            $productPriceType = ProductPriceType::whereName($productPriceTypeName)->first();
            if(!$productPriceType) {
                $productPriceType = new ProductPriceType();
                $productPriceType->name = $productPriceTypeName;
                $productPriceType->save();
            }
        }
    }
}
