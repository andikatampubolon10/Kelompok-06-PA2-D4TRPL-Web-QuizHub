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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id('ID_Tahun_Ajaran');
            $table->string('Nama_Tahun_Ajaran');
            $table->date('Mulai_Tahun_Ajaran');
            $table->date('Selesai_Tahun_Ajaran');
            $table->enum('Status', ['Aktif', 'Tidak Aktif']);
            $table->timestamps();
            $table->unsignedBigInteger('id_operator');
            $table->unsignedBigInteger('id_kurikulum');
            $table->foreign('id_operator')->references('id_operator')->on('operator')->onDelete('cascade');
            $table->foreign('id_kurikulum')->references('id_kurikulum')->on('kurikulum')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
