<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicatorsAddAlgorithmTextVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('indicators', function(Blueprint $t) {
            $t->integer('algorithm_version')->default(1);
            $t->foreignId('user_id')->default(1)->before('name'); // user account the indicator is for
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
        Schema::table('indicators', function(Blueprint $t) {
            $t->dropColumn('algorithm_version');
            $t->dropColumn('user_id');
        });
    }
}
