<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotsAddSymbolsAndIndicatorsJsonFields extends Migration
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
            $t->text('symbols')->nullable()->after('parameter_options');
            $t->text('indicators')->nullable()->after('symbols');
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
            $t->dropColumn('symbols');
            $t->dropColumn('indicators');
        });
    }
}
