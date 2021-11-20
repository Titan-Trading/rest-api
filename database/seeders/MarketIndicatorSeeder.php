<?php

namespace Database\Seeders;

use App\Models\MarketIndicator;
use Illuminate\Database\Seeder;

class MarketIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marketIndicatorsData = [
            [
                'name'      => 'RSI',
                'is_active' => true
            ],
        ];

        foreach($marketIndicatorsData as $marketIndicatorData) {
            $marketIndicator = MarketIndicator::whereName($marketIndicatorData['name'])->first();
            if(!$marketIndicator) {
                $marketIndicator = new MarketIndicator();
                $marketIndicator->name = $marketIndicatorData['name'];
            }

            $marketIndicator->is_active = $marketIndicatorData['is_active'];
            $marketIndicator->save();
        }
    }
}
