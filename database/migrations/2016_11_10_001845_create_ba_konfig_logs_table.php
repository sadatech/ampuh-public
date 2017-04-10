<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaKonfigLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ba_konfig_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year',false,true);
            $table->integer('month',false,true);
            $table->string('konfigurasi');
            $table->char('no',10);
            $table->string('nik');
            $table->string('id_ba');
            $table->string('id_toko');
            $table->string('agency');
            $table->string('name');
            $table->string('no_ktp');
            $table->string('no_hp');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('nama_reo');
            $table->integer('region');
            $table->string('brand');
            $table->string('store_no');
            $table->string('customer_id');
            $table->string('store_name_1');
            $table->string('store_name_2');
            $table->string('shipping_id');
            $table->string('channel');
            $table->string('account');
            $table->string('status');
            $table->string('join_date');
            $table->string('size_baju');
            $table->string('jumlah_seragam');
            $table->string('keterangan');
            $table->string('masa_kerja');
            $table->string('class');
            $table->string('jenis_kelamin');
            $table->string('hc');
            $table->enum('status_sp', ['SP1', 'SP2', 'SP3']);
            $table->date('tanggal_sp');
            $table->enum('sp_approval', ['approve', 'pending']);
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
        Schema::dropIfExists('ba_konfig_logs');
    }
}
