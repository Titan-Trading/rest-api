<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotsAndIndicatorsTablesAddEventStreamsColumn extends Migration
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
            $table->text('event_streams')->nullable()->after('parameter_options');
        });

        Schema::table('indicators', function (Blueprint $table) {
            $table->text('event_streams')->nullable()->after('parameter_options');
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
            $table->dropColumn('event_streams');
        });

        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('event_streams');
        });
    }
}
