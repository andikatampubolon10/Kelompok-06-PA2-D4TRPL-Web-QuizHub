<?php

namespace App\Http\Controllers;

use App\Models\mata_pelajaran;
use App\Models\kurikulum;
use App\Models\Operator;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
        {
            $user = auth()->user();

            $operator = Operator::where('id_user', $user->id)->first();

            $kurikulums = Kurikulum::where('id_operator', $operator->id_operator)->get();

            if ($request->has('kurikulum') && $request->kurikulum != '') {
                $mataPelajarans = mata_pelajaran::where('id_kurikulum', $request->kurikulum)
                    ->with(['operator', 'kurikulum'])
                    ->get();
            } else {
                $mataPelajarans = mata_pelajaran::with(['operator', 'kurikulum'])->get();
            }

            $mataPelajarans = mata_pelajaran::where('id_operator', $operator->id_operator)
                ->with(['operator', 'kurikulum']) // You can also load relationships if needed
                ->get();

            return view('Role.Operator.Mapel.index', compact('mataPelajarans', 'kurikulums', 'user'));
        }

    public function create()
    {
        $kurikulums = kurikulum::all();
        $user = auth()->user();
        return view('Role.Operator.Mapel.create', compact('user', 'kurikulums'));
    }

    public function store(Request $request)
    {
        // Validation with custom messages
        $request->validate([
            'nama_mata_pelajaran' => 'required|unique:mata_pelajaran',
            'id_kurikulum' => 'required|exists:kurikulum,id_kurikulum',
        ], [
            'nama_mata_pelajaran.required' => 'Nama mata pelajaran harus diisi.',
            'nama_mata_pelajaran.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'id_kurikulum.required' => 'Kurikulum harus dipilih.',
            'id_kurikulum.exists' => 'Kurikulum yang dipilih tidak valid.',
        ]);

        $idUser  = auth()->user()->id;
        $operator = Operator::where('id_user', $idUser)->first();
        
        mata_pelajaran::create([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
            'id_operator' => $operator->id_operator,
            'id_kurikulum' => $request->id_kurikulum,
        ]);

        return redirect()->route('Operator.MataPelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $mataPelajaran = mata_pelajaran::with(['operator', 'kurikulum'])->findOrFail($id);
        return view('Role.Operator.Mapel.show', compact('mataPelajaran'));
    }

    public function edit(string $id)
    {
        $mataPelajaran = mata_pelajaran::findOrFail($id);
        $kurikulums = kurikulum::all();
        $user = auth()->user();
        return view('Role.Operator.Mapel.edit', compact('mataPelajaran', 'kurikulums', 'user'));
    }

    public function update(Request $request, string $id)
    {
        // Validation with custom messages
        $request->validate([
            'nama_mata_pelajaran' => 'required|unique:mata_pelajaran,nama_mata_pelajaran,' . $id . ',id_mata_pelajaran',
            'id_kurikulum' => 'required|exists:kurikulum,id_kurikulum',
        ], [
            'nama_mata_pelajaran.required' => 'Nama mata pelajaran harus diisi.',
            'nama_mata_pelajaran.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'id_kurikulum.required' => 'Kurikulum harus dipilih.',
            'id_kurikulum.exists' => 'Kurikulum yang dipilih tidak valid.',
        ]);

        $mataPelajaran = mata_pelajaran::findOrFail($id);
        $mataPelajaran->update([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
        ]);

        return redirect()->route('Operator.MataPelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mataPelajaran = mata_pelajaran::findOrFail($id);
        $mataPelajaran->delete();

        return redirect()->route('Operator.MataPelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
