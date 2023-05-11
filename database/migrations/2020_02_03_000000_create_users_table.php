<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('uker_id'); // uker = unit kerja
            $table->unsignedBigInteger('atasan_id');
            $table->longText('lokasi_id')->nullable();
            $table->string('nama', 32);
            // $table->char('nrp', 9)->unique();
            $table->string('username')->unique();
            $table->text('foto');
            $table->string('password');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('uker_id')->references('id')->on('unitkerjas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
