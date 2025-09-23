<?php

namespace App\Http\Controllers;

use App\Models\mata_pelajaran;
use App\Models\kurikulum;
use App\Models\Operator;
use App\Models\Semester;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $operator = Operator::where('id_user', $user->id)->first();

        if ($request->has('id_semester') && $request->id_semester != '') {
            $mataPelajarans = mata_pelajaran::where('id_semester', $request->id_semester)
                ->where('id_operator', $operator->id_operator)
                ->with(['operator', 'semester'])
                ->get();
        } else {
            $mataPelajarans = mata_pelajaran::where('id_operator', $operator->id_operator)
                ->with(['operator', 'semester'])
                ->get();
        }

        $semesters = Semester::all();
        $activeSemester = $semesters->first();

        $mata_pelajaran = mata_pelajaran::where('id_operator', $operator->id_operator)->firstOrFail();

        return view('Role.Operator.Mapel.index', compact('mataPelajarans','mata_pelajaran', 'semesters', 'activeSemester', 'user'));
        }

    public function create(Request $request)
    {
        $user = auth()->user();

        // Ambil id_semester dari query parameter URL
        $semesterId = $request->get('id_semester');

        $semester = null;

        // Ambil semua data semester untuk dropdown
        $semesters = Semester::all();

        // Jika ada id_semester, cari semester berdasarkan id_semester
        if ($semesterId) {
            $semester = Semester::where('id_semester', $semesterId)->first(); // pakai 'id_semester' bukan 'id'
        }

        return view('Role.Operator.Mapel.create', compact('user', 'semester', 'semesters'));
    }

    public function store(Request $request)
    {
        // Validation with custom messages
        $request->validate([
            'nama_mata_pelajaran' => 'required|unique:mata_pelajaran',
            'id_semester' => 'required|exists:semester,id_semester',
        ], [
            'nama_mata_pelajaran.required' => 'Nama mata pelajaran harus diisi.',
            'nama_mata_pelajaran.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'id_semester.required' => 'Semester harus dipilih.',
            'id_semester.exists' => 'Semester yang dipilih tidak valid.',
        ]);

        $idUser  = auth()->user()->id;
        $operator = Operator::where('id_user', $idUser)->first();

        mata_pelajaran::create([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
            'id_operator' => $operator->id_operator,
            'id_semester' => $request->id_semester,
        ]);

        $redirectUrl = route('Operator.MataPelajaran.index');
        if ($request->id_semester) {
            $redirectUrl .= '?id_semester=' . $request->id_semester;
        }

        return redirect($redirectUrl)
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $mataPelajaran = mata_pelajaran::with(['operator', 'semester'])->findOrFail($id);
        return view('Role.Operator.Mapel.show', compact('mataPelajaran'));
    }

    public function edit(string $id)
    {
        $user = auth()->user();
        $operator = Operator::where('id_user', $user->id)->first();

        $mataPelajaran = mata_pelajaran::with('semester')->findOrFail($id);

        // Check if the mata pelajaran belongs to the current operator
        if ($mataPelajaran->id_operator !== $operator->id_operator) {
            return redirect()->route('Operator.MataPelajaran.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit mata pelajaran ini.');
        }

        $semesters = Semester::all();

        return view('Role.Operator.Mapel.edit', compact('mataPelajaran', 'semesters', 'user'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|unique:mata_pelajaran,nama_mata_pelajaran,' . $id . ',id_mata_pelajaran',
            'id_semester' => 'required|exists:semester,id_semester',
        ], [
            'nama_mata_pelajaran.required' => 'Nama mata pelajaran harus diisi.',
            'nama_mata_pelajaran.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'id_semester.required' => 'Semester harus dipilih.',
            'id_semester.exists' => 'Semester yang dipilih tidak valid.',
        ]);

        $user = auth()->user();
        $operator = Operator::where('id_user', $user->id)->first();

        $mataPelajaran = mata_pelajaran::findOrFail($id);

        // Check if the mata pelajaran belongs to the current operator
        if ($mataPelajaran->id_operator !== $operator->id_operator) {
            return redirect()->route('Operator.MataPelajaran.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit mata pelajaran ini.');
        }

        $mataPelajaran->update([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
            'id_semester' => $request->id_semester,
        ]);

        $redirectUrl = route('Operator.MataPelajaran.index');
        if ($request->id_semester) {
            $redirectUrl .= '?id_semester=' . $request->id_semester;
        }

        return redirect($redirectUrl)
            ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $operator = Operator::where('id_user', $user->id)->first();

        $mataPelajaran = mata_pelajaran::findOrFail($id);

        // Check if the mata pelajaran belongs to the current operator
        if ($mataPelajaran->id_operator !== $operator->id_operator) {
            return redirect()->route('Operator.MataPelajaran.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus mata pelajaran ini.');
        }

        $mataPelajaran->delete();

        return redirect()->route('Operator.MataPelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
