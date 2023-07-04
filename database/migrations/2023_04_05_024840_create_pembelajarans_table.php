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
        Schema::create('pembelajarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id')->nullable();
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->string('mata_pelajaran');
            $table->timestamps();
            $table->foreign('rombel_id')->references('id')->on('rombels')->onDelete('set null');
            $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelajarans');
    }
};
