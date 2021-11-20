<?php

namespace Database\Seeders;

use App\Models\MarketIndicator;
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
            ConnectedExchangeSeeder::class,
            MarketIndicatorSeeder::class,
            NewsSourceSeeder::class,
        ]);
    }
}
