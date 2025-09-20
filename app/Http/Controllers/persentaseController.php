<?php

namespace App\Http\Controllers;

use App\Models\persentase;
use App\Models\kursus;
use App\Models\guru;
use Illuminate\Support\Facades\Log;
use App\Models\tipe_ujian;
use App\Models\tipe_persentase;  // Menggunakan penulisan snake_case untuk model
use Illuminate\Http\Request;

class PersentaseController extends Controller
{
    // Menampilkan data persentase berdasarkan kursus
    public function index()
    {
        $user = auth()->user();

        $guru = $user->guru; // This will automatically load the guru relationship

        $courses = Kursus::where('id_guru', $guru->id_guru)->get();

        $persentases = persentase::with(['kursus', 'tipeUjian'])->get();

        // Kirim variabel 'courses' dan 'persentases' ke view
        return view('Role.Guru.Nilai.index', compact('persentases', 'courses', 'user', 'guru'));
    }

    // Menampilkan halaman create
    public function create(Request $request)
    {
        $user = auth()->user();
        $guru = guru::where('id_user', auth()->user()->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        $id_kursus = $request->query('id_kursus');

        $kursus = Kursus::where('id_guru', $guru->id_guru)->get();

        $tipeUjian = tipe_ujian::all();
        $tipePersentase = tipe_persentase::all();

        return view('Role.Guru.Nilai.create', compact('id_kursus', 'kursus', 'tipeUjian', 'tipePersentase', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kursus' => 'required|exists:kursus,id_kursus',
            'persentase_kuis' => 'required|numeric|min:0|max:100',
            'persentase_UTS' => 'required|numeric|min:0|max:100',
            'persentase_UAS' => 'required|numeric|min:0|max:100',
        ]);

        // Cek apakah sudah ada persentase untuk kursus ini
        $existingPersentase = persentase::where('id_kursus', $validated['id_kursus'])->exists();
        if ($existingPersentase) {
            return redirect()->back()->withErrors(['error' => 'Persentase sudah pernah diatur untuk kursus ini.']);
        }

        // Mengecek apakah total persentase lebih dari 100
        $totalPersentase = $validated['persentase_kuis'] + $validated['persentase_UTS'] + $validated['persentase_UAS'];
        if ($totalPersentase > 100) {
            return redirect()->back()->withErrors(['error' => 'Jumlah persentase tidak boleh lebih dari 100.']);
        }

        $tipeUjianKuis = 1;
        $tipeUjianUTS = 2;
        $tipeUjianUAS = 3;

        $tipePersentaseKuis = 1;
        $tipePersentaseUTS = 2;
        $tipePersentaseUAS = 3;

        // Create the persentase for Kuis, UTS, and UAS
        persentase::create([
            'id_kursus' => $validated['id_kursus'],
            'id_tipe_ujian' => $tipeUjianKuis, // Menggunakan ID
            'id_tipe_persentase' => $tipePersentaseKuis, // Menggunakan ID
            'persentase' => $validated['persentase_kuis'],
        ]);

        persentase::create([
            'id_kursus' => $validated['id_kursus'],
            'id_tipe_ujian' => $tipeUjianUTS, // Menggunakan ID
            'id_tipe_persentase' => $tipePersentaseUTS, // Menggunakan ID
            'persentase' => $validated['persentase_UTS'],
        ]);

        persentase::create([
            'id_kursus' => $validated['id_kursus'],
            'id_tipe_ujian' => $tipeUjianUAS, // Menggunakan ID
            'id_tipe_persentase' => $tipePersentaseUAS, // Menggunakan ID
            'persentase' => $validated['persentase_UAS'],
        ]);

        return redirect()->route('Guru.Persentase.index')->with('success', 'Persentase berhasil disimpan.');
    }


    public function edit(Request $request, $id_kursus)
    {
        $user = auth()->user();
        $guru = guru::where('id_user', auth()->user()->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        // Mengambil data persentase berdasarkan id_kursus
        $persentase = persentase::where('id_kursus', $id_kursus)->get();

        // Ambil data kursus, tipe ujian, dan tipe persentase yang diperlukan untuk dropdown
        $kursus = kursus::where('id_guru', $guru->id_guru)->get();
        $tipeUjian = tipe_ujian::all();
        $tipePersentase = tipe_persentase::all();

        // Pastikan Anda mengirimkan id_kursus ke view
        return view('Role.Guru.Nilai.edit', compact('persentase', 'user', 'kursus', 'tipeUjian', 'tipePersentase', 'id_kursus'));
    }

    public function update(Request $request, $id_kursus)
    {
        // Validasi input data
        $validated = $request->validate([
            'id_kursus' => 'required|exists:kursus,id_kursus',
            'persentase_kuis' => 'required|numeric|min:0|max:100',
            'persentase_UTS' => 'required|numeric|min:0|max:100',
            'persentase_UAS' => 'required|numeric|min:0|max:100',
        ]);

        // Mengecek apakah total persentase lebih dari 100
        $totalPersentase = $validated['persentase_kuis'] + $validated['persentase_UTS'] + $validated['persentase_UAS'];
        if ($totalPersentase > 100) {
            return redirect()->back()->withErrors(['error' => 'Jumlah persentase tidak boleh lebih dari 100.']);
        }

        // Mengupdate data berdasarkan id_kursus
        $persentaseKuis = persentase::where('id_kursus', $id_kursus)->where('id_tipe_ujian', 1)->first();
        $persentaseUTS = persentase::where('id_kursus', $id_kursus)->where('id_tipe_ujian', 2)->first();
        $persentaseUAS = persentase::where('id_kursus', $id_kursus)->where('id_tipe_ujian', 3)->first();

        // Update atau buat record untuk Kuis
        if ($persentaseKuis) {
            $persentaseKuis->update(['persentase' => $validated['persentase_kuis']]);
        } else {
            persentase::create([
                'id_kursus' => $id_kursus,
                'id_tipe_ujian' => 1,
                'persentase' => $validated['persentase_kuis'],
            ]);
        }

        // Update atau buat record untuk UTS
        if ($persentaseUTS) {
            $persentaseUTS->update(['persentase' => $validated['persentase_UTS']]);
        } else {
            persentase::create([
                'id_kursus' => $id_kursus,
                'id_tipe_ujian' => 2,
                'persentase' => $validated['persentase_UTS'],
            ]);
        }

        // Update atau buat record untuk UAS
        if ($persentaseUAS) {
            $persentaseUAS->update(['persentase' => $validated['persentase_UAS']]);
        } else {
            persentase::create([
                'id_kursus' => $id_kursus,
                'id_tipe_ujian' => 3,
                'persentase' => $validated['persentase_UAS'],
            ]);
        }

        // Redirect setelah sukses update
        return redirect()->route('Guru.Persentase.index')->with('success', 'Persentase berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $persentase = persentase::findOrFail($id);
        $persentase->delete();

        return redirect()->route('Guru.Persentase.index')->with('success', 'Persentase berhasil dihapus.');
    }
}
