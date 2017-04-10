<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replaces', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ba_id')->unsigned();
            $table->foreign('ba_id')->references('id')->on('bas');
            $table->enum('status',['Rolling dari Toko Lain', 'Lulus', 'Ba Join Kembali']);
            $table->text('description');
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
        Schema::dropIfExists('replaces');
    }
}
