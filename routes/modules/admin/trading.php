<?php

use Illuminate\Support\Facades\Route;


/**
 * Trading api routes
 */
Route::group([
    'prefix' => 'trading',
    'namespace' => 'Trading'
], function () {
    /**
     * Currency types (CRUD)
     */
    Route::get('/currency-types', 'CurrencyTypeController@index');

    /**
     * Currencies system-wide (CRUD)
     */
    Route::get('/currencies', 'CurrencyController@index');
    Route::post('/currencies', 'CurrencyController@store');
    Route::delete('/currencies/{id}', 'CurrencyController@delete');

    /**
     * Symbols system-wide (CRUD)
     */
    Route::get('/symbols', 'SymbolController@index');
    Route::post('/symbols', 'SymbolController@store');
    Route::get('/symbols/{id}', 'SymbolController@show');
    Route::put('/symbols/{id}', 'SymbolController@update');
    Route::delete('/symbols/{id}', 'SymbolController@delete');

    /**
     * Market types (CRUD)
     */
    Route::get('/market-types', 'MarketTypeController@index');

    /**
     * Exchanges system-wide, symbols, etc.
     */
    Route::get('/exchanges', 'ExchangeController@index');
    Route::put('/exchanges/{id}', 'ExchangeController@update');
    Route::get('/exchanges/{id}/symbols', 'ExchangeController@getSymbols');
    Route::post('/exchanges/{id}/symbols/{symbolId}', 'ExchangeController@addSymbol');
    // Route::put('/exchanges/{id}/symbols/{symbolId}', 'ExchangeController@updateSymbol');
    Route::delete('/exchanges/{id}/symbols/{symbolId}', 'ExchangeController@removeSymbol');
    Route::get('/exchanges/{id}/historical-data', 'ExchangeController@historicalData');

    /**
     * Exchange accounts (details for using different exchanges to make trades)
     */
    Route::get('/exchange-accounts', 'ExchangeAccountController@index');
    Route::post('/exchange-accounts', 'ExchangeAccountController@store');
    Route::get('/exchange-accounts/{id}', 'ExchangeAccountController@show');
    Route::put('/exchange-accounts/{id}', 'ExchangeAccountController@update');
    Route::delete('/exchange-accounts/{id}', 'ExchangeAccountController@delete');

    /**
     * Market indicators (CRUD, algorithm script)
     */
    Route::get('/indicators', 'IndicatorController@index');
    Route::post('/indicators', 'IndicatorController@store');
    Route::get('/indicators/tests', 'IndicatorTestController@allActive');
    Route::get('/indicators/{id}', 'IndicatorController@show');
    Route::put('/indicators/{id}', 'IndicatorController@update');
    Route::delete('/indicators/{id}', 'IndicatorController@delete');

    /**
     * Indicator tests (mock indicator test on an exchange for a given user)
     */
    Route::get('/indicators/{indicatorId}/tests', 'IndicatorTestController@index');
    Route::post('/indicators/{indicatorId}/tests', 'IndicatorTestController@store');
    Route::get('/indicators/{indicatorId}/tests/{id}', 'IndicatorTestController@show');
    Route::put('/indicators/{indicatorId}/tests/{id}', 'IndicatorTestController@update');
    Route::delete('/indicators/{indicatorId}/tests/{id}', 'IndicatorTestController@delete');

    /**
     * Conditional trades (CRUD, conditions and condition operations)
     */
    Route::get('/conditional-trades', 'ConditionalTradeController@index');
    Route::post('/conditional-trades', 'ConditionalTradeController@store');
    Route::get('/conditional-trades/{id}', 'ConditionalTradeController@show');
    Route::put('/conditional-trades/{id}', 'ConditionalTradeController@update');
    Route::delete('/conditional-trades/{id}', 'ConditionalTradeController@delete');

    /**
     * Bots (algorithm script, inputs, exchange, back-test settings, etc.)
     */
    Route::get('/bots', 'BotController@index');
    Route::get('/bots/sessions', 'BotSessionController@allActive');
    Route::get('/bots/sessions/{id}', 'BotSessionController@show');
    Route::post('/bots', 'BotController@store');
    Route::get('/bots/{id}', 'BotController@show');
    Route::put('/bots/{id}', 'BotController@update');
    Route::delete('/bots/{id}', 'BotController@delete');

    /**
     * Bot sessions (trading sessions on an exchange for a given user)
     */
    Route::get('/bots/{botId}/sessions', 'BotSessionController@index');
    Route::post('/bots/{botId}/sessions', 'BotSessionController@store');
    Route::get('/bots/{botId}/sessions/{id}', 'BotSessionController@show');
    Route::put('/bots/{botId}/sessions/{id}', 'BotSessionController@update');
    Route::post('/bots/{botId}/sessions/{id}/resume', 'BotSessionController@resume');
    Route::post('/bots/{botId}/sessions/{id}/stop', 'BotSessionController@stop');
    Route::delete('/bots/{botId}/sessions/{id}', 'BotSessionController@delete');
    Route::post('/bots/{botId}/sessions/{id}/results', 'BotSessionResultController@store');
    Route::get('/bots/{botId}/sessions/{id}/results', 'BotSessionResultController@show');

    /**
     * Orders (CRUD, fills, open orders)
     */
    Route::get('/orders', 'OrderController@index');
    Route::post('/orders', 'OrderController@store');
    Route::get('/orders/{id}', 'OrderController@show');
    Route::put('/orders/{id}', 'OrderController@update');
    Route::delete('/orders/{id}', 'OrderController@delete');
    Route::get('/orders/{id}/fills', 'OrderController@getFills');
    Route::post('/orders/{id}/fills', 'OrderController@storeFill');
    Route::delete('/orders/{orderId}/fills/{id}', 'OrderController@deleteFill');

    /**
     * Datasets (a given partition of exchange data, for easily backtesting certain market conditions)
     */
    Route::get('/datasets', 'ExchangeDatasetController@index');
    Route::post('/datasets', 'ExchangeDatasetController@store');
    Route::put('/datasets/{id}', 'ExchangeDatasetController@update');
    Route::delete('/datasets/{id}', 'ExchangeDatasetController@delete');

    Route::get('/data', 'ExchangeDataController@index');
    Route::post('/data', 'ExchangeDataController@store');
    Route::put('/data/{id}', 'ExchangeDataController@update');
    Route::delete('/data/{id}', 'ExchangeDataController@delete');
});