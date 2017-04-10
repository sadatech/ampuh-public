<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExitFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ba_id')->unsigned();
            $table->foreign('ba_id')->references('id')->on('bas');
            $table->string('stores');
            $table->date('join_date');
            $table->string('alasan');
            $table->date('filling_date');
            $table->date('effective_date');
            $table->boolean('pending')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exit_forms');
    }
}
