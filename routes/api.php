<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Authentication (login, log out, refresh access token, password reset, etc.)
 */
Route::post('/auth/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/auth/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Route::post('/auth/refresh', 'App\Http\Controllers\Auth\LoginController@refresh');

Route::middleware(['auth'])->group(function() {
    /**
     * Currency types (CRUD)
     */
    Route::get('/currency-types', 'App\Http\Controllers\Trading\CurrencyTypeController@index');

    /**
     * Currencies system-wide (CRUD)
     */
    Route::get('/currencies', 'App\Http\Controllers\Trading\CurrencyController@index');
    Route::post('/currencies', 'App\Http\Controllers\Trading\CurrencyController@store');
    Route::delete('/currencies/{id}', 'App\Http\Controllers\Trading\CurrencyController@delete');

    /**
     * Symbols system-wide (CRUD)
     */
    Route::get('/symbols', 'App\Http\Controllers\Trading\SymbolController@index');
    Route::post('/symbols', 'App\Http\Controllers\Trading\SymbolController@store');
    Route::get('/symbols/{id}', 'App\Http\Controllers\Trading\SymbolController@show');
    Route::put('/symbols/{id}', 'App\Http\Controllers\Trading\SymbolController@update');
    Route::delete('/symbols/{id}', 'App\Http\Controllers\Trading\SymbolController@delete');

    /**
     * Market indicators (CRUD, algorithm script)
     */
    Route::get('/indicators', 'App\Http\Controllers\Trading\MarketIndicatorController@index');
    Route::post('/indicators', 'App\Http\Controllers\Trading\MarketIndicatorController@store');
    Route::get('/indicators/{id}', 'App\Http\Controllers\Trading\MarketIndicatorController@show');
    Route::put('/indicators/{id}', 'App\Http\Controllers\Trading\MarketIndicatorController@update');
    Route::delete('/indicators/{id}', 'App\Http\Controllers\Trading\MarketIndicatorController@delete');

    /**
     * Market types (CRUD)
     */
    Route::get('/market-types', 'App\Http\Controllers\Trading\MarketTypeController@index');

    /**
     * Exchanges system-wide, symbols, etc.
     */
    Route::get('/exchanges', 'App\Http\Controllers\Trading\ExchangeController@index');
    Route::put('/exchanges/{id}', 'App\Http\Controllers\Trading\ExchangeController@update');
    Route::get('/exchanges/{id}/symbols', 'App\Http\Controllers\Trading\ExchangeController@getSymbols');
    Route::post('/exchanges/{id}/symbols/{symbolId}', 'App\Http\Controllers\Trading\ExchangeController@addSymbol');
    // Route::put('/exchanges/{id}/symbols/{symbolId}', 'App\Http\Controllers\Trading\ExchangeController@updateSymbol');
    Route::delete('/exchanges/{id}/symbols/{symbolId}', 'App\Http\Controllers\Trading\ExchangeController@removeSymbol');
    Route::get('/exchanges/{id}/historical-data', 'App\Http\Controllers\Trading\ExchangeController@historicalData');

    /**
     * Images (CRUD)
     */
    Route::get('/images', 'App\Http\Controllers\ImageController@index');
    Route::post('/images', 'App\Http\Controllers\ImageController@store');
    Route::put('/images/{id}', 'App\Http\Controllers\ImageController@update');
    Route::delete('/images/{id}', 'App\Http\Controllers\ImageController@delete');

    /**
     * Teams (CRUD)
     */
    // Route::get('/teams', 'App\Http\Controllers\TeamController@index');
    // Route::post('/teams', 'App\Http\Controllers\TeamController@store');
    // Route::get('/teams/{id}', 'App\Http\Controllers\TeamController@show');
    // Route::put('/teams/{id}', 'App\Http\Controllers\TeamController@update');
    // Route::delete('/teams/{id}', 'App\Http\Controllers\TeamController@delete');

    /**
     * Users (CRUD, roles, permission, api keys, connected exchanges, etc)
     */
    Route::get('/users', 'App\Http\Controllers\UserController@index');
    Route::post('/users', 'App\Http\Controllers\UserController@store');
    Route::get('/users/{id}', 'App\Http\Controllers\UserController@show');
    Route::put('/users/{id}', 'App\Http\Controllers\UserController@update');
    Route::delete('/users/{id}', 'App\Http\Controllers\UserController@delete');

    Route::get('/api-keys', 'App\Http\Controllers\ApiKeyController@index');
    Route::post('/api-keys', 'App\Http\Controllers\ApiKeyController@store');
    Route::delete('/api-keys/{id}', 'App\Http\Controllers\ApiKeyController@delete');

    Route::get('/connected-exchanges', 'App\Http\Controllers\Trading\ConnectedExchangeController@index');
    Route::post('/connected-exchanges', 'App\Http\Controllers\Trading\ConnectedExchangeController@store');
    Route::put('/connected-exchanges/{id}', 'App\Http\Controllers\Trading\ConnectedExchangeController@update');
    Route::delete('/connected-exchanges/{id}', 'App\Http\Controllers\Trading\ConnectedExchangeController@delete');

    /**
     * Conditional trades (CRUD, conditions and condition operations)
     */
    Route::get('/conditional-trades', 'App\Http\Controllers\Trading\ConditionalTradeController@index');
    Route::post('/conditional-trades', 'App\Http\Controllers\Trading\ConditionalTradeController@store');
    Route::get('/conditional-trades/{id}', 'App\Http\Controllers\Trading\ConditionalTradeController@show');
    Route::put('/conditional-trades/{id}', 'App\Http\Controllers\Trading\ConditionalTradeController@update');
    Route::delete('/conditional-trades/{id}', 'App\Http\Controllers\Trading\ConditionalTradeController@delete');

    /**
     * Bots and bot sessions (algorithm script, inputs, bot, exchange, back-test settings, etc.)
     */
    Route::get('/bots', 'App\Http\Controllers\Trading\BotController@index');
    Route::post('/bots', 'App\Http\Controllers\Trading\BotController@store');
    Route::get('/bots/sessions', 'App\Http\Controllers\Trading\BotSessionController@allActive');
    Route::get('/bots/{id}', 'App\Http\Controllers\Trading\BotController@show');
    Route::put('/bots/{id}', 'App\Http\Controllers\Trading\BotController@update');
    Route::delete('/bots/{id}', 'App\Http\Controllers\Trading\BotController@delete');
    Route::get('/bots/{botId}/sessions', 'App\Http\Controllers\Trading\BotSessionController@index');
    Route::post('/bots/{botId}/sessions', 'App\Http\Controllers\Trading\BotSessionController@store');
    Route::get('/bots/{botId}/sessions/{id}', 'App\Http\Controllers\Trading\BotSessionController@show');
    Route::put('/bots/{botId}/sessions/{id}', 'App\Http\Controllers\Trading\BotSessionController@update');
    Route::delete('/bots/{botId}/sessions/{id}', 'App\Http\Controllers\Trading\BotSessionController@delete');

    /**
     * Orders (CRUD, fills, open orders)
     */
    Route::get('/orders', 'App\Http\Controllers\Trading\OrderController@index');
    Route::post('/orders', 'App\Http\Controllers\Trading\OrderController@store');
    Route::get('/orders/{id}', 'App\Http\Controllers\Trading\OrderController@show');
    Route::put('/orders/{id}', 'App\Http\Controllers\Trading\OrderController@update');
    Route::delete('/orders/{id}', 'App\Http\Controllers\Trading\OrderController@delete');
    Route::get('/orders/{id}/fills', 'App\Http\Controllers\Trading\OrderController@getFills');
    Route::post('/orders/{id}/fills', 'App\Http\Controllers\Trading\OrderController@storeFill');
    Route::delete('/orders/{orderId}/fills/{id}', 'App\Http\Controllers\Trading\OrderController@deleteFill');

    /**
     * Event sourcing (resource, resource id, resource data before and after)
     */
    // Route::get('/events', 'App\Http\Controllers\EventSourceController@index');
    // Route::post('/events', 'App\Http\Controllers\EventSourceController@store');
    // Route::get('/events/{id}', 'App\Http\Controllers\EventSourceController@show');
    // Route::put('/events/{id}', 'App\Http\Controllers\EventSourceController@update');
    // Route::delete('/events/{id}', 'App\Http\Controllers\EventSourceController@delete');

    /**
     * News (authors, categories, sources, articles, analysis, etc.)
     */
    Route::get('/news/categories', 'App\Http\Controllers\News\CategoryController@index');
    Route::post('/news/categories', 'App\Http\Controllers\News\CategoryController@store');
    Route::get('/news/categories/{id}', 'App\Http\Controllers\News\CategoryController@show');
    Route::put('/news/categories/{id}', 'App\Http\Controllers\News\CategoryController@update');
    Route::delete('/news/categories/{id}', 'App\Http\Controllers\News\CategoryController@delete');

    Route::get('/news/sources', 'App\Http\Controllers\News\SourceController@index');
    Route::post('/news/sources', 'App\Http\Controllers\News\SourceController@store');
    Route::get('/news/sources/{id}', 'App\Http\Controllers\News\SourceController@show');
    Route::put('/news/sources/{id}', 'App\Http\Controllers\News\SourceController@update');
    Route::delete('/news/sources/{id}', 'App\Http\Controllers\News\SourceController@delete');

    Route::get('/news/authors', 'App\Http\Controllers\News\AuthorController@index');
    Route::post('/news/authors', 'App\Http\Controllers\News\AuthorController@store');
    Route::get('/news/authors/{id}', 'App\Http\Controllers\News\AuthorController@show');
    Route::put('/news/authors/{id}', 'App\Http\Controllers\News\AuthorController@update');
    Route::delete('/news/authors/{id}', 'App\Http\Controllers\News\AuthorController@delete');

    Route::get('/news/articles', 'App\Http\Controllers\News\ArticleController@index');
    Route::post('/news/articles', 'App\Http\Controllers\News\ArticleController@store');
    Route::get('/news/articles/{id}', 'App\Http\Controllers\News\ArticleController@show');
    Route::put('/news/articles/{id}', 'App\Http\Controllers\News\ArticleController@update');
    Route::delete('/news/articles/{id}', 'App\Http\Controllers\News\ArticleController@delete');
});
