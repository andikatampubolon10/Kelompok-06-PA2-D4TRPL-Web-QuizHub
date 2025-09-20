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
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->string('nama_guru');
            $table->bigInteger('nip')->unique(); 
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->unsignedBigInteger('id_mata_pelajaran');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_operator')->references('id_operator')->on('operator')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::dropIfExists('guru');  // Perbaiki nama tabel menjadi 'guru'
}

};
