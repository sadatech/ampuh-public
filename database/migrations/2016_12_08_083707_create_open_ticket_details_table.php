<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenTicketDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_ticket_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
//            $table->foreign('ticket_id')->references('id')->on('open_tickets');
            $table->string('message');
            $table->string('attachment')->nullable();
            $table->boolean('role');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('open_ticket_details');
    }
}
