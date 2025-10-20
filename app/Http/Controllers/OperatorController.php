<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Operator; 
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class OperatorController extends Controller
{
    public function index()
{
    // Fetch operators ordered by the most recent creation (descending order)
    $operators = Operator::orderBy('created_at', 'desc')->get();
    $users = auth()->user();
    
    // Return the view with the updated list of operators
    return view('Role.Admin.Akun.index', compact('operators', 'users'));
}


    public function create()
    {
        $role = Role::where('name', 'Operator')->first();
        return view('Role.Admin.Akun.create', compact('role'));
    }

 public function store(Request $request)
{
    Log::info('Store method called.');
    
    // Validate the input
    $request->validate([
        'nama_sekolah' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'durasi' => 'required|integer|min:12',
    ], [
        'nama_sekolah.required' => 'Nama sekolah harus diisi.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email yang Anda masukkan tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password harus diisi.',
        'password.min' => 'Password minimal terdiri dari 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'durasi.required' => 'Durasi harus diisi.',
        'durasi.integer' => 'Durasi harus berupa angka.',
        'durasi.min' => 'Durasi minimal 12.',
    ]);
    
    Log::info('Validation passed.', $request->all());
    
    try {
        // Create the user
        $user = User::create([
            'name' => $request->nama_sekolah,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        Log::info('User created.', ['id_user' => $user->id]);
        
        // Assign the 'Operator' role to the user
        $user->assignRole('Operator');
        Log::info('Role assigned to user.', ['user_id' => $user->id]);
        
        // Create the operator
        $operator = Operator::create([
            'nama_sekolah' => $request->nama_sekolah,
            'durasi' => $request->durasi ?? 12,
            'status' => 'Aktif',
            'id_user' => $user->id,
        ]);
        
        Log::info('Operator created.', ['operator_id' => $operator->id_operator]);
        
        // After creation, retrieve the updated list of operators
        $operators = Operator::all();
        $users = auth()->user(); 
        
        // Redirect to the index page with the updated list
        return redirect()->route('Admin.Akun.index')->with('success', 'Akun operator berhasil dibuat.');
    } catch (\Exception $e) {
        Log::error('Error creating operator: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Gagal membuat akun operator.']);
    }
}
    

    public function show(string $id)
    {
        $operator = Operator::findOrFail($id);
        return view('Role.Admin.Akun.show', compact('operator'));
    }

    public function edit($user)
    {
        try {
            $operator = Operator::where('id_user', $user)->firstOrFail();
            $user = User::findOrFail($operator->id_user);
    
            return view('Role.Admin.Akun.edit', compact('operator', 'user'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('Admin.Akun.index')->withErrors('Operator not found.');
        }
    }

    public function update(Request $request, $id_operator)
    {
        Log::info("Update method called for operator with ID: {$id_operator}");
    
        try {
            // Find the operator by ID
            $operator = Operator::findOrFail($id_operator);
            Log::info("Operator found", ['operator_id' => $operator->id_operator]);
    
            // Validation with custom error messages
            $request->validate([
                'nama_sekolah' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $operator->id_user,
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'in:Aktif,Tidak Aktif',
            ], [
                'nama_sekolah.string' => 'Nama sekolah harus berupa teks.',
                'email.email' => 'Format email yang Anda masukkan tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.min' => 'Password minimal terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'status.in' => 'Status harus bernilai Aktif atau Tidak Aktif.',
            ]);
    
            Log::info("Validation passed", $request->all());
    
            // Update the operator's information
            $operator->update([
                'nama_sekolah' => $request->filled('nama_sekolah') ? $request->nama_sekolah : $operator->nama_sekolah,
                'status' => $request->filled('status') ? $request->status : $operator->status,
            ]);
    
            Log::info("Operator updated", ['operator_id' => $operator->id_operator]);
    
            // Update the user data
            $user = User::findOrFail($operator->id_user);
            Log::info("User found", ['user_id' => $user->id]);
    
            $userData = [
                'name' => $request->filled('nama_sekolah') ? $request->nama_sekolah : $user->name,
                'email' => $request->filled('email') ? $request->email : $user->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }
    
            $user->update($userData);
            Log::info("User updated", ['user_id' => $user->id]);
    
            return redirect()->route('Admin.Akun.index')->with('success', 'Akun operator berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Operator or User not found: " . $e->getMessage());
            return back()->withErrors(['error' => 'Operator atau pengguna tidak ditemukan.']);
        } catch (\Exception $e) {
            Log::error("Error updating operator: " . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal memperbarui operator.']);
        }
    }    

  public function destroy(string $id)
{
    $operator = Operator::findOrFail($id);

    // Cek apakah status operator aktif
    if ($operator->status === 'Aktif') {
        return redirect()->route('Admin.Akun.index')
            ->with('error', 'Operator dengan status Aktif tidak dapat dihapus.');
    }

    // Ambil user yang terkait dengan operator
    $user = User::find($operator->id_user);

    // Hapus operator
    $operator->delete();

    // Jika user ditemukan, hapus juga user-nya
    if ($user) {
        $user->delete();
    }

    return redirect()->route('Admin.Akun.index')
        ->with('success', 'Akun operator berhasil dihapus.');
}

}