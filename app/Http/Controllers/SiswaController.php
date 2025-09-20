<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use App\Models\User;
use App\Models\Operator;
use App\Models\kelas;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Get the logged-in user
        $user = auth()->user();

        // Ensure the user is logged in
        if (!$user) {
            return redirect()->route('login');
        }

        $operator = Operator::where('id_user', $user->id)->first();

        // Get the available classes for the operator
        $kelas = kelas::where('id_operator', $operator->id_operator)->get();

        // Fetch students based on the class filter if provided, otherwise fetch all students
        if ($request->has('kelas') && $request->kelas != '') {
            $siswa = siswa::where('id_kelas', $request->kelas)
                ->with(['user', 'kelas']) // Include relationships (user, kelas)
                ->orderBy('created_at', 'desc') // Ensure newest students appear first
                ->get();
        } else {
            // If no class filter is applied, fetch all students and order them by created_at
            $siswa = siswa::with(['user', 'kelas'])
                ->orderBy('created_at', 'desc') // Ensures the newest data appears first
                ->get();
        }

        // Return the view with the necessary data
        return view('Role.Operator.Siswa.index', compact('siswa', 'kelas', 'user'));
    }

    public function upload()
    {
        return view('Role.Operator.Siswa.index');
    }

    public function import(Request $request)
    {
        \Log::info('Import method called.');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                \Log::info('File is valid, starting import.');

                // Mengimpor data siswa
                Excel::import(new SiswaImport($request->kelas), $request->file('file'));

                \Log::info('Data siswa berhasil diupload.');
                return redirect()->route('Operator.Siswa.index')->with('success', 'Data siswa berhasil diupload.');
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                \Log::error('Validation errors during import: ' . json_encode($e->failures()));
                return redirect()->back()->with('error', 'Terjadi kesalahan validasi saat mengimpor data.');
            } catch (\Exception $e) {
                \Log::error('Error during import: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
            }
        } else {
            \Log::warning('File tidak valid atau gagal diupload.');
            return redirect()->back()->with('error', 'File tidak valid atau gagal diupload.');
        }
    }

    public function create()
    {
        $kelas = kelas::all();
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        return view('Role.Operator.Siswa.create', compact('kelas', 'user'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called', ['request' => $request->all()]);

        // Validation with custom messages
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|numeric|digits:10|min:10|unique:siswa',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'kelas' => 'required|exists:kelas,id_kelas',
            'status' => 'in:Aktif,Tidak Aktif',
        ], [
            'name.required' => 'Nama siswa harus diisi.',
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.digits' => 'NIS harus terdiri dari 10 digit.',
            'nis.min' => 'NIS harus terdiri dari minimal 10 digit.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'kelas.required' => 'Kelas harus dipilih.',
            'kelas.exists' => 'Kelas yang dipilih tidak valid.',
            'status.in' => 'Status harus bernilai "Aktif" atau "Tidak Aktif".',
        ]);

        Log::info('Validation passed', ['data' => $request->all()]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            Log::info('User created', ['user_id' => $user->id]);

            $user->assignRole('Siswa');
            Log::info('Role assigned to user', ['user_id' => $user->id, 'role' => 'Siswa']);

            $idUser   = auth()->user()->id;
            $operator = Operator::where('id_user', $idUser)->first();

            if (!$operator) {
                Log::warning('Operator not found', ['user_id' => $idUser]);
                return redirect()->back()->with('error', 'Operator tidak ditemukan.');
            }

            $siswa = siswa::create([
                'nama_siswa' => $request->name,
                'nis' => $request->nis,
                'id_user' => $user->id,
                'id_kelas' => $request->kelas,
                'id_operator' => $operator->id_operator,
                'status' => $request->status ?? 'Aktif',
            ]);

            Log::info('Siswa created', ['siswa_id' => $siswa->id]);

            return redirect()->route('Operator.Siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error during storing siswa', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan siswa: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $siswa = siswa::with('user')->findOrFail($id);
        return view('Role.Operator.Siswa.index', compact('siswa'));
    }

    public function edit(string $id)
    {
        $siswa = siswa::with('user')->findOrFail($id);
        $kelas = kelas::all();
        $user = auth()->user();
        return view('Role.Operator.Siswa.edit', compact('user', 'siswa', 'kelas'));
    }

    public function update(Request $request, string $id_siswa)
    {
        // Log masuk untuk melihat data request
        Log::debug('Update Request Data:', $request->all());

        // Validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|numeric|digits:10|min:10|unique:siswa,nis,' . $id_siswa . ',id_siswa', // Validasi nis
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'kelas' => 'nullable|exists:kelas,id_kelas',
        ], [
            'name.required' => 'Nama siswa harus diisi.',
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.digits' => 'NIS harus terdiri dari 10 digit.',
            'nis.min' => 'NIS harus terdiri dari minimal 10 digit.',
            'password.min' => 'Password minimal terdiri dari 10 karakter.',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status harus bernilai "Aktif" atau "Tidak Aktif".',
            'kelas.exists' => 'Kelas yang dipilih tidak valid.',
        ]);

        // Temukan siswa berdasarkan ID
        $siswa = siswa::findOrFail($id_siswa);

        // Update data siswa
        $siswa->nama_siswa = $request->name;
        $siswa->nis = $request->nis;
        $siswa->status = $request->status;

        // Jika password ada perubahan, update password
        if ($request->filled('password')) {
            Log::debug('Password is being updated');
            $siswa->password = bcrypt($request->password); // Update password di tabel siswa
        }

        // Simpan perubahan pada tabel siswa
        Log::debug('Saving Siswa Data...');
        $siswa->save();

        // Jika siswa terkait dengan user (untuk nama dan password)
        if ($siswa->user) {
            Log::debug('Updating User Data:', ['old_name' => $siswa->user->name, 'new_name' => $request->name]);
            $siswa->user->name = $request->name; // Update nama pada tabel 'users'

            if ($request->filled('password')) {
                Log::debug('Updating User Password');
                $siswa->user->password = bcrypt($request->password); // Update password pada tabel 'users'
            }
            $siswa->user->save(); // Simpan perubahan pada tabel users
        }

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('Operator.Siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $siswa = siswa::findOrFail($id);

        // Cek apakah status siswa aktif
        if ($siswa->status === 'Aktif') {
            return redirect()->route('Operator.Siswa.index')->with('error', 'Siswa dengan status "Aktif" tidak dapat dihapus.');
        }

        // Jika status siswa tidak aktif, hapus data siswa
        $siswa->delete();
        return redirect()->route('Operator.Siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}