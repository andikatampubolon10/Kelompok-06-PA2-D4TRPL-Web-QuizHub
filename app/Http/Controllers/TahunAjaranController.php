<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\Kurikulum;
use App\Models\Semester;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TahunAjaranController extends Controller
{
    public function index($id_kurikulum)
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Cari kurikulum berdasarkan id_kurikulum
    $kurikulum = Kurikulum::find($id_kurikulum);
    if (!$kurikulum) {
        return redirect()->back()->with('error', 'Kurikulum tidak ditemukan.');
    }

    $operator = Operator::where('id_user', $user->id)->first();

    $tahunAjaran = TahunAjaran::where('id_operator', $operator->id_operator)
        ->where('id_kurikulum', $kurikulum->id_kurikulum)
        ->with('operator')
        ->get();

    return view('Role.Operator.Tahun Ajaran.index', compact('tahunAjaran', 'user', 'kurikulum'));
}

   public function create($id_kurikulum)
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    $kurikulum = Kurikulum::find($id_kurikulum);

    if (!$kurikulum) {
        return redirect()->back()->with('error', 'Kurikulum tidak ditemukan.');
    }

    $operator = Operator::where('id_user', $user->id)->first();
    
    if (!$operator) {
        return redirect()->back()->with('error', 'Data operator tidak ditemukan.');
    }

    Log::info('ID Kurikulum yang diterima di create', ['id_kurikulum' => $kurikulum->id_kurikulum]);

    return view('Role.Operator.Tahun Ajaran.create', compact('user', 'operator', 'kurikulum'));
}


    public function store(Request $request)
{
    Log::info('Menerima permintaan untuk menyimpan tahun ajaran', $request->all());

    $validated = $request->validate([
        'Nama_Tahun_Ajaran' => 'required|string|unique:tahun_ajaran,Nama_Tahun_Ajaran',
        'Mulai_Tahun_Ajaran' => 'required|date',
        'Selesai_Tahun_Ajaran' => 'required|date',
        'id_kurikulum' => 'required|exists:kurikulum,id_kurikulum',
    ]);

    $user = auth()->user();
    $operator = Operator::where('id_user', $user->id)->first();

    if (!$operator) {
        return redirect()->back()->with('error', 'Operator tidak ditemukan.');
    }

    $status = now()->between($request->Mulai_Tahun_Ajaran, $request->Selesai_Tahun_Ajaran) ? 'Aktif' : 'Tidak Aktif';

    try {
        // Buat Tahun Ajaran
        $tahunAjaran = TahunAjaran::create([
            'Nama_Tahun_Ajaran' => $request->Nama_Tahun_Ajaran,
            'Mulai_Tahun_Ajaran' => $request->Mulai_Tahun_Ajaran,
            'Selesai_Tahun_Ajaran' => $request->Selesai_Tahun_Ajaran,
            'Status' => $status,
            'id_operator' => $operator->id_operator,
            'id_kurikulum' => $request->id_kurikulum,
        ]);

        // Otomatis buat Semester Ganjil & Genap
        Semester::create([
            'nama_semester' => 'Ganjil',
            'ID_Tahun_Ajaran' => $tahunAjaran->ID_Tahun_Ajaran,
        ]);

        Semester::create([
            'nama_semester' => 'Genap',
            'ID_Tahun_Ajaran' => $tahunAjaran->ID_Tahun_Ajaran,
        ]);

        Log::info('Tahun ajaran & semester berhasil disimpan', [
            'tahun_ajaran' => $tahunAjaran->Nama_Tahun_Ajaran,
            'semester' => ['Ganjil', 'Genap']
        ]);

    } catch (\Exception $e) {
        Log::error('Terjadi kesalahan saat menyimpan tahun ajaran/semester', [
            'error_message' => $e->getMessage(),
        ]);
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }

    return redirect()->route('Operator.TahunAjaran.index', ['id_kurikulum' => $request->id_kurikulum])
        ->with('success', 'Tahun ajaran & semester berhasil ditambahkan.');
}


    public function update(Request $request, string $id)
    {
        $request->validate([
            'Nama_Tahun_Ajaran' => 'required|string|unique:tahun_ajaran,Nama_Tahun_Ajaran,' . $id . ',ID_Tahun_Ajaran',
            'Mulai_Tahun_Ajaran' => 'required|date',
            'Selesai_Tahun_Ajaran' => 'required|date',
        ], [
            'Nama_Tahun_Ajaran.required' => 'Nama tahun ajaran harus diisi.',
            'Nama_Tahun_Ajaran.string' => 'Nama tahun ajaran harus berupa teks.',
            'Nama_Tahun_Ajaran.max' => 'Nama tahun ajaran tidak boleh lebih dari 10 karakter.',
            'Nama_Tahun_Ajaran.unique' => 'Nama tahun ajaran sudah ada, silakan pilih nama lain.',
            'Mulai_Tahun_Ajaran.required' => 'Tanggal mulai tahun ajaran harus diisi.',
            'Mulai_Tahun_Ajaran.date' => 'Tanggal mulai tahun ajaran harus berupa format tanggal yang valid.',
            'Selesai_Tahun_Ajaran.required' => 'Tanggal selesai tahun ajaran harus diisi.',
            'Selesai_Tahun_Ajaran.date' => 'Tanggal selesai tahun ajaran harus berupa format tanggal yang valid.',
        ]);

        $tahunAjaran = TahunAjaran::findOrFail($id);

        $status = now()->between($request->Mulai_Tahun_Ajaran, $request->Selesai_Tahun_Ajaran) ? 'Aktif' : 'Tidak Aktif';

        $tahunAjaran->update([
            'Nama_Tahun_Ajaran' => $request->Nama_Tahun_Ajaran,
            'Mulai_Tahun_Ajaran' => $request->Mulai_Tahun_Ajaran,
            'Selesai_Tahun_Ajaran' => $request->Selesai_Tahun_Ajaran,
            'Status' => $status,
        ]);

        return redirect()->route('Operator.TahunAjaran.index', ['id_kurikulum' => $tahunAjaran->id_kurikulum])
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $id_kurikulum = $tahunAjaran->id_kurikulum;
        $tahunAjaran->delete();

        return redirect()->route('Operator.TahunAjaran.index', ['id_kurikulum' => $id_kurikulum])
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
