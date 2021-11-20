<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Orders added to the exchange
         */
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // user making the order
            $table->foreignId('connected_exchange_id'); // api key and exchange to use for the order
            $table->string('order_id'); // order id on the exchange
            $table->morphs('tradeable'); // was the order created by a conditional trade or a trade algorithm
            $table->string('status'); // status of the order
            $table->boolean('is_test')->default(true); // is the order a test order
            $table->string('side'); // buy or sell
            $table->string('type'); // type of order (market, limit, etc)
            $table->string('fill_type'); // type of fulfillment
            $table->decimal('quantity'); // quantity of the order
            $table->decimal('price'); // price of the order
            $table->string('base_symbol'); // base symbol USD, BTC, ETH, etc.
            $table->string('target_symbol'); // target symbol, BTC, USD, LSK, etc.
            $table->timestamp('added_to_exchange_at')->nullable(); // when was the order added to the exchange
            $table->timestamp('fill_started_at')->nullable(); // when was the order beginning to be filled
            $table->timestamp('fill_completed_at')->nullable(); // when was the order completely filled
            $table->timestamps();
        });

        /**
         * Order fills that took place
         */
        Schema::create('fills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id'); // order that the fill took place on
            $table->decimal('quantity'); // quantity of the fill
            $table->decimal('price'); // price of the fill
            $table->timestamp('filled_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fills');
        Schema::dropIfExists('orders');
    }
}
