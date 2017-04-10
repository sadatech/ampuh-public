<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ba_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ba_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->integer('month');
            $table->integer('year');
            $table->integer('store_count_static')->nullable();
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
        Schema::dropIfExists('ba_summaries');
    }
}
