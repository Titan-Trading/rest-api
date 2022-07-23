<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicatorsTableAddAlgorithmTextColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('indicators', function(Blueprint $table) {
            $table->text('algorithm_text')->nullable();
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
        Schema::table('indicators', function(Blueprint $table) {
            $table->dropColumn('algorithm_text');
        });
    }
}
