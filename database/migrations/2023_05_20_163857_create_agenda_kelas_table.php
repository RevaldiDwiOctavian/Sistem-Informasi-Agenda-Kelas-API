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
        Schema::create('agenda_kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id');
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('pembelajaran_id');
            $table->unsignedBigInteger('siswa_absen_id');
            $table->text('materi_pembelajaran');
            $table->timestamp('tgl_agenda');
            $table->string('kehadiran_guru');
            $table->timestamps();
            $table->foreign('rombel_id')->references('id')->on('rombels');
            $table->foreign('guru_id')->references('id')->on('gurus');
            $table->foreign('pembelajaran_id')->references('id')->on('pembelajarans');
            $table->foreign('siswa_absen_id')->references('id')->on('siswa_absens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_kelas');
    }
};
