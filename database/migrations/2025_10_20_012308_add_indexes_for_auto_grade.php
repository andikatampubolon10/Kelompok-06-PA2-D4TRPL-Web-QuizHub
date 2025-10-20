<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jawaban_siswa', function (Blueprint $table) {
            // Pilih salah satu urutan, ini yang umum:
            $table->index(['id_siswa', 'id_soal'], 'idx_js_siswa_soal');
        });

        Schema::table('soal', function (Blueprint $table) {
            $table->index('id_ujian', 'idx_soal_ujian');
        });

        Schema::table('bobot_tipe_soal', function (Blueprint $table) {
            $table->index(['id_ujian', 'id_tipe_soal'], 'idx_bobot_ujian_tipe');
        });

        // Tidak perlu idx_tipe_nilai_keys karena sudah ada uniq_tipe_nilai
    }

    public function down(): void
    {
        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->dropIndex('idx_js_siswa_soal');
        });
        Schema::table('soal', function (Blueprint $table) {
            $table->dropIndex('idx_soal_ujian');
        });
        Schema::table('bobot_tipe_soal', function (Blueprint $table) {
            $table->dropIndex('idx_bobot_ujian_tipe');
        });
    }
};
