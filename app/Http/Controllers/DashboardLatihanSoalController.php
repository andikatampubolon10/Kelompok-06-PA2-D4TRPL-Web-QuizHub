<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardLatihanSoalController extends Controller
{
    public function index()
    {
        return view('Role.Siswa.Course.kurikulum');
    }
}
