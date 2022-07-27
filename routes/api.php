<?php

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

/**
 * Api routes
 */
Route::middleware(['auth'])->group(function() {
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
     * Roles (create, update, index, view, assign permissions)
     */
    Route::get('/roles', 'App\Http\Controllers\RoleController@index');
    Route::post('/roles', 'App\Http\Controllers\RoleController@store');
    Route::get('/roles/{id}', 'App\Http\Controllers\RoleController@show');
    Route::put('/roles/{id}', 'App\Http\Controllers\RoleController@update');
    Route::delete('/roles/{id}', 'App\Http\Controllers\RoleController@delete');
    Route::post('/roles/{id}/permissions', 'App\Http\Controllers\RoleController@assignPermissions');

    /**
     * Permissions
     */
    Route::get('/permissions', 'App\Http\Controllers\PermissionController@index');

    /**
     * Users (user accounts)
     */
    Route::get('/users', 'App\Http\Controllers\UserController@index');
    Route::post('/users', 'App\Http\Controllers\UserController@store');
    Route::get('/users/{id}', 'App\Http\Controllers\UserController@show');
    Route::put('/users/{id}', 'App\Http\Controllers\UserController@update');
    Route::delete('/users/{id}', 'App\Http\Controllers\UserController@delete');

    /**
     * Api keys (used for external access)
     */
    Route::get('/api-keys', 'App\Http\Controllers\ApiKeyController@index');
    Route::post('/api-keys', 'App\Http\Controllers\ApiKeyController@store');
    Route::put('/api-keys/{id}', 'App\Http\Controllers\ApiKeyController@update');
    Route::delete('/api-keys/{id}', 'App\Http\Controllers\ApiKeyController@delete');

    /**
     * Event sourcing (resource, resource id, resource data before and after)
     */
    // Route::get('/events', 'App\Http\Controllers\EventSourceController@index');
    // Route::post('/events', 'App\Http\Controllers\EventSourceController@store');
    // Route::get('/events/{id}', 'App\Http\Controllers\EventSourceController@show');
    // Route::put('/events/{id}', 'App\Http\Controllers\EventSourceController@update');
    // Route::delete('/events/{id}', 'App\Http\Controllers\EventSourceController@delete');

    /**
     * Trading api routes
     */
    Route::group([
        'prefix' => 'trading',
        'namespace' => 'App\Http\Controllers\Trading'
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
         * Market indicators (CRUD, algorithm script)
         */
        Route::get('/indicators', 'IndicatorController@index');
        Route::post('/indicators', 'IndicatorController@store');
        Route::get('/indicators/{id}', 'IndicatorController@show');
        Route::put('/indicators/{id}', 'IndicatorController@update');
        Route::delete('/indicators/{id}', 'IndicatorController@delete');

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
        Route::get('/exchange_accounts', 'ExchangeAccountController@index');
        Route::post('/exchange_accounts', 'ExchangeAccountController@store');
        Route::put('/exchange_accounts/{id}', 'ExchangeAccountController@update');
        Route::delete('/exchange_accounts/{id}', 'ExchangeAccountController@delete');

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
        Route::put('/bots/{botId}/sessions/{id}/activate', 'BotSessionController@activate');
        Route::put('/bots/{botId}/sessions/{id}/deactivate', 'BotSessionController@deactivate');
        Route::delete('/bots/{botId}/sessions/{id}', 'BotSessionController@delete');

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
        Route::get('/datasets', 'DatasetController@index');
        Route::post('/datasets', 'DatasetController@store');
        Route::put('/datasets/{id}', 'DatasetController@update');
        Route::delete('/datasets/{id}', 'DatasetController@delete');
    });


    /**
     * News api routes (articles, signals, etc.)
     */
    Route::group([
        'prefix' => 'news',
        'namespace' => 'App\Http\Controllers\News'
    ], function() {
        /**
         * News article categories
         */
        Route::get('/categories', 'CategoryController@index');
        Route::post('/categories', 'CategoryController@store');
        Route::get('/categories/{id}', 'CategoryController@show');
        Route::put('/categories/{id}', 'CategoryController@update');
        Route::delete('/categories/{id}', 'CategoryController@delete');

        /**
         * News sources
         */
        Route::get('/sources', 'SourceController@index');
        Route::post('/sources', 'SourceController@store');
        Route::get('/sources/{id}', 'SourceController@show');
        Route::put('/sources/{id}', 'SourceController@update');
        Route::delete('/sources/{id}', 'SourceController@delete');

        /**
         * News article authors
         */
        Route::get('/authors', 'AuthorController@index');
        Route::post('/authors', 'AuthorController@store');
        Route::get('/authors/{id}', 'AuthorController@show');
        Route::put('/authors/{id}', 'AuthorController@update');
        Route::delete('/authors/{id}', 'AuthorController@delete');

        /**
         * News articles
         */
        Route::get('/articles', 'ArticleController@index');
        Route::post('/articles', 'ArticleController@store');
        Route::get('/articles/{id}', 'ArticleController@show');
        Route::put('/articles/{id}', 'ArticleController@update');
        Route::delete('/articles/{id}', 'ArticleController@delete');
    });


    /**
     * Marketplace api routes (product types, product use snapshots, products, product prices, product orders, product categories, order payments, payment processors, discount codes, etc.)
     */
    Route::group([
        'prefix' => 'marketplace',
        'namespace' => 'App\Http\Controllers\Marketplace'
    ], function() {
        /**
         * Payment processor types
         */
        Route::get('/payment-processor-types', 'PaymentProcessorTypeController@index');

        /**
         * Payment processors
         */
        Route::get('/payment-processors', 'PaymentProcessorController@index');

        /**
         * Product types
         */
        Route::get('/product-types', 'ProductTypeController@index');

        /**
         * Product categories
         */
        Route::get('/categories', 'CategoryController@index');
        Route::post('/categories', 'CategoryController@store');
        Route::put('/categories/{id}', 'CategoryController@update');
        Route::delete('/categories/{id}', 'CategoryController@delete');

        /**
         * Products
         */
        Route::get('/products', 'ProductController@index');
        Route::post('/products', 'ProductController@store');
        Route::get('/products/{id}', 'ProductController@show');
        Route::put('/products/{id}', 'ProductController@update');
        Route::delete('/products/{id}', 'ProductController@delete');

        /**
         * Product reviews
         */
        Route::get('/products/{id}/reviews', 'ProductReviewController@index');
        Route::post('/products/{id}/reviews', 'ProductReviewController@store');
        Route::get('/products/{productId}/reviews/{id}', 'ProductReviewController@show');
        Route::put('/products/{productId}/reviews/{id}', 'ProductReviewController@update');
        Route::delete('/products/{productId}/reviews/{id}', 'ProductReviewController@delete');

        /**
         * Product orders
         */
        Route::get('/orders', 'ProductOrderController@index');
        Route::post('/orders', 'ProductOrderController@store');
        Route::get('/orders/{id}', 'ProductOrderController@show');
        Route::put('/orders/{id}', 'ProductOrderController@update');
        Route::delete('/orders/{id}', 'ProductOrderController@delete');

        /**
         * Product order payments
         */
        Route::get('/orders/{id}/payments', 'ProductOrderPaymentController@index');

        /**
         * Discount codes
         */
        Route::get('/discount-codes', 'DiscountCodeController@index');
        Route::post('/discount-codes', 'DiscountCodeController@store');
        Route::put('/discount-codes/{id}', 'DiscountCodeController@update');
        Route::delete('/discount-codes/{id}', 'DiscountCodeController@delete');

        /**
         * Seller accounts
         */
        Route::get('/sellers', 'SellerAccountController@index');
        Route::post('/sellers', 'SellerAccountController@store');
        Route::get('/sellers/{id}', 'SellerAccountController@show');
        Route::put('/sellers/{id}', 'SellerAccountController@update');
        Route::delete('/sellers/{id}', 'SellerAccountController@delete');

        /**
         * Seller account withdraw methods
         */
        Route::get('/withdraw-methods', 'WithdrawMethodController@index');
        Route::post('/withdraw-methods', 'WithdrawMethodController@store');
        Route::delete('/withdraw-methods/{id}', 'WithdrawMethodController@delete');

        /**
         * Seller account withdraws
         */
        Route::get('/withdraws', 'WithdrawController@index');
        Route::post('/withdraws', 'WithdrawController@store');
        Route::put('/withdraws/{id}', 'WithdrawController@delete');

        /**
         * User account payment methods
         */
        Route::get('/payment-methods', 'PaymentMethodController@index');
        Route::post('/payment-methods', 'PaymentMethodController@store');
        Route::delete('/payment-methods/{id}', 'PaymentMethodController@delete');
    });
});
