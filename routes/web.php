<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardsiswaController;
use App\Http\Controllers\JawabanSiswaLatihanSoalController;
use App\Http\Controllers\JawabanSiswaQuizController;
use App\Http\Controllers\JawabanSiswaUjianController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\LatihanSoalController;
use App\Http\Controllers\LatihanSoalSoalController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JawabanSoalUjianController;
use App\Http\Controllers\JawabanSoalQuizController;
use App\Http\Controllers\JawabanLatihanSoalController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\persentaseController;
use App\Http\Controllers\ListSiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\BobotTipeSoalController;
use App\Http\Controllers\EnrollSiswaController;
use App\Http\Controllers\PracticeQuestionController;
use App\Models\mata_pelajaran;
use App\Models\Guru;
use App\Http\Middleware\CheckOperatorStatus;
use App\Models\TahunAjaran;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// Halaman dashboard
Route::get('/login', function () {
    return view('login');
})->middleware(['auth', 'verified'])->name('dashboard');


// Group route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Route untuk profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('Admin')->name('Admin.')->middleware('role:Admin')->group(function () {
        Route::resource('/Akun', OperatorController::class)->parameters(['Akun' => 'user']);
        Route::get('/Akun/{user}/edit', [OperatorController::class, 'edit'])->name('Akun.edit');
        Route::get('Akun/{user}', [OperatorController::class, 'show'])->name('Admin.Akun.show');
        Route::resource('/Bisnis', BisnisController::class)->parameters(['Bisnis' => 'id_bisnis',]);
    });

    Route::prefix('Guru')->name('Guru.')->middleware('role:Guru')->group(function () {
        Route::resource('/Course', CourseController::class);
        Route::resource('/Siswa', SiswaController::class);
        Route::resource('/Latihan', LatihanSoalController::class);
        Route::get('/Latihan/{id_latihan}/edit', [LatihanSoalController::class, 'edit'])->name('Guru.Latihan.edit');
        Route::resource('/LatihanSoalSoal', LatihanSoalSoalController::class);
        Route::resource('/Kelas', KelasController::class);
        Route::resource('/MataPelajaran', MataPelajaranController::class);
        Route::get('/Ujian/{id_ujian}/selesai', [UjianController::class, 'selesai'])
        ->name('Ujian.selesai');
        Route::get('/Ujian/{id_ujian}/siswa/{id_siswa}', [UjianController::class, 'detailJawabanSiswa'])
        ->name('Ujian.jawabanSiswa');
        Route::resource('/Ujian', UjianController::class);
        Route::get('/ListSiswa/{id_kursus}', [ListSiswaController::class, 'index'])->name('ListSiswa');
        Route::get('/nilai/export/{id_kursus}', [ListSiswaController::class, 'exportNilai'])->name('nilai.export');
        Route::get('/nilai/{id_kursus}', [App\Http\Controllers\NilaiController::class, 'index'])->name('Guru.nilai.index');
        Route::post('/calculate-nilai/{id_kursus}/{id_siswa}', [NilaiController::class, 'calculateNilai'])->name('Guru.nilai.calculate');
        Route::get('/nilai-breakdown/{id_kursus}/{id_siswa}', [App\Http\Controllers\NilaiController::class, 'getScoreBreakdown'])->name('Guru.nilai.breakdown');
        Route::resource('/Soal', SoalController::class);
        Route::resource('/Persentase', persentaseController::class);
        Route::post('/Soal/upload-image', [App\Http\Controllers\SoalController::class, 'uploadImage'])->name('Soal.uploadImage');
        Route::get('/Soal/create/{type}', [SoalController::class, 'create'])->name('Guru.Soal.create');
        Route::get('/Soal/preview/{id}', [SoalController::class, 'preview'])->name('Soal.preview');
        Route::resource('/Kurikulum', KurikulumController::class);
        Route::resource('/Attempt', AttemptController::class);

        Route::get('/Materi', [MateriController::class, 'index'])->name('Materi.index');
        Route::get('/Materi/create', [MateriController::class, 'create'])->name('Materi.create');
        Route::post('/Materi', [MateriController::class, 'store'])->name('Materi.store');
        Route::get('/Materi/{id_materi}', [MateriController::class, 'show'])->name('Materi.show');
        Route::get('/Materi/{id_materi}/edit', [MateriController::class, 'edit'])->name('Materi.edit');
        Route::put('/Materi/{id_materi}', [MateriController::class, 'update'])->name('Materi.update');
        Route::patch('/Materi/{id_materi}', [MateriController::class, 'update']);
        Route::delete('/Materi/{id_materi}', [MateriController::class, 'destroy'])->name('Materi.destroy');

        Route::resource('/JawabanSiswaLatihanSoal', JawabanSiswaLatihanSoalController::class);
        Route::resource('/JawabanSiswaUjian', JawabanSiswaUjianController::class);
        Route::resource('/Nilai', NilaiController::class);
        Route::post('/reset-recalculate-nilai/{id_kursus}', [ListSiswaController::class, 'resetAndRecalculateNilai']);
        Route::get('bobot-tipe-soal', [BobotTipeSoalController::class, 'index'])->name('BobotTipeSoal.index');
        Route::get('bobot-tipe-soal/create', [BobotTipeSoalController::class, 'create'])->name('BobotTipeSoal.create');
        Route::post('bobot-tipe-soal', [BobotTipeSoalController::class, 'store'])->name('BobotTipeSoal.store');
        Route::get('bobot-tipe-soal/{id_bobot_tipe_soal}/edit', [BobotTipeSoalController::class, 'edit'])->name('BobotTipeSoal.edit');
        Route::put('bobot-tipe-soal/{id_bobot_tipe_soal}', [BobotTipeSoalController::class, 'update'])->name('BobotTipeSoal.update');
        Route::delete('bobot-tipe-soal/{id_bobot_tipe_soal}', [BobotTipeSoalController::class, 'destroy'])->name('BobotTipeSoal.destroy');
        
    });

    Route::prefix('Operator')->name('Operator.')->middleware('role:Operator')->group(function () {
        Route::get('/Guru/upload', [GuruController::class, 'upload'])->name('Guru.upload');
        Route::post('/Guru/import', [GuruController::class, 'import'])->name('Guru.import');
        Route::resource('/Guru', GuruController::class);
        Route::get('/Course/beranda', [CourseController::class, 'beranda'])->name('Course.beranda');
        Route::resource('/Course', CourseController::class);
        Route::resource('semester', SemesterController::class);


        Route::resource('/Siswa', SiswaController::class);
        Route::get('/Siswa/upload', [SiswaController::class, 'upload'])->name('Siswa.upload');
        Route::post('/Siswa/import', [SiswaController::class, 'import'])->name('Siswa.import');

        Route::get('/TahunAjaran/{id_tahun_ajaran}/edit', [TahunAjaranController::class, 'edit'])
            ->name('TahunAjaran.edit');

        Route::put('/TahunAjaran/{id_tahun_ajaran}', [TahunAjaranController::class, 'update'])
            ->name('TahunAjaran.update');

        // Gunakan resource untuk TahunAjaran, create sudah otomatis ada
        Route::prefix('/Kurikulum/{id_kurikulum}')->group(function () {
            // Index (list tahun ajaran untuk kurikulum tertentu)
            Route::get('/TahunAjaran', [TahunAjaranController::class, 'index'])
                ->name('TahunAjaran.index');

            // Create form (butuh id_kurikulum)
            Route::get('/TahunAjaran/create', [TahunAjaranController::class, 'create'])
                ->name('TahunAjaran.create');

            // Store (simpan tahun ajaran untuk kurikulum ini)
            Route::post('/TahunAjaran', [TahunAjaranController::class, 'store'])
                ->name('TahunAjaran.store');

            // Edit/Update/Delete (opsional, pakai id_tahun_ajaran sebagai param)
            Route::get('/TahunAjaran/{id_tahun_ajaran}/edit', [TahunAjaranController::class, 'edit'])
                ->name('TahunAjaran.edit');
            Route::put('/TahunAjaran/{id_tahun_ajaran}', [TahunAjaranController::class, 'update'])
                ->name('TahunAjaran.update');
            Route::delete('/TahunAjaran/{id_tahun_ajaran}', [TahunAjaranController::class, 'destroy'])
                ->name('TahunAjaran.destroy');
        });

        // Route untuk Kelas dan Kurikulum
        Route::resource('/Kelas', KelasController::class);
        Route::resource('/Kurikulum', KurikulumController::class);
        Route::resource('/MataPelajaran', MataPelajaranController::class);
    });

    // Route untuk Siswa
    Route::prefix('Siswa')->name('Siswa.')->middleware('role:Siswa')->group(function () {
        Route::get('/courses', [DashboardsiswaController::class, 'dashboard'])->name('Course.index');
        Route::get('/courses/{id_kursus}/ujian', [DashboardsiswaController::class, 'tipeujian'])->name('Course.tipeujian');
        Route::post('/courses/ujian/enter', [DashboardsiswaController::class, 'enterUjian'])->name('Course.ujian.enter');
        // routes/web.php atau routes/api.php
        Route::get('/courses/{id_kursus}/ujian/{id_ujian}/{id_tipe_ujian}/take', [DashboardsiswaController::class, 'soal'])->name('Course.ujian.take');
        Route::get('/enroll/kurikulum', [EnrollSiswaController::class, 'kurikulum'])->name('enroll.kurikulum');
        Route::get('/enroll/kurikulum/{id_kurikulum}/tahun-ajaran', [EnrollSiswaController::class, 'tahunAjaran'])->name('enroll.tahunajaran');
        Route::get('/enroll/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester', [EnrollSiswaController::class, 'semester'])
            ->name('enroll.semester');
        Route::get('/siswa/kursus/{kursus}/ujian/{ujian}/gate', [DashboardsiswaController::class, 'gate'])
            ->name('ujian.gate');

        // routes/web.php

        Route::get('/courses/{id_kursus}/ujian/{id_ujian}/hasil', [DashboardsiswaController::class, 'hasilUjian'])
            ->name('Ujian.hasil');

        Route::post('/ujian/{kursus_id}/{ujian_id}/exit', [DashboardsiswaController::class, 'exitExam'])->name('Ujian.exit');


        // 4) Mata Pelajaran (butuh id_kurikulum, id_tahun_ajaran, id_semester)
        Route::get('/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel', [EnrollSiswaController::class, 'mataPelajaran'])
            ->name('enroll.mapel');

        // 5) Kursus (butuh id_kurikulum, id_tahun_ajaran, id_semester, id_mata_pelajaran)
        Route::get('/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel/{id_mata_pelajaran}/kursus', [EnrollSiswaController::class, 'kursus'])
            ->name('enroll.kursus');
        Route::post('/enroll/kursus/enroll', [EnrollSiswaController::class, 'enroll'])->name('kursus.enroll.password');
        Route::post('courses/{id_kursus}/ujian/{id_ujian}/submit', [DashboardsiswaController::class, 'submitUjian'])->name('Ujian.submit');
        Route::get('/latihan/kurikulum', [PracticeQuestionController::class, 'kurikulum'])->name('latihan.kurikulum');
        Route::get('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran', [PracticeQuestionController::class, 'tahunAjaran'])->name('latihan.tahunajaran');
        Route::get('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester', [PracticeQuestionController::class, 'semester'])
            ->name('latihan.semester');
        // 4) Mata Pelajaran (butuh id_kurikulum, id_tahun_ajaran, id_semester)

        Route::get('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel', [PracticeQuestionController::class, 'mataPelajaran'])
            ->name('latihan.mapel');
        Route::get('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel/{id_mata_pelajaran}/kelas', [PracticeQuestionController::class, 'kelas'])
            ->name('latihan.kelas');
        Route::get('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel/{id_mata_pelajaran}/kelas/{id_kelas}/topik', [PracticeQuestionController::class, 'topikByKelas'])
            ->name('latihan.kelas.topik');
        Route::get('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel/{id_mata_pelajaran}/kelas/{id_kelas}/topik/{id_latihan}', [PracticeQuestionController::class, 'viewLatihan'])->name('latihan.kelas.topik.detail');
        Route::post('/latihan/kurikulum/{id_kurikulum}/tahun-ajaran/{id_tahun_ajaran}/semester/{id_semester}/mapel/{id_mata_pelajaran}/kelas/{id_kelas}/topik/{id_latihan}/submit', [PracticeQuestionController::class, 'submitLatihan'])->name('latihan.submit');
        
        // routes/web.php

        Route::get('/grades', [DashboardsiswaController::class, 'nilaiSiswa'])
            ->name('Grades.index');

        Route::get('/grades/course/{id_kursus}', [DashboardsiswaController::class, 'nilaiKursus'])
            ->name('Grades.course');

        Route::post('/kursus/{id_kursus}/ujian/{id_ujian}/submit', [UjianController::class, 'submitUjian'])
    ->name('submitUjian');

        Route::resource('/JawabanSiswaQuiz', JawabanSiswaQuizController::class);
        Route::resource('/Ujian', UjianController::class);
        Route::resource('/JawabanSiswaUjian', JawabanSiswaUjianController::class);
        Route::resource('/LatihanSoal', LatihanSoalController::class);
        Route::resource('/JawabanSiswaLatihanSoal', JawabanSiswaLatihanSoalController::class);
        Route::resource('/MataPelajaran', MataPelajaranController::class);
        Route::resource('/Kurikulum', KurikulumController::class);
        Route::resource('/Kelas', KelasController::class);
        Route::resource('/Profil', ProfilController::class);
        Route::get('/courses/{id_kursus}/ujian/{id_ujian}/nilai', [NilaiController::class, 'gradeUjianSiswaSelf'])->name('Course.ujian.nilai');
    });
});

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared!';
});


// Route untuk logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/get-guru/{mata_pelajaran}', function ($mata_pelajaran) {
    // Menemukan mata pelajaran berdasarkan ID
    $mataPelajaran = mata_pelajaran::find($mata_pelajaran);

    // Mendapatkan guru yang mengajar mata pelajaran tersebut
    $gurus = $mataPelajaran ? $mataPelajaran->gurus : [];

    // Mengembalikan data guru dalam format JSON
    return response()->json(['gurus' => $gurus]);
});
require __DIR__ . '/auth.php';
