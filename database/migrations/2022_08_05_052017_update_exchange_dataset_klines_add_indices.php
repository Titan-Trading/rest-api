<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExchangeDatasetKlinesAddIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('exchange_dataset_klines', function(Blueprint $t) {
            $t->index(['exchange_id', 'symbol_id', 'interval'], 'exc_id_sym_id_int_idx');
            $t->index('timestamp', 'tmstp_idx');
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
        Schema::table('exchange_dataset_klines', function(Blueprint $t) {
            $t->dropIndex('exc_id_sym_id_int_idx');
            $t->dropIndex('tmstp_idx');
        });
    }
}
