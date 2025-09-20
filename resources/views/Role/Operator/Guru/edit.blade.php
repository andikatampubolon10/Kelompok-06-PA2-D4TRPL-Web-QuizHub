@extends('layouts.operator-layout')

@section('title', 'Edit Guru')
@section('page-title', 'Edit Data Guru')
@section('page-description', 'Ubah informasi data guru yang sudah terdaftar')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 bg-white p-4 rounded-lg shadow-sm">
            <a href="{{ route('Operator.Guru.index') }}" class="flex items-center hover:text-teal-600 transition-colors">
                <i class="fas fa-chalkboard-teacher mr-1"></i>
                Daftar Guru
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-800 font-medium">Edit Guru</span>
        </nav>

        <div class="bg-gradient-to-r from-teal-50 to-blue-50 border border-teal-200 rounded-lg p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-2xl text-teal-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800">{{ $guru->nama_guru }}</h3>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <span class="flex items-center">
                            <i class="fas fa-id-card mr-1"></i>
                            NIP: {{ $guru->nip }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-envelope mr-1"></i>
                            {{ $guru->user->email }}
                        </span>
                        <span class="flex items-center">
                            @if (strtolower($guru->status) === 'aktif')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Tidak Aktif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-edit text-teal-600 mr-2"></i>
                    Form Edit Data Guru
                </h2>
            </div>

            <form action="{{ route('Operator.Guru.update', $guru->id_guru) }}" method="POST" class="p-6 space-y-6"
                id="editGuruForm">
                @csrf
                @method('PATCH')

                <!-- Help Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800 mb-1">Tips Pengisian Form</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• NIP harus unik dan tidak boleh sama dengan guru lain</li>
                                <li>• Email akan digunakan untuk login ke sistem</li>
                                <li>• Password hanya diisi jika ingin mengubah password lama</li>
                                <li>• Status "Tidak Aktif" akan menonaktifkan akses login guru</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIP -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card text-teal-600 mr-2"></i>
                            NIP <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nip') border-red-500 @enderror"
                            placeholder="Masukkan NIP guru" maxlength="20">
                        @error('nip')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Nama Guru -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-user text-teal-600 mr-2"></i>
                            Nama Guru <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $guru->nama_guru) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap guru">
                        @error('name')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope text-teal-600 mr-2"></i>
                            Email <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $guru->user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('email') border-red-500 @enderror"
                            placeholder="Masukkan alamat email">
                        @error('email')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-toggle-on text-teal-600 mr-2"></i>
                            Status Akun <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors appearance-none bg-white @error('status') border-red-500 @enderror">
                                <option value="Aktif" {{ old('status', $guru->status) == 'Aktif' ? 'selected' : '' }}>
                                    ✅ Aktif
                                </option>
                                <option value="Tidak Aktif"
                                    {{ old('status', $guru->status) == 'Tidak Aktif' ? 'selected' : '' }}>
                                    ❌ Tidak Aktif
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('status')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-key text-teal-600 mr-2"></i>
                        Ubah Password (Opsional)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <i class="fas fa-lock text-teal-600 mr-2"></i>
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('password') border-red-500 @enderror"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="flex items-center mt-1 text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span class="text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <i class="fas fa-lock text-teal-600 mr-2"></i>
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('password_confirmation') border-red-500 @enderror"
                                    placeholder="Konfirmasi password baru">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="flex items-center mt-1 text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span class="text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('Operator.Guru.index') }}"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Kursus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form submission with loading state
        document.getElementById('editGuruForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitSpinner.classList.remove('hidden');

            // Validate status change
            const currentStatus = '{{ $guru->status }}';
            const newStatus = document.querySelector('select[name="status"]').value;

            if (currentStatus === 'Aktif' && newStatus === 'Tidak Aktif') {
                if (!confirm('Anda yakin ingin menonaktifkan guru ini? Guru tidak akan bisa login ke sistem.')) {
                    e.preventDefault();
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.textContent = 'Simpan Perubahan';
                    submitSpinner.classList.add('hidden');
                    return false;
                }
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endsection
