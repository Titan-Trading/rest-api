<?php

namespace Database\Seeders;

use App\Models\Trading\Exchange;
use App\Models\Image;
use App\Models\Trading\MarketType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExchangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Market types
         */
        $marketTypesData = [
            'crypto',
            'stock'
        ];

        foreach($marketTypesData as $name) {
            $exchangeType = MarketType::whereName($name)->first();
            if(!$exchangeType) {
                $exchangeType = new MarketType();
                $exchangeType->name = $name;
                $exchangeType->save();
            }
        }

        $exchangesData = [
            [
                'name'        => 'SimpleTrader',
                'website_url' => 'https://www.simpletrader.com',
                'image_name'  => '',
                'is_active'   => true,
                'is_dex'      => false,
                'symbol_template' => '[target]-[base]',
                'types'       => [
                    'stock',
                    'crypto'
                ]
            ],
            [
                'name'        => 'KuCoin',
                'website_url' => 'https://www.kucoin.com',
                'image_name'  => '',
                'is_active'   => true,
                'is_dex'      => false,
                'symbol_template' => '[target]-[base]',
                'types'       => [
                    'crypto'
                ]
            ],
        ];

        foreach($exchangesData as $exchangeData) {
            // get image record for logo
            $image = Image::whereName($exchangeData['image_name'])->first();
            if(!$image) {
                continue;
            }
            
            // create or update exchange
            $exchange = Exchange::whereName($exchangeData['name'])->first();
            if(!$exchange) {
                $exchange = new Exchange();
                $exchange->name = $exchangeData['name'];
            }
            $exchange->website_url = $exchangeData['website_url'];
            $exchange->logo_id     = $image->id;
            $exchange->is_active   = $exchangeData['is_active'];
            $exchange->is_dex      = $exchangeData['is_dex'];
            $exchange->symbol_template = $exchangeData['symbol_template'];
            $exchange->save();

            // adding supported types to an exchange
            $marketTypes = [];
            foreach($exchangeData['types'] as $type) {
                $marketType = MarketType::whereName($type)->first();
                if(!$marketType) {
                    continue;
                }

                $marketTypes[$marketType->id] = [
                    'market_type_id' => $marketType->id,
                    'exchange_id' => $exchange->id,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now()
                ];                
            }
            $exchange->supportedMarketTypes()->sync($marketTypes);
        }
    }
}
