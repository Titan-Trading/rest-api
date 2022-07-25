<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * A list of a different bots that are available (selection of an algorithm)
         */
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->longText('algorithm_text');
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * A session of a bot making trades (back-testing or live), parameters or options, start or end dates, etc
         */
        Schema::create('bot_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('connected_exchange_id');
            $table->foreignId('bot_id');
            $table->text('parameters');
            $table->string('mode');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * The results of a bot's session (max draw-down, profit, )
         */
        Schema::create('bot_session_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_session_id');
            $table->integer('total_pips');
            $table->integer('total_trades');
            $table->integer('total_wins');
            $table->integer('total_losses');
            $table->decimal('winning_percent');
            $table->decimal('risk_percent_per_position');
            $table->decimal('max_dollar_drawdown');
            $table->decimal('total_dollar_gain');
            $table->decimal('total_dollar_percent_gain');
            $table->decimal('starting_balance');
            $table->decimal('ending_balance');
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
        Schema::dropIfExists('bot_session_results');
        Schema::dropIfExists('bot_sessions');
        Schema::dropIfExists('bots');
    }
}
