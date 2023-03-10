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
            ExchangeSeeder::class,
            
            // General
            ImageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ApiKeySeeder::class,

            // Trading
            CurrencyPairSeeder::class,
            ExchangeAccountSeeder::class,
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
