<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotsAddCompiledAlgorithmText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('bots', function(Blueprint $t) {
            $t->text('algorithm_text_compiled')->nullable();
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
        Schema::table('bots', function(Blueprint $t) {
            $t->dropColumn('algorithm_text_compiled');
        });
    }
}
