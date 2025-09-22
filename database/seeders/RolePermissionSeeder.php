<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission; 
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Start a transaction
        DB::transaction(function () {
            // Clear existing data
            DB::table('model_has_permissions')->delete();
            DB::table('model_has_roles')->delete();
            Permission::query()->delete();
            Role::query()->delete();

            // Define permissions
            $permissions = [
                // Admin
                'create Operator',
                'view Operator',
                'edit Operator',
                'delete Operator',
                'create Bisnis',
                'view Bisnis',
                'delete Bisnis',
                
                // Operator
                'create Siswa',
                'view Siswa',
                'edit Siswa',
                'delete Siswa',
                'create Guru',
                'view Guru',
                'edit Guru',
                'delete Guru',
                'create Kelas',
                'view Kelas',
                'edit Kelas',
                'create Kurikulum',
                'view Kurikulum',
                'edit Kurikulum',
                'create Mapel',
                'view Mapel',
                'edit Mapel',
                'delete Mapel',
                
                // Guru
                'view Course',
                'create Course',
                'edit Course',
                'delete Course',
                'create latihanSoal',
                'view latihanSoal',
                'edit latihanSoal',
                'delete latihanSoal',
                'create Nilai',
                'view Nilai',
                'edit Nilai',
            ];

            // Create permissions in bulk
            Permission::insert(array_map(function ($permission) {
                return [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $permissions));

            // Create roles and assign permissions
            $AdminRole = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
            $AdminRole->givePermissionTo(['create Operator', 'view Operator', 'edit Operator', 'delete Operator', 'create Bisnis', 'view Bisnis', 'delete Bisnis']);

            $OperatorRole = Role::create(['name' => 'Operator', 'guard_name' => 'web']);
            $OperatorRole->givePermissionTo(['create Siswa', 'view Siswa', 'edit Siswa', 'delete Siswa', 'create Guru', 'view Guru', 'edit Guru', 'delete Guru', 'create Kelas', 'view Kelas', 'edit Kelas', 'create Kurikulum', 'view Kurikulum', 'edit Kurikulum', 'create Mapel', 'view Mapel', 'edit Mapel', 'delete Mapel']);

            $GuruRole = Role::create(['name' => 'Guru', 'guard_name' => 'web']);
            $GuruRole->givePermissionTo(['view Siswa', 'view Guru', 'view Kelas', 'view Kurikulum', 'view Mapel', 'view Course', 'create Course', 'edit Course', 'delete Course', 'create latihanSoal', 'view latihanSoal', 'edit latihanSoal', 'delete latihanSoal', 'create Nilai', 'view Nilai', 'edit Nilai']);

            $SiswaRole = Role::create(['name' => 'Siswa', 'guard_name' => 'web']);
            $SiswaRole->givePermissionTo(['view Course', 'view latihanSoal', 'view Nilai', 'view Kelas', 'view Kurikulum', 'view Mapel']);

            // Create Admin user
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
            ]);

            $user->assignRole($AdminRole);
        });
    }
}