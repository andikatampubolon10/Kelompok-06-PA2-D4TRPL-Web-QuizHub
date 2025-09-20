<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        $id_semester = $request->query('id_semester');

        // Pastikan id_semester valid dan gunakan untuk mengambil data semester
        $semesters = Semester::where('ID_Tahun_Ajaran', $id_semester)->get();

        // Jika kamu ingin mengambil semua semester tanpa filter, bisa gunakan Semester::all()
        $semesters = Semester::all();

        return view('Role.Operator.Semester.index', compact('semesters', 'id_semester'));
    }
}
