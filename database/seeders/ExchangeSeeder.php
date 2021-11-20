<?php

namespace Database\Seeders;

use App\Models\Exchange;
use App\Models\Image;
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
        $exchangesData = [
            [
                'name'        => 'KuCoin',
                'website_url' => 'https://www.kucoin.com',
                'image_name'  => '',
                'is_active'   => true,
                'is_dex'      => false
            ],
        ];

        foreach($exchangesData as $exchangeData) {
            $image = Image::whereName($exchangeData['image_name'])->first();
            if(!$image) {
                continue;
            }
            
            $exchange = Exchange::whereName($exchangeData['name'])->first();
            if(!$exchange) {
                $exchange = new Exchange();
                $exchange->name = $exchangeData['name'];
            }

            $exchange->website_url = $exchangeData['website_url'];
            $exchange->image_url   = $exchangeData['image_url'];
            $exchange->is_active   = $exchangeData['is_active'];
            $exchange->is_dex      = $exchangeData['is_dex'];
            $exchange->save();
        }
    }
}
