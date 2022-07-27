<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Trading
            ImageSeeder::class,
            UserSeeder::class,
            ApiKeySeeder::class,
            ExchangeSeeder::class,
            CurrencyPairSeeder::class,
            ConnectedExchangeSeeder::class,
            IndicatorSeeder::class,

            // News
            // SourceSeeder::class,

            // Marketplace
            PaymentProcessorTypeSeeder::class,
            PaymentProcessorSeeder::class,
            ProductCategorySeeder::class,
            ProductTypeSeeder::class,
            ProductPriceTypeSeeder::class,
            DiscountTypeSeeder::class
        ]);
    }
}
