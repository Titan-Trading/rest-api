<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotsTableAddAlgorithmTextVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('bots', function (Blueprint $table) {
            $table->integer('algorithm_text_version')->default(1);
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
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('algorithm_text_version');
        });
    }
}
