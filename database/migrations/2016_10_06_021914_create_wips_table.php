<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWIPsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wips', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->integer('ba_id')->unsigned()->nullable();
            $table->foreign('ba_id')->references('id')->on('bas');

            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->integer('sdf_id')->unsigned()->nullable();
            $table->foreign('sdf_id')->references('id')->on('sdfs');

            $table->integer('replace_id')->unsigned()->nullable();
            $table->foreign('replace_id')->references('id')->on('replaces');

            $table->enum('status', ['replacement', 'new store']);
            $table->enum('fullfield', ['fullfield', 'hold', '']);

            $table->text('reason');
            $table->date('filling_date');
            $table->date('effective_date');

            $table->float('head_count');
            $table->boolean('pending')->default(0);
            $table->boolean('isHold')->default(0);

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
        Schema::dropIfExists('w_i_ps');
    }
}
