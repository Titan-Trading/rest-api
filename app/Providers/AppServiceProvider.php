<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        Relation::morphMap([
            'user' => 'App\Models\User',
            'bot' => 'App\Models\Trading\Bot',
            'indicator' => 'App\Models\Trading\Indicator',
            // 'signal' => 'App\Models\Trading\Signal',
            'payment_processor' => 'App\Models\Marketing\PaymentProcessor'
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
