<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Operator;
use App\Models\mata_pelajaran;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index()
    {
        // Get the logged-in user (Operator)
        $user = auth()->user();

        // Ensure the user is logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Fetch the operator related to the logged-in user
        $operator = Operator::where('id_user', $user->id)->first(); // Use 'id_user' or correct column for your case

        // Fetch the 'guru' (teachers) associated with the operator
        $gurus = guru::where('id_operator', $operator->id_operator)->with('user')->get();

        // Pass the user data to the view
        return view('Role.Operator.Guru.index', compact('gurus', 'user'));
    }

    public function upload()
    {
        return view('Role.Operator.Guru.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ], [
            'file.required' => 'File harus diupload.',
            'file.mimes' => 'File harus bertipe .xlsx atau .xls.',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                Excel::import(new GuruImport, $request->file('file'));
                return redirect()->route('Operator.Guru.index')->with('success', 'Data guru berhasil diupload.');
            } catch (\Exception $e) {
                \Log::error('Error during import: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'File tidak valid atau gagal diupload.');
        }
    }

    public function create()
    {
        $mataPelajaran = mata_pelajaran::all();
        $user = auth()->user();

        $operator = Operator::where('id_user', $user->id)->first();

        $mataPelajarans = mata_pelajaran::with('guru')
            ->where('id_operator', $operator->id_operator)
            ->get();

        if (!$user) {
            return redirect()->route('login');
        }
        return view('Role.Operator.Guru.create', compact('user', 'mataPelajaran','operator'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|numeric|digits:18|unique:guru,nip',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'in:Aktif,Tidak Aktif',
            'mata_pelajaran' => 'required|array|min:1',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        return DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole('Guru');

            $operator = Operator::where('id_user', auth()->id())->first();
            if (!$operator) {
                abort(422, 'ID Operator tidak ditemukan untuk user login.');
            }

            $guru = Guru::create([
                'nama_guru'        => $request->name,
                'nip'              => $request->nip,
                'id_user'          => $user->id,
                'id_operator'      => $operator->id_operator,
                'status'           => $request->status ?? 'Aktif',
                'id_mata_pelajaran' => $request->mata_pelajaran[0], // jika memang ada kolom ini
            ]);

            // Simpan ke tabel pivot guru_mata_pelajaran
            foreach ($request->mata_pelajaran as $id_mapel) {
                \App\Models\Guru_Mata_Pelajaran::create([
                    'id_guru' => $guru->id_guru,
                    'id_mata_pelajaran' => $id_mapel,
                ]);
            }

            return redirect()
                ->route('Operator.Guru.index')
                ->with('success', 'Guru berhasil ditambahkan.');
        });
    }

    public function show(string $id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return view('Role.Operator.Guru.index', compact('guru'));
    }

    public function edit(string $id)
    {
        $mataPelajaran = mata_pelajaran::all();
        $guru = Guru::with('user')->findOrFail($id);
        $user = auth()->user();
        return view('Role.Operator.Guru.edit', compact('guru', 'user', 'mataPelajaran'));
    }

    public function update(Request $request, string $id_guru)
    {
        // Log masuk untuk melihat data request
        Log::debug('Update Request Data:', $request->all());

        // Validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|numeric|digits:18|min:18|unique:guru,nip,' . $id_guru . ',id_guru',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'name.required' => 'Nama guru harus diisi.',
            'nip.required' => 'NIP harus diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.digits' => 'NIP harus terdiri dari 16 digit.',
            'nip.min' => 'NIP harus terdiri dari minimal 16 digit.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status harus bernilai "Aktif" atau "Tidak Aktif".',
        ]);

        // Temukan guru berdasarkan ID
        $guru = Guru::findOrFail($id_guru);

        $guru->mataPelajaran()->sync($request->mata_pelajaran);

        // Update data guru
        $guru->nama_guru = $request->name;
        $guru->nip = $request->nip;
        $guru->status = $request->status;

        // Jika user ada, update nama guru di tabel user
        if ($guru->user) {
            Log::debug('Updating User Name:', ['old_name' => $guru->user->name, 'new_name' => $request->name]);
            $guru->user->name = $request->name; // Update kolom 'name' di tabel 'users'
        }

        // Update password jika ada perubahan
        if ($request->filled('password')) {
            Log::debug('Password is being updated');
            $guru->password = bcrypt($request->password); // Update password di tabel guru

            if ($guru->user) {
                Log::debug('Updating User Password');
                $guru->user->password = bcrypt($request->password); // Update password di tabel users
                $guru->user->save(); // Simpan perubahan pada user
            }
        }

        // Simpan perubahan pada tabel guru
        Log::debug('Saving Guru Data...');
        $guru->save();

        // Simpan perubahan pada tabel user jika nama diupdate
        if ($guru->user) {
            Log::debug('Saving User Data...');
            $guru->user->save(); // Simpan perubahan nama dan password pada user
        }

        // Return ke halaman index dengan pesan sukses
        return redirect()->route('Operator.Guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $guru = Guru::findOrFail($id);

        if ($guru->status === 'Aktif') {
            return redirect()->route('Operator.Guru.index')->with('error', 'Guru dengan status "Aktif" tidak dapat dihapus.');
        }

        $guru->delete();
        return redirect()->route('Operator.Guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
