<?php

namespace App\Imports;

use App\Models\guru;
use App\Models\User;
use App\Models\Operator;
use App\Models\mata_pelajaran;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToModel, WithStartRow, WithValidation
{
    public function startRow(): int
    {
        return 2; // Skip the header row
    }

    public function model(array $row)
    {
        try {
            $operatorId = session('operator_id'); // Store operator ID in session before upload
            $operator = Operator::find($operatorId);
            if (!$operator) {
                throw new \Exception('Operator tidak ditemukan.');
            }

            // Check if NIP already exists in the 'guru' table
            if (Guru::where('nip', $row[1])->exists()) {
                throw new \Exception("NIP {$row[1]} sudah ada di tabel guru.");
            }

            // Check if the 'guru' role exists
            $guruRole = Role::where('name', 'guru')->first();
            if (!$guruRole) {
                throw new \Exception('Role "guru" tidak ditemukan.');
            }

            // Create a new User record
            $user = User::create([
                'name' => $row[0], // Nama Guru
                'email' => $row[2], // Email
                'password' => Hash::make($row[3]), // Password (hashed)
            ]);

            // Assign the 'guru' role to the user
            $user->assignRole('guru');

            // Check if Mata Pelajaran exists
            $mataPelajaran = mata_pelajaran::where('nama_mata_pelajaran', $row[4])->first();
            if (!$mataPelajaran) {
                throw new \Exception("Mata pelajaran {$row[4]} tidak ditemukan.");
            }

            // Create the Guru record
            Guru::create([
                'nama_guru' => $row[0], // Nama Guru
                'nip' => $row[1], // NIP
                'id_user' => $user->id,
                'id_operator' => $operator->id_operator,
                'status' => 'Aktif', // Default status
                'id_mata_pelajaran' => $mataPelajaran->id_mata_pelajaran,
            ]);
        } catch (\Exception $e) {
            // Log the error to the Laravel log file
            Log::error("Failed to import data for NIP {$row[1]}: " . $e->getMessage());
            throw new \Exception("Data gagal diimpor: " . $e->getMessage());
        }

        return null; // Return null after processing the row
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string', // Nama Guru
            '1' => 'required|numeric|digits:16|unique:guru,nip', // NIP (16 digits and unique in the 'guru' table)
            '2' => 'required|email|unique:users,email', // Email (unique in the 'users' table)
            '3' => 'required|string', // Password (required)
            '4' => 'required|string', // Mata Pelajaran (required)
        ];
    }
}
