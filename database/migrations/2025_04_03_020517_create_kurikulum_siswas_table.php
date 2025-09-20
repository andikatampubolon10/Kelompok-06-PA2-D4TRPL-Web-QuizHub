<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kurikulum_siswa', function (Blueprint $table) {
            $table->id('id_kurikulum_siswa');
            $table->unsignedBigInteger('id_kurikulum');
            $table->unsignedBigInteger('id_siswa');
            $table->foreign('id_kurikulum')->references('id_kurikulum')->on('kurikulum')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulum_siswas');
    }
};
