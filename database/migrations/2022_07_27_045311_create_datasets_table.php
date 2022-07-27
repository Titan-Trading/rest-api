<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Saved dataset to backtest against quickly and repetitively
         */
        Schema::create('exchange_datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id');
            $table->foreignId('exchange_id');
            $table->foreignId('symbol_id');
            $table->string('interval');
            $table->string('name');
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
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
        Schema::dropIfExists('exchange_datasets');
    }
}
