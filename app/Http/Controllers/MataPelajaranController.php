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

<<<<<<< HEAD
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

        return view('Role.Operator.Mapel.index', compact('mataPelajarans', 'semesters', 'activeSemester', 'user'));
    }

    public function create(Request $request)
=======
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
>>>>>>> 4fec7715132b29472c95658f2dce39503adea415
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
