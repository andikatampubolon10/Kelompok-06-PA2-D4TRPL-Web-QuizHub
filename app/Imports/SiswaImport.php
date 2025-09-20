<?php

namespace App\Imports;

use App\Models\siswa;
use App\Models\User;
use App\Models\kelas;
use App\Models\Operator;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class SiswaImport implements ToModel, WithStartRow, WithValidation
{
    protected $id_kelas;

    public function __construct($id_kelas)
    {
        $this->id_kelas = $id_kelas;
    }

    public function startRow(): int
    {
        return 2; // Skip the header row
    }

    public function model(array $row)
    {
        try {
            Log::info('Processing row: ' . json_encode($row));

            // Validate that all required fields are present
            if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4])) {
                Log::warning('Data tidak lengkap untuk baris: ' . json_encode($row));
                return null; // Skip incomplete data
            }

            // Check if the 'siswa' role exists in the database
            $SiswaRole = Role::where('name', 'siswa')->first();
            if (!$SiswaRole) {
                Log::error('Role "siswa" tidak ditemukan.');
                throw new \Exception('Role "siswa" tidak ditemukan.');
            }

            // Find the class (Kelas) associated with the student
            $kelas = Kelas::where('nama_kelas', $row[4])->first();
            if (!$kelas) {
                Log::warning('Kelas tidak ditemukan untuk nama: ' . $row[4]);
                return null; // Skip if class doesn't exist
            }

            // Create user record for the student
            Log::info('Creating user for: ' . $row[0]);
            $user = User::create([
                'name' => $row[0],            // Student's name
                'email' => $row[2],           // Student's email
                'password' => Hash::make($row[3]), // Hash the password before saving
            ]);

            // Assign the 'siswa' role to the user
            $user->assignRole('Siswa');

            // Fetch the operator for the authenticated user
            $operator = Operator::where('id_user', auth()->user()->id)->first();
            if (!$operator) {
                Log::warning('Operator tidak ditemukan untuk user id: ' . auth()->user()->id);
                return null; // Skip if operator doesn't exist
            }

            // Create the Siswa record for the student
            Log::info('Creating siswa record for: ' . $row[0]);
            $siswa = Siswa::create([
                'nama_siswa' => $row[0],       // Student's name
                'nis' => $row[1],              // Student's NIS
                'id_user' => $user->id,        // Link to the user
                'id_kelas' => $kelas->id_kelas, // Link to the class
                'id_operator' => $operator->id_operator, // Link to the operator
                'status' => 'Aktif',           // Default status as "Active"
            ]);

            // Log the successful creation of the siswa record
            Log::info('Siswa record created successfully: ' . json_encode($siswa));

            return $siswa; // Return the siswa record

        } catch (\Exception $e) {
            // Catch any exceptions and log the details
            Log::error('Failed to import row: ' . json_encode($row));
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error details: ' . $e->getTraceAsString());
            return null; // Skip this row on error
        }
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string',   // Student's name
            '1' => 'required|numeric|unique:siswa,nis', // NIS (must be unique)
            '2' => 'required|email|unique:users,email', // Email (must be unique)
            '3' => 'required|string',   // Password (hashed)
            '4' => 'required|string',   // Class name
        ];
    }

    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        // Log validation failures for any rows that fail validation
        foreach ($failures as $failure) {
            Log::error('Import validation failed: ', $failure->errors());
        }
    }
}
