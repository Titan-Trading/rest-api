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
            ImageSeeder::class,
            UserSeeder::class,
            ExchangeSeeder::class,
            CurrencyPairSeeder::class,
            ConnectedExchangeSeeder::class,
            MarketIndicatorSeeder::class,
            // NewsSourceSeeder::class,
        ]);
    }
}
