<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeAccountsTable extends Migration
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
        Schema::create('exchange_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('exchange_id');
            $table->string('api_key')->nullable();
            $table->string('api_key_secret')->nullable();
            $table->string('wallet_private_key')->nullable();
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
        Schema::dropIfExists('exchange_accounts');
    }
}
