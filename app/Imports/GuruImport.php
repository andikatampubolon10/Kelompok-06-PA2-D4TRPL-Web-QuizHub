<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use App\Models\Operator;
use App\Models\mata_pelajaran;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToModel, WithStartRow, WithValidation
{
    private int $operatorId;

    public function __construct(int $operatorId)
    {
        $this->operatorId = $operatorId;
    }

    public function startRow(): int
    {
        return 2; // Skip header
    }

    public function model(array $row)
    {
        try {
            // Validasi operator
            $operator = Operator::where('id_operator', $this->operatorId)->first();
            if (!$operator) {
                throw new \Exception('Operator tidak ditemukan untuk id_operator: ' . $this->operatorId);
            }

            // Cek NIP unik
            if (Guru::where('nip', $row[1])->exists()) {
                throw new \Exception("NIP {$row[1]} sudah ada di tabel guru.");
            }

            // Pastikan role tersedia (samakan casing dengan yang ada di DB)
            $roleName = 'Guru'; // ganti ke 'guru' jika tabel roles kamu lowercase
            $guruRole = Role::where('name', $roleName)->first();
            if (!$guruRole) {
                throw new \Exception("Role \"{$roleName}\" tidak ditemukan.");
            }

            // Buat user
            $user = User::create([
                'name'     => $row[0], // Nama Guru
                'email'    => $row[2], // Email
                'password' => Hash::make($row[3]), // Password
            ]);

            // Assign role
            $user->assignRole($roleName);

            // Cari mata pelajaran
            $mataPelajaran = mata_pelajaran::where('nama_mata_pelajaran', $row[4])->first();
            if (!$mataPelajaran) {
                throw new \Exception("Mata pelajaran {$row[4]} tidak ditemukan.");
            }

            // Buat guru
            Guru::create([
                'nama_guru'        => $row[0],
                'nip'              => $row[1],
                'id_user'          => $user->id,
                'id_operator'      => $operator->id_operator,
                'status'           => 'Aktif',
                'id_mata_pelajaran'=> $mataPelajaran->id_mata_pelajaran,
            ]);

        } catch (\Throwable $e) {
            Log::error("Failed to import row for NIP {$row[1]}: " . $e->getMessage(), ['row' => $row]);
            throw new \Exception("Data gagal diimpor: " . $e->getMessage());
        }

        return null; // per baris selesai
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string',                      // Nama Guru
            '1' => 'required|numeric|digits:16|unique:guru,nip', // NIP 18 digit, konsisten dengan store()
            '2' => 'required|email|unique:users,email',    // Email unik
            '3' => 'required|string|min:8',                // Password minimal 8
            '4' => 'required|string',                      // Nama Mata Pelajaran
        ];
    }

    public function customValidationMessages()
    {
        return [
            '1.digits' => 'NIP harus terdiri dari 18 digit.',
        ];
    }
}
