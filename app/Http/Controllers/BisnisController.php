<?php

namespace App\Http\Controllers;

use App\Models\bisnis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BisnisController extends Controller
{
    // Show all business records
     public function index()
    {
        $bisnises = Bisnis::all(); // Mengambil semua data bisnis dari database
        return view('Role.Admin.Bisnis.index', compact('bisnises'));
    }

    // Show the form for creating a new business record
    public function create()
    {
        return view('Role.Admin.Bisnis.create');
    }

    // Store a newly created business record in storage
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'nama_sekolah' => 'required|string|unique:bisnis',
            'jumlah_pendapatan' => 'required|numeric', // Ensure this is filled
            'perjanjian' => 'required|file|mimes:pdf,doc,docx', // Validate file perjanjian
        ], [
            'nama_sekolah.required' => 'Nama sekolah harus diisi.',
            'jumlah_pendapatan.required' => 'Jumlah pendapatan harus diisi.',
            'jumlah_pendapatan.numeric' => 'Jumlah pendapatan harus berupa angka.',
            'perjanjian.required' => 'Perjanjian harus diunggah.',
            'perjanjian.mimes' => 'Perjanjian harus berupa file PDF, DOC, atau DOCX.',
        ]);

        // Ensure the file is uploaded
        if ($request->hasFile('perjanjian') && $request->file('perjanjian')->isValid()) {
            // Store the perjanjian file
            $filePath = $request->file('perjanjian')->storeAs(
                'perjanjian', time() . '_' . $request->file('perjanjian')->getClientOriginalName(), 'public'
            );

            // Create the business record with the file path
            Bisnis::create([
                'nama_sekolah' => $request->nama_sekolah,
                'jumlah_pendapatan' => $request->jumlah_pendapatan,
                'perjanjian' => $filePath, // Store the file path
            ]);

            // Redirect with success message
            return redirect()->route('Admin.Bisnis.index')->with('success', 'Bisnis berhasil dibuat dan perjanjian di-upload.');
        }

        // If the file isn't valid or wasn't uploaded, return error
        return redirect()->route('Admin.Bisnis.index')->with('error', 'Perjanjian tidak valid atau tidak ada file yang di-upload.');
    }

    // Show the form to edit a business record
    public function edit($id_bisnis)
    {
        $bisnis = Bisnis::findOrFail($id_bisnis); // Find the business by its ID
        return view('Role.Admin.Bisnis.edit', compact('bisnis'));
    }

    // Update the business record
    public function update(Request $request, $id_bisnis)
    {
        // Validate the input
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'jumlah_pendapatan' => 'required|numeric',
        ], [
            'nama_sekolah.required' => 'Nama sekolah harus diisi.',
            'jumlah_pendapatan.required' => 'Jumlah pendapatan harus diisi.',
            'jumlah_pendapatan.numeric' => 'Jumlah pendapatan harus berupa angka.',
        ]);

        // Find the business record to update
        $bisnis = Bisnis::findOrFail($id_bisnis);

        // Update the business details
        $bisnis->update($request->only(['nama_sekolah', 'jumlah_pendapatan']));

        // If there's a new perjanjian file uploaded
        if ($request->hasFile('perjanjian') && $request->file('perjanjian')->isValid()) {
            // Store the new perjanjian file
            $filePath = $request->file('perjanjian')->storeAs(
                'perjanjian', time() . '_' . $request->file('perjanjian')->getClientOriginalName(), 'public'
            );

            // Update the perjanjian path in the database
            $bisnis->update(['perjanjian' => $filePath]);
        }

        // Redirect with success message
        return redirect()->route('Admin.Bisnis.index')->with('success', 'Bisnis berhasil diupdate.');
    }

    // Delete a business record
    public function destroy($id_bisnis)
    {
        $bisnis = Bisnis::findOrFail($id_bisnis); // Find the business by its ID
        $bisnis->delete(); // Delete the business record

        return redirect()->route('Admin.Bisnis.index')->with('success', 'Bisnis berhasil dihapus.');
}
}
