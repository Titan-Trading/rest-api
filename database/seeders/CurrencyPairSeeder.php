<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyType;
use App\Models\Exchange;
use App\Models\Symbol;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CurrencyPairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // currency types
        $currencyTypesData = [
            'fiat',
            'crypto',
            'stock'
        ];
        foreach($currencyTypesData as $type) {
            $currencyType = CurrencyType::whereName($type)->first();
            if(!$currencyType) {
                $currencyType = new CurrencyType();
                $currencyType->name = $type;
                $currencyType->save();
            }
        }

        // currencies
        $currenciesData = [
            'USD' => 'fiat',
            'USDT' => 'crypto',
            'BTC' => 'crypto',
            'ETH' => 'crypto',
            'TSLA' => 'stock'
        ];
        foreach($currenciesData as $name => $type) {
            $currencyType = CurrencyType::whereName($type)->first();
            if(!$currencyType) {
                continue;
            }
            
            $currency = Currency::whereTypeId($currencyType->id)->whereName($name)->first();
            if(!$currency) {
                $currency = new Currency();
                $currency->type_id = $currencyType->id;
                $currency->name = $name;
                $currency->save();
            }
        }

        // symbols
        $symbolsData = [
            'USDT' => 'BTC',
            'USDT' => 'ETH',
            'BTC' => 'ETH'
        ];
        foreach($symbolsData as $base => $target) {
            $baseCurrency = Currency::whereName($base)->first();
            if(!$baseCurrency) {
                continue;
            }

            $targetCurrency = Currency::whereName($target)->first();
            if(!$targetCurrency) {
                continue;
            }

            $symbol = Symbol::whereBaseCurrencyId($baseCurrency->id)
                ->whereTargetCurrencyId($targetCurrency->id)
                ->first();
            if(!$symbol) {
                $symbol = new Symbol();
                $symbol->base_currency_id = $baseCurrency->id;
                $symbol->target_currency_id = $targetCurrency->id;
                $symbol->save();
            }
        }

        // symbols on a given exchange
        $exchangeSymbolsData = [
            'KuCoin' => [
                'USDT' => 'BTC',
                'USDT' => 'ETH',
                'BTC' => 'ETH'
            ]
        ];
        foreach($exchangeSymbolsData as $exchangeName => $symbols) {
            
            $exchange = Exchange::whereName($exchangeName)->first();
            if(!$exchange) {
                continue;
            }

            $exchangeSymbols = [];
            foreach($symbols as $base => $target) {
                
                $baseCurrency = Currency::whereName($base)->first();
                if(!$baseCurrency) {
                    continue;
                }

                $targetCurrency = Currency::whereName($target)->first();
                if(!$targetCurrency) {
                    continue;
                }

                $symbol = Symbol::whereBaseCurrencyId($baseCurrency->id)
                    ->whereTargetCurrencyId($targetCurrency->id)
                    ->first();
                if(!$symbol) {
                    continue;
                }

                // adding supported symbols to an exchange
                $exchangeSymbols[$symbol->id] = [
                    'symbol_id' => $symbol->id,
                    'exchange_id' => $exchange->id,
                    'is_active' => true,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now()
                ];                
            }

            $exchange->symbols()->sync($exchangeSymbols);
        }
    }
}
