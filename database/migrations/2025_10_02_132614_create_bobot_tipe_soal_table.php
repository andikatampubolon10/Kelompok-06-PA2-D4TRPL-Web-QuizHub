<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBobotTipeSoalTable extends Migration
{
    public function up()
    {
        Schema::create('bobot_tipe_soal', function (Blueprint $table) {
            $table->id('id_bobot_tipe_soal');
            $table->unsignedBigInteger('id_tipe_soal');
            $table->unsignedBigInteger('id_ujian');
            $table->float('bobot');
            $table->timestamps();
            $table->foreign('id_tipe_soal')->references('id_tipe_soal')->on('tipe_soal')->onDelete('cascade');
            $table->foreign('id_ujian')->references('id_ujian')->on('ujian')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bobot_tipe_soal');
    }
}
