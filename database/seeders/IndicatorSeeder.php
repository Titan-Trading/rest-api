<?php

namespace Database\Seeders;

use App\Models\Trading\Indicator;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
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
            $marketIndicator = Indicator::whereName($marketIndicatorData['name'])->first();
            if(!$marketIndicator) {
                $marketIndicator = new Indicator();
                $marketIndicator->name = $marketIndicatorData['name'];
            }

            $marketIndicator->is_active = $marketIndicatorData['is_active'];
            $marketIndicator->save();
        }
    }
}
