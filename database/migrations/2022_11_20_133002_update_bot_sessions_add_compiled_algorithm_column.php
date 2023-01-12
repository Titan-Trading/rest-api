<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotSessionsAddCompiledAlgorithmColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('bot_sessions', function(Blueprint $t) {
            $t->text('algorithm_text_compiled')->nullable()->after('bot_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('bot_sessions', function(Blueprint $t) {
            $t->dropColumn('algorithm_text_compiled');
        });
    }
}
