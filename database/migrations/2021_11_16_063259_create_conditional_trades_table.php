<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionalTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Conditional trade instance
         */
        Schema::create('conditional_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // user trade is created by
            $table->foreignId('exchange_account_id'); // exchange and api key to use for the trade
            $table->foreignId('parent_conditional_trade_id')->nullable(); // only apply trade once parent trade has completed
            $table->boolean('is_test')->default(false); // is test order or not
            $table->boolean('is_active')->default(false); // is active or not
            $table->string('status'); // status of the trade
            $table->string('side')->default('buy'); // side of the trade
            $table->string('base_symbol'); // base symbol USD, BTC, ETH, etc.
            $table->string('target_symbol'); // target symbol, BTC, USD, LSK, etc.
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Market indicators (RSI, EMA) (ability to make new indicators)
         */
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // name of the indicator
            $table->boolean('is_active'); // is the indicator active and able to be used
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Metadata attached to a market indicator (RSI or EMA20 operators)
         */
        Schema::create('indicator_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id'); // indicator metadata is attached to
            $table->string('key'); // key to find the metadata
            $table->string('value'); // value of the metadata
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Condition in which to place an order
         */
        Schema::create('trade_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conditional_trade_id'); // conditional trade that condition is applied to
            $table->foreignId('indicator_id'); // RSI, EMA, etc
            $table->foreignId('value_id'); // value record to compare to
            $table->string('comparative_operator'); // equal to, greater than, less than, etc.
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Condition value
         */
        Schema::create('trade_condition_values', function(Blueprint $table) {
            $table->id();
            $table->string('value_type'); // type of value
            $table->string('value'); // value
            $table->softDeletes();
        });

        /**
         * How to tie multiple trade conditions together using a boolean operator
         */
        Schema::create('trade_condition_operators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operand1_id'); // trade condition 1
            $table->foreignId('operand2_id'); // trade condition 2
            $table->integer('precedence')->default(1); // the precedence that the condition is applied
            $table->string('boolean_operator'); // AND, OR, etc.
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
        Schema::dropIfExists('trade_condition_operators');
        Schema::dropIfExists('trade_condition_values');
        Schema::dropIfExists('trade_conditions');
        Schema::dropIfExists('indicator_metadata');
        Schema::dropIfExists('indicators');
        Schema::dropIfExists('conditional_trades');
    }
}
