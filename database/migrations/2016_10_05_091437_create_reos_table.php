<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reos', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');

            $table->integer('professional_id')->unsigned();
            $table->foreign('professional_id')->references('id')->on('professionals');

            $table->integer('division_id')->unsigned();
            $table->foreign('division_id')->references('id')->on('divisions');

            $table->integer('agency_id')->unsigned();
            $table->foreign('agency_id')->references('id')->on('agencies');

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
        Schema::dropIfExists('reos');
    }
}
