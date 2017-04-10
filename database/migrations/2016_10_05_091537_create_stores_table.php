1<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_no');
            $table->string('customer_id');
            $table->string('store_name_1');
            $table->string('store_name_2');
            $table->string('channel');
            $table->string('shipping_id')->nullable();
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities');

            $table->integer('reo_id')->unsigned();
            $table->foreign('reo_id')->references('id')->on('reos');

            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->float('alokasi_ba_nyx')->nullable();
            $table->float('alokasi_ba_oap')->nullable();
            $table->float('alokasi_ba_myb')->nullable();
            $table->float('alokasi_ba_gar')->nullable();
            $table->float('alokasi_ba_cons')->nullable();
            $table->float('alokasi_ba_mix')->nullable();
            $table->enum('isHold', [0, 1])->default(0);


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
        Schema::dropIfExists('stores');
    }
}
