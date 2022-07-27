<?php

use Illuminate\Support\Facades\Route;


/**
 * Marketplace api routes (product types, product use snapshots, products, product prices, product orders, product categories, order payments, payment processors, discount codes, etc.)
 */
Route::group([
    'prefix' => 'marketplace',
    'namespace' => 'Marketplace'
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