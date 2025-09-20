<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use App\Models\Operator;
use Illuminate\Http\Request;

class KelasController extends Controller
{
 public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $operator = Operator::where('id_user', $user->id)->first();

        $kelas = Kelas::where('id_operator', $operator->id_operator)
            ->with('operator')  
            ->get();

        return view('Role.Operator.Kelas.index', compact('kelas', 'user'));
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('Role.Operator.Kelas.create', compact('user'));
    }

    public function store(Request $request)
    {
        // Custom validation rules
        $request->validate([
            'nama_kelas' => 'required|string|max:10|unique:kelas', // Ensure the class name is unique
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi.', // Custom message for 'required'
            'nama_kelas.string' => 'Nama kelas harus berupa teks.', // Custom message for 'string'
            'nama_kelas.max' => 'Nama kelas tidak boleh lebih dari 10 karakter.', // Custom message for 'max'
            'nama_kelas.unique' => 'Nama kelas sudah ada, silakan pilih nama lain.', // Custom message for 'unique'
        ]);

        $idUser = auth()->id();
        $operator = Operator::where('id_user', $idUser)->first();

        // Create the class (Kelas)
        kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'id_operator' => $operator->id_operator,
        ]);

        return redirect()->route('Operator.Kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $kelas = kelas::with('operator')->findOrFail($id);
        return view('Role.Operator.Kelas.show', compact('kelas'));
    }

    public function edit(string $id)
    {
        $kelas = kelas::findOrFail($id);
        $user = auth()->user();
        return view('Role.Operator.Kelas.edit', compact('kelas', 'user'));
    }

    public function update(Request $request, string $id)
    {
        // Custom validation rules for updating
        $request->validate([
            'nama_kelas' => 'required|string|max:10|unique:kelas,nama_kelas,' . $id . ',id_kelas', // Allow updating the same class name
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi.',
            'nama_kelas.string' => 'Nama kelas harus berupa teks.',
            'nama_kelas.max' => 'Nama kelas tidak boleh lebih dari 10 karakter.',
            'nama_kelas.unique' => 'Nama kelas sudah ada, silakan pilih nama lain.',
        ]);

        $kelas = kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('Operator.Kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kelas = kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('Operator.Kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
