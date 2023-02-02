<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSupportTicketsAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('support_tickets', function(Blueprint $t) {
            $t->id();
            $t->foreignId('user_id');
            $t->foreignId('staff_user_id')->nullable();
            $t->string('status');
            $t->string('priority');
            $t->string('subject');
            $t->text('message');
            $t->timestamps();
        });
        Schema::create('support_ticket_replies', function(Blueprint $t) {
            $t->id();
            $t->foreignId('support_ticket_id');
            $t->foreignId('user_id');
            $t->text('message');
            $t->timestamps();
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
        Schema::drop('support_tickets');
        Schema::drop('support_ticket_replies');
    }
}
