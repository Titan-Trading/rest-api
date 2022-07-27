<?php

namespace Database\Seeders;

use App\Models\Trading\ExchangeAccount;
use App\Models\Trading\Exchange;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExchangeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $connectedExchangesData = [
            /*[
                'exchange'           => 'KuCoin',
                'user'               => 'admin@simpletrader.com',
                'api_key'            => '',
                'api_key_secret'     => '',
                'wallet_private_key' => ''
            ],*/
        ];

        foreach($connectedExchangesData as $connectedExchangeData) {
            $user = User::whereEmail($connectedExchangeData['user'])->first();
            if(!$user) {
                continue;
            }

            $exchange = Exchange::whereName($connectedExchangeData['exchange'])->first();
            if(!$exchange) {
                continue;
            }

            $connectedExchange = ExchangeAccount::whereApiKey($connectedExchangeData['api_key'])->first();
            if(!$connectedExchange) {
                $connectedExchange = new ExchangeAccount();
                $connectedExchange->api_key = $connectedExchangeData['api_key'];
            }

            $connectedExchange->user_id            = $user->id;
            $connectedExchange->exchange_id        = $exchange->id;
            $connectedExchange->api_key_secret     = $connectedExchangeData['api_key_secret'];
            $connectedExchange->wallet_private_key = $connectedExchangeData['wallet_private_key'];
            $connectedExchange->save();
        }
    }
}
