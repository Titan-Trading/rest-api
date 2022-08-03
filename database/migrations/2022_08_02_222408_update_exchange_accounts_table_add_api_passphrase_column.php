<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExchangeAccountsTableAddApiPassphraseColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('exchange_accounts', function(Blueprint $t) {
            $t->string('api_key_passphrase')->nullable();
            $t->string('api_version')->nullable();
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
        Schema::table('exchange_accounts', function(Blueprint $t) {
            $t->dropColumn('api_key_passphrase');
            $t->dropColumn('api_version');
        });
    }
}
