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
        Schema::create('kurikulum', function (Blueprint $table) {
            $table->id('id_kurikulum');
            $table->string('nama_kurikulum');
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id_operator')->on('operator')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulums');
    }
};
