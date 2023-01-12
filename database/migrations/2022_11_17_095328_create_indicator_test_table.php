<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('exchange_account_id');
            $table->foreignId('bot_id');
            $table->foreignId('indicator_id');
            $table->string('name');
            $table->boolean('active');
            $table->text('bot_parameters');
            $table->text('indicator_parameters');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
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
        Schema::dropIfExists('indicator_tests');
    }
}
