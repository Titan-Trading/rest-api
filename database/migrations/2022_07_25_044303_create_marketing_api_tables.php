<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingApiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        /**
         * Type of payment processor (bank, credit_card, debit_card, crypto, etc.)
         */
        Schema::create('payment_processor_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Payment processor used to purchase a product (stripe, plaid, etc.)
         */
        Schema::create('payment_processors', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Pivot payment processors to payment processor types
         */
        Schema::create('payment_processor_payment_processor_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_processor_type_id');
            $table->foreignId('payment_processor_id');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Pivot table for users and payment processor accounts
         */
        Schema::create('user_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('payment_processor_type_id'); // ex: bank, card, crypto, etc
            $table->foreignId('payment_processor_id'); // payment processors, ex: stripe
            $table->string('payment_processor_account_id'); // ex: stripe account id
            $table->text('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * An account that a user is selling products from
         */
        Schema::create('seller_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id'); // users
            $table->foreignId('default_withdraw_method_id')->nullable(); // default account to withdraw to
            $table->string('name')->unique();
            $table->string('status');
            $table->decimal('rating'); // average rating of all products sales made by this account
            $table->decimal('balance');
            $table->decimal('commission_generated'); // total profit that this account has generated for the platform
            $table->decimal('revenue_generated'); // total revenue that this account has generated for the platform
            $table->timestamp('balance_updated_at')->nullable(); // when the balance was last updated
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Pivot table for seller account and payment processor accounts (withdraw methods)
         */
        Schema::create('seller_account_withdraw_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id');
            $table->foreignId('payment_processor_type_id'); // ex: bank, card, crypto, etc
            $table->foreignId('payment_processor_id'); // payment processors, ex: stripe
            $table->string('payment_processor_account_id'); // ex: stripe account id
            $table->text('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * A withdraw from a seller account
         */
        Schema::create('seller_account_withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id');
            $table->foreignId('withdraw_method_id');
            $table->decimal('payout_amount');
            $table->decimal('tax_amount');
            $table->decimal('commission_amount');
            $table->decimal('balance_after_withdraw');
            $table->text('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Type of product being sold
         */
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Categories that a product can be a part of
         */
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Product that can be sold (bot/trading strategy, merch, etc.)
         */
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id');
            $table->string('sellable_type')->nullable(); // bot or indicator
            $table->foreignId('sellable_id')->nullable(); // id of the bot or indicator
            $table->foreignId('featured_image_id')->nullable();
            $table->boolean('is_featured');
            $table->text('status');
            $table->string('name');
            $table->text('description');
            $table->decimal('rating');
            $table->integer('quantity')->nullable();
            $table->integer('sold'); // how many times the product has been sold
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Type of product prices
         */
        Schema::create('product_price_types', function(Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Prices that a product can be sold for
         */
        Schema::create('product_prices', function(Blueprint $table) {
            $table->id();
            $table->foreignId('type_id');
            $table->foreignId('product_id');
            $table->boolean('is_active');
            $table->string('name')->unique();
            $table->decimal('price_value');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Pivot table to assign categories to a product
         */
        Schema::create('product_category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('product_category_id');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Reviews for the product
         */
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id'); // users
            $table->foreignId('product_id'); // product that review was left on
            $table->foreignId('featured_image_id')->nullable();
            $table->decimal('rating');
            $table->boolean('is_featured');
            $table->text('status');
            $table->string('reviewer_name'); // name of the reviewer
            $table->string('title');
            $table->text('text');
            $table->timestamps();
            $table->softDeletes();
        });
        
        /**
         * Product purchase orders
         */
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id');
            $table->foreignId('buyer_id');
            $table->foreignId('product_id'); // product that was purchased
            $table->foreignId('product_price_id'); // pricing model of the product purchased
            $table->foreignId('discount_code_id')->nullable(); // id of the discount applied, if one is applied
            $table->text('status'); // status of the order
            $table->timestamp('next_payment_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Product purchase order payments
         */
        Schema::create('product_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('buyer_id');
            $table->foreignId('payment_processor_type_id'); // ex: bank, card, crypto, etc
            $table->foreignId('payment_processor_id'); // payment processors, ex: stripe
            $table->string('payment_processor_account_id'); // ex: stripe account id
            $table->text('status'); // status of the payment
            $table->decimal('commission_amount');
            $table->decimal('tax_amount');
            $table->decimal('paid_amount');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Product discount types (dollar amount, percentage, etc.)
         */
        Schema::create('discount_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Product discount codes
         */
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id'); // id of the user that created the discount code
            $table->foreignId('type_id'); // type of discount to apply
            $table->string('code')->unique(); // code to apply the discount
            $table->integer('max_uses')->nullable(); // number of times the discount code can be used
            $table->integer('uses'); // number of times the discount code has been used
            $table->decimal('order_minimum_required')->nullable(); // minimum dollar amount to apply discount code
            $table->text('status'); // status of the discount code
            $table->decimal('discount_amount'); // amount of the discount
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_processor_payment_processor_type');
        Schema::dropIfExists('payment_processors');
        Schema::dropIfExists('payment_processor_types');
        Schema::dropIfExists('seller_account_withdraws');
        Schema::dropIfExists('seller_accounts');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_price_types');
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('product_category_product');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_orders');
        Schema::dropIfExists('product_order_payments');
        Schema::dropIfExists('discount_types');
        Schema::dropIfExists('discount_codes');
    }
}
