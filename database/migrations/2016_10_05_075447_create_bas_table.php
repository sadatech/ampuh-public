<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nik')->index();
            $table->string('name')->index();
            $table->bigInteger('no_ktp');
            $table->string('no_hp');
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities');

            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->string('rekening')->nullable();
            $table->enum('bank_name', ['BNI', 'MANDIRI']);
            $table->enum('status', ['mobile', 'stay', 'rotasi']);
            $table->date('join_date');
            $table->integer('agency_id')->unsigned();
            $table->foreign('agency_id')->references('id')->on('agencies');
            $table->enum('uniform_size', ['S/7', 'M/9', 'L/11']);
            $table->integer('total_uniform');
            $table->text('description');
            $table->char('class', 15);
            $table->enum('gender', ['male', 'female']);
            $table->integer('approval_id')->unsigned();
            $table->foreign('approval_id')->references('id')->on('approves');

            $table->text('approval_reason')->nullable();
            $table->string('resign_reason', 100)->nullable();;
            $table->integer('division_id')->unsigned();
            $table->foreign('division_id')->references('id')->on('divisions');

            $table->integer('area_id')->unsigned();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->enum('education', ['SD', 'SLTP', 'SLTA', 'DIPLOMA', 'S1', 'S2']);
            $table->date('birth_date');
            $table->integer('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');

            $table->integer('professional_id')->unsigned();
            $table->foreign('professional_id')->references('id')->on('professionals');

            $table->string('foto_ktp')->nullable();;
            $table->string('pas_foto')->nullable();;
            $table->string('foto_tabungan')->nullable();;

            $table->enum('status_sp', ['SP1', 'SP2', 'SP3']);
            $table->date('tanggal_sp')->nullable();
            $table->enum('sp_approval', ['approve', 'pending'])->nullable();
            $table->string('keterangan_sp', 100)->nullable();
            $table->string('foto_sp', 100)->nullable();
            $table->string('foto_akte')->nullable();
            $table->date('anak_lahir_date')->nullable();

            $table->integer('arina_brand_id')->references('id')->on('arina_brands');
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
        Schema::dropIfExists('bas');
    }
}
