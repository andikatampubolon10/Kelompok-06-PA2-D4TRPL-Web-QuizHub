<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Tambah UNIQUE KEY hanya jika belum ada (hindari error 1061 Duplicate key)
        $exists = DB::selectOne("
            SELECT COUNT(1) AS c
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'tipe_nilai'
              AND index_name = 'uniq_tipe_nilai'
        ");
        if (empty($exists) || (int)$exists->c === 0) {
            DB::unprepared("
                ALTER TABLE tipe_nilai
                ADD UNIQUE KEY uniq_tipe_nilai (id_siswa, id_ujian, id_tipe_ujian);
            ");
        }

        // Bersihkan jika prosedur/trigger sudah ada
        DB::unprepared("DROP TRIGGER IF EXISTS trg_js_ins_recalc");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_js_upd_recalc");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_recalc_nilai_ujian");

        // === PROCEDURE (tanpa DELIMITER & tanpa CTE; kompatibel MariaDB) ===
       DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_recalc_nilai_ujian(IN p_id_ujian BIGINT, IN p_id_siswa BIGINT)
BEGIN
    DECLARE v_id_tipe_ujian BIGINT;
    DECLARE v_total_weighted DOUBLE DEFAULT 0;

    -- Ambil tipe_ujian (untuk penulisan ke tipe_nilai)
    SELECT id_tipe_ujian INTO v_id_tipe_ujian
    FROM ujian WHERE id_ujian = p_id_ujian LIMIT 1;

    -- Jika total bobot tipe soal ≠ 100, jangan lakukan apa-apa
    IF (SELECT IFNULL(SUM(bobot),0) FROM bobot_tipe_soal WHERE id_ujian = p_id_ujian) = 100 THEN

        /*
          HANYA menilai otomatis:
          - 'Pilihan Berganda' & 'Benar Salah' (berdasarkan jawaban_soal.benar = 1).
          'Isian' (essay) diabaikan (dinilai manual).
        */
        SELECT IFNULL(SUM(weighted),0) INTO v_total_weighted
        FROM (
            SELECT
                t.id_tipe_soal,
                t.total_bobot,
                t.bobot_benar,
                t.bobot_tipe,
                (
                    (CASE WHEN t.total_bobot > 0
                          THEN (t.bobot_benar / t.total_bobot) * 100
                          ELSE 0 END)
                    * t.bobot_tipe / 100
                ) AS weighted
            FROM (
                SELECT
                    ts.id_tipe_soal,
                    SUM(COALESCE(s.bobot,0)) AS total_bobot,
                    SUM(CASE WHEN jso.benar = 1 THEN COALESCE(s.bobot,0) ELSE 0 END) AS bobot_benar,
                    IFNULL(MAX(bts.bobot),0) AS bobot_tipe
                FROM soal s
                JOIN tipe_soal ts ON ts.id_tipe_soal = s.id_tipe_soal
                LEFT JOIN jawaban_siswa js
                    ON js.id_soal = s.id_soal AND js.id_siswa = p_id_siswa
                LEFT JOIN jawaban_soal jso
                    ON jso.id_jawaban_soal = js.id_jawaban_soal
                LEFT JOIN bobot_tipe_soal bts
                    ON bts.id_ujian = p_id_ujian AND bts.id_tipe_soal = ts.id_tipe_soal
                WHERE s.id_ujian = p_id_ujian
                  AND LOWER(ts.nama_tipe_soal) IN ('pilihan berganda','benar salah')
                GROUP BY ts.id_tipe_soal
            ) AS t
        ) AS x;

        -- Upsert hasil ke tipe_nilai (dibulatkan)
        INSERT INTO tipe_nilai (id_siswa, id_ujian, id_tipe_ujian, nilai, created_at, updated_at)
        VALUES (p_id_siswa, p_id_ujian, v_id_tipe_ujian, ROUND(v_total_weighted), NOW(), NOW())
        ON DUPLICATE KEY UPDATE nilai = VALUES(nilai), updated_at = NOW();

    END IF;
END
SQL);

        // === TRIGGERS (tanpa DELIMITER) ===
        DB::unprepared("
CREATE TRIGGER trg_js_ins_recalc
AFTER INSERT ON jawaban_siswa
FOR EACH ROW
BEGIN
    DECLARE v_id_ujian BIGINT;
    SELECT s.id_ujian INTO v_id_ujian
    FROM soal s WHERE s.id_soal = NEW.id_soal LIMIT 1;

    IF v_id_ujian IS NOT NULL THEN
        CALL sp_recalc_nilai_ujian(v_id_ujian, NEW.id_siswa);
    END IF;
END
        ");

        DB::unprepared("
CREATE TRIGGER trg_js_upd_recalc
AFTER UPDATE ON jawaban_siswa
FOR EACH ROW
BEGIN
    DECLARE v_id_ujian BIGINT;
    SELECT s.id_ujian INTO v_id_ujian
    FROM soal s WHERE s.id_soal = NEW.id_soal LIMIT 1;

    IF v_id_ujian IS NOT NULL THEN
        CALL sp_recalc_nilai_ujian(v_id_ujian, NEW.id_siswa);
    END IF;
END
        ");
    }

public function down(): void
{
    DB::unprepared('DROP TRIGGER IF EXISTS trg_js_ins_recalc');
    DB::unprepared('DROP TRIGGER IF EXISTS trg_js_upd_recalc');
    DB::unprepared('DROP PROCEDURE IF EXISTS sp_recalc_nilai_ujian');
}

};
