<?php

namespace App\Http\Controllers;

use App\Models\guru;
use App\Models\User;
use App\Models\Operator;
use App\Models\mata_pelajaran;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

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

        if (!$user) {
            return redirect()->route('login');
        }
        return view('Role.Operator.Guru.create', compact('user', 'mataPelajaran'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nip' => 'required|numeric|digits:16|min:16|unique:guru',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'status' => 'in:Aktif,Tidak Aktif',
                'mata_pelajaran' => 'required|array|min:1',
                'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran', // Validasi mata pelajaran
            ], [
                'name.required' => 'Nama guru harus diisi.',
                'nip.required' => 'NIP harus diisi.',
                'nip.unique' => 'NIP sudah terdaftar.',
                'nip.numeric' => 'NIP harus berupa angka.',
                'nip.digits' => 'NIP harus terdiri dari 16 digit.',
                'nip.min' => 'NIP harus terdiri dari minimal 16 digit.',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password harus diisi.',
                'password.min' => 'Password minimal terdiri dari 8 karakter.',
                'status.in' => 'Status harus bernilai "Aktif" atau "Tidak Aktif".',
                'mata_pelajaran.required' => 'Mata pelajaran harus dipilih.',
                'mata_pelajaran.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            ]);

            // Membuat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $user->assignRole('Guru');

            // Ambil ID Operator yang terkait dengan user
            $idUser = auth()->user()->id;
            $operator = Operator::where('id_user', $idUser)->first();

            if (!$operator) {
                Log::error('Operator not found for user: ' . $idUser);
                return redirect()->back()->withErrors('ID Operator tidak ditemukan. Pastikan pengguna memiliki ID Operator yang valid.');
            }

            // Membuat data guru
            $guru = Guru::create([
                'nama_guru' => $request->name,
                'nip' => $request->nip,
                'id_user' => $user->id,
                'id_operator' => $operator->id_operator,
                'status' => $request->status ?? 'Aktif',
            ]);

            // Menyimpan relasi many-to-many ke tabel pivot
            $guru->mataPelajaran()->attach($request->mata_pelajaran);

            return redirect()->route('Operator.Guru.index')->with('success', 'Guru berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error adding guru: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_id' => auth()->user()->id,
            ]);
            return redirect()->back()->withErrors('Terjadi kesalahan saat menambahkan guru.');
        }
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
            'nip' => 'required|numeric|digits:16|min:16|unique:guru,nip,' . $id_guru . ',id_guru',
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
