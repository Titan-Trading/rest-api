<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExchangeDatasetsTableAddDeletedAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('exchange_datasets', function (Blueprint $table) {
            $table->integer('periods')->nullable()->after('name');
            $table->string('source')->after('name');
            $table->softDeletes()->after('updated_at');
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
        Schema::table('exchange_datasets', function (Blueprint $table) {
            $table->dropColumn('periods');
            $table->dropColumn('source');
            $table->dropSoftDeletes();
        });
    }
}
