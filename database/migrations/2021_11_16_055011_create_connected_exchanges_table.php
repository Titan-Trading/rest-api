<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectedExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Exchange accounts (connections for a given user to a given exchange)
         */
        Schema::create('connected_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('exchange_id');
            $table->string('api_key');
            $table->string('api_key_secret');
            $table->string('wallet_private_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connected_exchanges');
    }
}
