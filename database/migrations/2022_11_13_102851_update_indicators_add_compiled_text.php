<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicatorsAddCompiledText extends Migration
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
            $t->text('algorithm_text_compiled')->nullable();
            $t->text('parameter_options')->nullable();
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
            $t->dropColumn('algorithm_text_compiled');
            $t->dropColumn('parameter_options');
        });
    }
}
