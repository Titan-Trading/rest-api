<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Market types (supports crypto, stock market, etc.)
         */
        Schema::create('market_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Exchange to make trades on (stock market, crypto, both, etc.)
         */
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logo_id');
            $table->string('name')->unique();
            $table->string('website_url');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_dex')->default(false);
            $table->string('symbol_template');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * Type of exchange (supports crypto only, stock market only, both, etc.)
         */
        Schema::create('exchange_market_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_type_id');
            $table->foreignId('exchange_id');
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
        Schema::dropIfExists('exchange_market_type');
        Schema::dropIfExists('exchanges');
        Schema::dropIfExists('exchange_types');
    }
}
