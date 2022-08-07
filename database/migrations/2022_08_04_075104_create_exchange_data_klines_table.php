<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeDataKlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_dataset_klines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id');
            $table->foreignId('exchange_dataset_id');
            $table->foreignId('exchange_id');
            $table->foreignId('symbol_id');
            $table->string('interval');
            $table->decimal('open', 36, 18);
            $table->decimal('high', 36, 18);
            $table->decimal('low', 36, 18);
            $table->decimal('close', 36, 18);
            $table->decimal('volume', 36, 18);
            $table->decimal('base_volume', 36, 18);
            $table->integer('timestamp');
            $table->timestamp('date');
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
        Schema::dropIfExists('exchange_dataset_klines');
    }
}
