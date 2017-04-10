<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreKonfiglogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_konfiglogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('year');
            $table->integer('month');
            $table->integer('konfigurasi');
            $table->string('alokasi_ba_oap');
            $table->string('alokasi_ba_myb');
            $table->string('alokasi_ba_gar');
            $table->string('alokasi_ba_cons');
            $table->string('alokasi_ba_mix');
            $table->string('oap_count');
            $table->string('myb_count');
            $table->string('gar_count');
            $table->string('mix_count');
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
        Schema::dropIfExists('store_konfiglogs');
    }
}
