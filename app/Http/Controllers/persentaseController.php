<?php

namespace App\Http\Controllers;

use App\Models\Persentase;
use App\Models\Kursus;
use App\Models\Guru;
use App\Models\tipe_persentase;
use App\Models\tipe_ujian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersentaseController extends Controller
{
    // Index: daftar persentase per kursus guru
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru ?? null;

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        // Dapatkan kursus milik guru
        $courses = Kursus::where('id_guru', $guru->id_guru)->get();

        // Dapatkan persentase dengan eager load
        $persentases = Persentase::with(['kursus', 'tipeUjian', 'tipePersentase'])
            ->whereIn('id_kursus', $courses->pluck('id_kursus'))
            ->get();

        return view('Role.Guru.Nilai.index', compact('persentases', 'courses', 'user', 'guru'));
    }

    // Create: tampilkan form create persentase untuk kursus
    public function create(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru ?? null;

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        $id_kursus = $request->query('id_kursus'); // bisa null

        // Ambil kursus milik guru untuk dropdown
        $kursus = Kursus::where('id_guru', $guru->id_guru)->get();

        $tipeUjian = tipe_ujian::all();
        $tipePersentase = tipe_persentase::all();

        // Validasi opsional id_kursus jika diberikan
        if ($id_kursus !== null) {
            $valid = Kursus::where('id_kursus', $id_kursus)
                ->where('id_guru', $guru->id_guru)
                ->exists();

            if (!$valid) {
                return redirect()->back()->withErrors(['error' => 'Kursus tidak ditemukan untuk guru ini.']);
            }
        }

        return view('Role.Guru.Nilai.create', [
            'id_kursus' => $id_kursus,
            'kursus' => $kursus,
            'tipeUjian' => $tipeUjian,
            'tipePersentase' => $tipePersentase,
            'user' => $user,
        ]);
    }

    // Store: simpan 3 persentase (Kuis, UTS, UAS)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kursus' => 'required|exists:kursus,id_kursus',
            'persentase_kuis' => 'required|numeric|min:0|max:100',
            'persentase_UTS' => 'required|numeric|min:0|max:100',
            'persentase_UAS' => 'required|numeric|min:0|max:100',
        ]);

        // Cek duplikasi: pastikan tidak ada entri untuk kursus dan tipe persentase/ujian sama
        $existsKuis = Persentase::where('id_kursus', $validated['id_kursus'])
            ->where('id_tipe_ujian', 1)
            ->exists();
        $existsUTS  = Persentase::where('id_kursus', $validated['id_kursus'])
            ->where('id_tipe_ujian', 2)
            ->exists();
        $existsUAS  = Persentase::where('id_kursus', $validated['id_kursus'])
            ->where('id_tipe_ujian', 3)
            ->exists();

        if ($existsKuis || $existsUTS || $existsUAS) {
            return redirect()->back()->withErrors(['error' => 'Persentase untuk kursus ini telah diatur sebelumnya.']);
        }

        // Total harus tidak melebihi 100
        $total = $validated['persentase_kuis'] + $validated['persentase_UTS'] + $validated['persentase_UAS'];
        if ($total > 100) {
            return redirect()->back()->withErrors(['error' => 'Jumlah persentase tidak boleh lebih dari 100.']);
        }

        DB::beginTransaction();
        try {
            // ID tipe persentase sesuai konvensi
            Persentase::create([
                'id_kursus' => $validated['id_kursus'],
                'id_tipe_ujian' => 1,
                'id_tipe_persentase' => 1,
                'persentase' => $validated['persentase_kuis'],
            ]);

            Persentase::create([
                'id_kursus' => $validated['id_kursus'],
                'id_tipe_ujian' => 2,
                'id_tipe_persentase' => 2,
                'persentase' => $validated['persentase_UTS'],
            ]);

            Persentase::create([
                'id_kursus' => $validated['id_kursus'],
                'id_tipe_ujian' => 3,
                'id_tipe_persentase' => 3,
                'persentase' => $validated['persentase_UAS'],
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan persentase: ' . $e->getMessage()]);
        }

        return redirect()->route('Guru.Persentase.index')->with('success', 'Persentase berhasil disimpan.');
    }

    // Edit: tampilkan data persentase untuk id_kursus
    public function edit(Request $request, $id_kursus)
    {
        $user = Auth::user();
        $guru = $user->guru ?? null;
        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        // Ambil data persentase untuk kursus ini
        $persentase = Persentase::where('id_kursus', $id_kursus)->get();

        $kursus = Kursus::where('id_guru', $guru->id_guru)->get();
        $tipeUjian = tipe_ujian::all();
        $tipePersentase = tipe_persentase::all();

        return view('Role.Guru.Nilai.edit', compact('persentase', 'user', 'kursus', 'tipeUjian', 'tipePersentase', 'id_kursus'));
    }

    // Update: per kursus, per tipe ujian
    public function update(Request $request, $id_kursus)
    {
        $validated = $request->validate([
            'persentase_kuis' => 'required|numeric|min:0|max:100',
            'persentase_UTS' => 'required|numeric|min:0|max:100',
            'persentase_UAS' => 'required|numeric|min:0|max:100',
        ]);

        $total = $validated['persentase_kuis'] + $validated['persentase_UTS'] + $validated['persentase_UAS'];
        if ($total > 100) {
            return redirect()->back()->withErrors(['error' => 'Jumlah persentase tidak boleh lebih dari 100.']);
        }

        DB::beginTransaction();
        try {
            // Kuis
            $persKuis = Persentase::where('id_kursus', $id_kursus)->where('id_tipe_ujian', 1)->first();
            if ($persKuis) {
                $persKuis->update(['persentase' => $validated['persentase_kuis']]);
            } else {
                Persentase::create([
                    'id_kursus' => $id_kursus,
                    'id_tipe_ujian' => 1,
                    'id_tipe_persentase' => 1,
                    'persentase' => $validated['persentase_kuis'],
                ]);
            }

            // UTS
            $persUTS = Persentase::where('id_kursus', $id_kursus)->where('id_tipe_ujian', 2)->first();
            if ($persUTS) {
                $persUTS->update(['persentase' => $validated['persentase_UTS']]);
            } else {
                Persentase::create([
                    'id_kursus' => $id_kursus,
                    'id_tipe_ujian' => 2,
                    'id_tipe_persentase' => 2,
                    'persentase' => $validated['persentase_UTS'],
                ]);
            }

            // UAS
            $persUAS = Persentase::where('id_kursus', $id_kursus)->where('id_tipe_ujian', 3)->first();
            if ($persUAS) {
                $persUAS->update(['persentase' => $validated['persentase_UAS']]);
            } else {
                Persentase::create([
                    'id_kursus' => $id_kursus,
                    'id_tipe_ujian' => 3,
                    'id_tipe_persentase' => 3,
                    'persentase' => $validated['persentase_UAS'],
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui persentase: ' . $e->getMessage()]);
        }

        return redirect()->route('Guru.Persentase.index')->with('success', 'Persentase berhasil diperbarui.');
    }

    // Destroy: hapus semua persentase terkait kursus tertentu
    public function destroy($id_kursus)
    {
        DB::beginTransaction();
        try {
            Persentase::where('id_kursus', $id_kursus)->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus persentase: ' . $e->getMessage()]);
        }

        return redirect()->route('Guru.Persentase.index')->with('success', 'Persentase berhasil dihapus untuk kursus tersebut.');
    }
}