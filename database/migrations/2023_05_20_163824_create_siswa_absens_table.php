<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id');
            $table->unsignedBigInteger('siswa_id');
            $table->string('keterangan_absen');
            $table->string('alasan');
            $table->string('kelas');
            $table->timestamps();
            $table->foreign('rombel_id')->references('id')->on('rombels');
            $table->foreign('siswa_id')->references('id')->on('siswas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siswa_absens');
    }
};
