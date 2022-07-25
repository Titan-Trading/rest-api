<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Type of currency (Ex: crypto, fiat, stock, etc.)
         */
        Schema::create('currency_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Currencies (USD, BTC, ETH, etc.)
         */
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id'); // type of currency (Ex: fiat, crypto, etc.)
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Currency pairs to use for trades
         */
        Schema::create('symbols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id');
            $table->foreignId('target_currency_id');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Pivot a currency pair and an exchange
         */
        Schema::create('exchange_symbol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id');
            $table->foreignId('exchange_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Metadata for a currency pair on an exchange
         */
        Schema::create('exchange_symbol_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_symbol_id');
            $table->string('key');
            $table->string('value');
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
        Schema::dropIfExists('exchange_symbol_metadata');
        Schema::dropIfExists('exchange_symbol');
        Schema::dropIfExists('symbols');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('currency_types');
    }
}
