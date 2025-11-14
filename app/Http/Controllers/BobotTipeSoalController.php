<?php

namespace App\Http\Controllers;

use App\Models\BobotTipeSoal;
use App\Models\tipe_soal;
use App\Models\Ujian;
use Illuminate\Http\Request;

class BobotTipeSoalController extends Controller
{
    // Tampilkan daftar bobot per tipe soal untuk sebuah ujian (id_ujian di URL)
    public function index(Request $request)
{
    $idUjian = $request->query('id_ujian');

    $ujian = Ujian::find($idUjian);

    // Load 3 tipe soal (Fixed)
    $tipes = tipe_soal::all(); // Pilihan Berganda, Benar Salah, Isian

    // Load bobot milik ujian
    $bobotTipeSoals = BobotTipeSoal::where('id_ujian', $idUjian)->get();

    return view('Role.Guru.Course.Soal.bobot_tipe_soal_index', [
        'ujian' => $ujian,
        'tipes' => $tipes,
        'bobotTipeSoals' => $bobotTipeSoals,
    ]);
}

    // Tampilkan form create
    public function create(Request $request)
    {
        $idUjian = $request->query('id_ujian');
        $tipeSoal = tipe_soal::all();

        return view('Role.Guru.Course.Soal.bobot_tipe_soal_create', [
            'id_ujian' => $idUjian,
            'tipeSoal' => $tipeSoal,
        ]);
    }

    // Store bobot untuk tipe soal pada ujian tertentu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tipe_soal' => 'required|exists:tipe_soal,id_tipe_soal',
            'id_ujian' => 'required|exists:ujian,id_ujian',
            'bobot' => 'required|numeric|min:0',
        ]);

        // Hitung total bobot saat ini untuk id_ujian tertentu
        $totalBobotSaatIni = BobotTipeSoal::where('id_ujian', $validated['id_ujian'])
            ->sum('bobot');

        $totalBaru = $totalBobotSaatIni + $validated['bobot'];

        if ($totalBaru > 100) {
            return redirect()->back()->withErrors(['error' => 'Total bobot untuk ujian ini tidak boleh melebihi 100.'])->withInput();
        }

        BobotTipeSoal::create([
            'id_tipe_soal' => $validated['id_tipe_soal'],
            'id_ujian' => $validated['id_ujian'],
            'bobot' => $validated['bobot'],
        ]);

        return redirect()->route('Guru.BobotTipeSoal.index', ['id_ujian' => $validated['id_ujian']])
            ->with('success', 'Bobot tipe soal berhasil disimpan.');
    }

    // Tampilkan form edit
    public function edit(Request $request, $id_bobot_tipe_soal)
    {
        $bobot = BobotTipeSoal::findOrFail($id_bobot_tipe_soal);
        $tipeSoal = tipe_soal::all();

        return view('Role.Guru.Course.Soal.bobot_tipe_soal_edit', [
            'bobot' => $bobot,
            'tipeSoal' => $tipeSoal,
        ]);
    }

    public function update(Request $request, $id_bobot_tipe_soal)
    {
        $validated = $request->validate([
            'id_tipe_soal' => 'required|exists:tipe_soal,id_tipe_soal',
            'id_ujian' => 'required|exists:ujian,id_ujian',
            'bobot' => 'required|numeric|min:0',
        ]);

        $bobot = BobotTipeSoal::findOrFail($id_bobot_tipe_soal);

        // Jika id_ujian mungkin tidak terkirim dari form, fallback ke yang ada di model
        $idUjian = $validated['id_ujian'] ?? $bobot->id_ujian;

        // Hitung total bobot tanpa entri ini, lalu tambahkan bobot baru
        $totalTanpaIni = BobotTipeSoal::where('id_ujian', $idUjian)
            ->where('id_bobot_tipe_soal', '!=', $id_bobot_tipe_soal)
            ->sum('bobot');

        $totalBaru = $totalTanpaIni + $validated['bobot'];

        if ($totalBaru > 100) {
            return redirect()->back()->withErrors(['error' => 'Total bobot untuk ujian ini tidak boleh melebihi 100.'])->withInput();
        }

        $bobot->update([
            'id_tipe_soal' => $validated['id_tipe_soal'],
            'id_ujian' => $idUjian,
            'bobot' => $validated['bobot'],
        ]);

        return redirect()->route('Guru.BobotTipeSoal.index', ['id_ujian' => $idUjian])
            ->with('success', 'Bobot tipe soal berhasil diperbarui.');
    }
    
    // Hapus bobot tipe soal
    public function destroy(Request $request, $id_bobot_tipe_soal)
    {
        $bobot = BobotTipeSoal::findOrFail($id_bobot_tipe_soal);
        $idUjian = $bobot->id_ujian;
        $bobot->delete();

        return redirect()->route('Guru.BobotTipeSoal.index', ['id_ujian' => $idUjian])
            ->with('success', 'Bobot tipe soal berhasil dihapus.');
    }
}
