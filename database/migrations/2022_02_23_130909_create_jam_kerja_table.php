<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJamKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jam_kerja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('N');
            $table->string('hari');
            $table->time('mulai_absen');
            $table->time('mulai_kerja');
            $table->time('selesai_kerja');

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
        Schema::dropIfExists('jam_kerja');
    }
}
