<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_id')->unsigned();
            $table->foreign('division_id')->references('id')->on('divisions');

            $table->integer('professional_id')->unsigned();
            $table->foreign('professional_id')->references('id')->on('professionals');

            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->integer('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');

            $table->enum('agency', ['LOREAL', 'DISTRIBUTOR']);
            $table->enum('gender', ['male', 'female']);
            $table->enum('education', ['SD', 'SLTP', 'SLTA', 'DIPLOMA', 'S1', 'S2']);
            $table->date('birth_date');
            $table->string('name');
            $table->string('nik');
            $table->integer('area_id')->unsigned();
            $table->foreign('area_id')->references('id')->on('areas');
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
        Schema::dropIfExists('employees');
    }
}
