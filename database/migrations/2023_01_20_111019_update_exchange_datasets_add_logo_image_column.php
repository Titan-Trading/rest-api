<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExchangeDatasetsAddLogoImageColumn extends Migration
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
            $table->foreignId('logo_image_id')->nullable();
            $table->longText('description')->nullable();
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
            $table->dropColumn('logo_image_id');
            $table->dropColumn('description');
        });
    }
}
