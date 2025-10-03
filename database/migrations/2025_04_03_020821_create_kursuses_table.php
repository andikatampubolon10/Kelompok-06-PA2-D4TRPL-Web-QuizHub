<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kursus', function (Blueprint $table) {
            $table->id('id_kursus');
            $table->string('nama_kursus');
            $table->string('password');
            $table->string('image');
            $table->string('image_url')->nullable();

            // relasi guru
            $table->unsignedBigInteger('id_guru');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');

            // relasi operator
            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id_operator')->on('operator')->onDelete('cascade');

            // relasi mata pelajaran
            $table->unsignedBigInteger('id_mata_pelajaran');
            $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');

            // relasi tahun ajaran
            $table->unsignedBigInteger('ID_Tahun_Ajaran');
            $table->foreign('ID_Tahun_Ajaran')->references('id_tahun_ajaran')->on('tahun_ajaran')->onDelete('cascade');

            // relasi kelas
            $table->unsignedBigInteger('id_kelas');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursuses');
    }
};
