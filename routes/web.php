<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\CourseController;
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
use App\Models\mata_pelajaran;
use App\Models\Guru;
use App\Http\Middleware\CheckOperatorStatus;
use App\Models\TahunAjaran;

// ==================================
// 🔑 ROUTE LOGIN / LOGOUT
// ==================================
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login.form');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ==================================
// 🏠 DASHBOARD (SETELAH LOGIN)
// ==================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================================
// 🔒 ROUTE YANG BUTUH LOGIN
// ==================================
Route::middleware('auth')->group(function () {
    // Profil user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==============================
    // 👑 ADMIN
    // ==============================
    Route::prefix('Admin')->name('Admin.')->middleware('role:Admin')->group(function () {
        Route::resource('/Akun', OperatorController::class)->parameters(['Akun' => 'user']);
        Route::get('/Akun/{user}/edit', [OperatorController::class, 'edit'])->name('Akun.edit');
        Route::get('/Akun/{user}', [OperatorController::class, 'show'])->name('Akun.show');
        Route::resource('/Bisnis', BisnisController::class)->parameters(['Bisnis' => 'id_bisnis']);
    });

    // ==============================
    // 👨‍🏫 GURU
    // ==============================
    Route::prefix('Guru')->name('Guru.')->middleware('role:Guru')->group(function () {
        Route::get('/Course/{id_guru}', [CourseController::class, 'index'])->name('Course.index');
        Route::resource('/Course', CourseController::class);
        Route::resource('/Siswa', SiswaController::class);
        Route::resource('/Latihan', LatihanSoalController::class);
        Route::get('/Latihan/{id_latihan}/edit', [LatihanSoalController::class, 'edit'])->name('Latihan.edit');
        Route::resource('/LatihanSoalSoal', LatihanSoalSoalController::class);
        Route::resource('/Kelas', KelasController::class);
        Route::resource('/MataPelajaran', MataPelajaranController::class);
        Route::resource('/Ujian', UjianController::class);
        Route::get('/ListSiswa/{id_kursus}', [ListSiswaController::class, 'index'])->name('ListSiswa');
        Route::get('/nilai/export/{id_kursus}', [ListSiswaController::class, 'exportNilai'])->name('nilai.export');
        Route::get('/nilai/{id_kursus}', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/calculate-nilai/{id_kursus}/{id_siswa}', [NilaiController::class, 'calculateNilai'])->name('nilai.calculate');
        Route::get('/nilai-breakdown/{id_kursus}/{id_siswa}', [NilaiController::class, 'getScoreBreakdown'])->name('nilai.breakdown');
        Route::resource('/Soal', SoalController::class);
        Route::resource('/Persentase', persentaseController::class);
        Route::get('/Soal/create/{type}', [SoalController::class, 'create'])->name('Soal.create');
        Route::get('/Soal/preview/{id}', [SoalController::class, 'preview'])->name('Soal.preview');
        Route::resource('/Kurikulum', KurikulumController::class);
        Route::resource('/Attempt', AttemptController::class);
        Route::resource('/Materi', MateriController::class);
        Route::resource('/JawabanSiswaLatihanSoal', JawabanSiswaLatihanSoalController::class);
        Route::resource('/JawabanSiswaUjian', JawabanSiswaUjianController::class);
        Route::resource('/Nilai', NilaiController::class);
        Route::post('/reset-recalculate-nilai/{id_kursus}', [ListSiswaController::class, 'resetAndRecalculateNilai']);
    });

    // ==============================
    // 🛠 OPERATOR
    // ==============================
    Route::prefix('Operator')->name('Operator.')->middleware('role:Operator')->group(function () {
        Route::resource('/Guru', GuruController::class);
        Route::get('/Course/beranda', [CourseController::class, 'beranda'])->name('Course.beranda');
        Route::resource('/Course', CourseController::class);
        Route::resource('semester', SemesterController::class);
        Route::get('/Guru/upload', [GuruController::class, 'upload'])->name('Guru.upload');
        Route::post('/Guru/import', [GuruController::class, 'import'])->name('Guru.import');

        Route::resource('/Siswa', SiswaController::class);
        Route::get('/Siswa/upload', [SiswaController::class, 'upload'])->name('Siswa.upload');
        Route::post('/Siswa/import', [SiswaController::class, 'import'])->name('Siswa.import');

<<<<<<< HEAD
        Route::get('/TahunAjaran/{id_tahun_ajaran}/edit', [TahunAjaranController::class, 'edit'])
            ->name('TahunAjaran.edit');

        Route::put('/TahunAjaran/{id_tahun_ajaran}', [TahunAjaranController::class, 'update'])
            ->name('TahunAjaran.update');

        // Gunakan resource untuk TahunAjaran, create sudah otomatis ada
=======
        // Tahun Ajaran per Kurikulum
>>>>>>> 4fec7715132b29472c95658f2dce39503adea415
        Route::prefix('/Kurikulum/{id_kurikulum}')->group(function () {
            Route::get('/TahunAjaran', [TahunAjaranController::class, 'index'])->name('TahunAjaran.index');
            Route::get('/TahunAjaran/create', [TahunAjaranController::class, 'create'])->name('TahunAjaran.create');
            Route::post('/TahunAjaran', [TahunAjaranController::class, 'store'])->name('TahunAjaran.store');
            Route::get('/TahunAjaran/{id_tahun_ajaran}/edit', [TahunAjaranController::class, 'edit'])->name('TahunAjaran.edit');
            Route::put('/TahunAjaran/{id_tahun_ajaran}', [TahunAjaranController::class, 'update'])->name('TahunAjaran.update');
            Route::delete('/TahunAjaran/{id_tahun_ajaran}', [TahunAjaranController::class, 'destroy'])->name('TahunAjaran.destroy');
        });

        Route::resource('/Kelas', KelasController::class);
        Route::resource('/Kurikulum', KurikulumController::class);
        Route::resource('/MataPelajaran', MataPelajaranController::class);
    });

    // ==============================
    // 🎓 SISWA
    // ==============================
    Route::prefix('Siswa')->name('Siswa.')->middleware('role:Siswa')->group(function () {
        Route::resource('/Course', CourseController::class);
        Route::resource('/JawabanSiswaQuiz', JawabanSiswaQuizController::class);
        Route::resource('/Ujian', UjianController::class);
        Route::resource('/JawabanSiswaUjian', JawabanSiswaUjianController::class);
        Route::resource('/LatihanSoal', LatihanSoalController::class);
        Route::resource('/JawabanSiswaLatihanSoal', JawabanSiswaLatihanSoalController::class);
        Route::resource('/MataPelajaran', MataPelajaranController::class);
        Route::resource('/Kurikulum', KurikulumController::class);
        Route::resource('/Kelas', KelasController::class);
        Route::resource('/Profil', ProfilController::class);
    });
});

// ==================================
// UTILITIES
// ==================================
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared!';
});

// API untuk get guru dari mata pelajaran
Route::get('/get-guru/{mata_pelajaran}', function ($mata_pelajaran) {
    $mataPelajaran = mata_pelajaran::find($mata_pelajaran);
    $gurus = $mataPelajaran ? $mataPelajaran->gurus : [];
    return response()->json(['gurus' => $gurus]);
});
