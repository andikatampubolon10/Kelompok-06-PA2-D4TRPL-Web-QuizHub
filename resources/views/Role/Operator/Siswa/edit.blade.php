@extends('layouts.operator-layout')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Data Siswa')
@section('page-description', 'Ubah informasi data siswa yang sudah terdaftar')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 bg-white p-4 rounded-lg shadow-sm">
            <a href="{{ route('Operator.Siswa.index') }}" class="flex items-center hover:text-teal-600 transition-colors">
                <i class="fas fa-user-graduate mr-1"></i>
                Daftar Siswa
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-800 font-medium">Edit Siswa</span>
        </nav>

        <!-- Siswa Info Card -->
        <div class="bg-gradient-to-r from-teal-50 to-blue-50 border border-teal-200 rounded-lg p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-graduate text-2xl text-teal-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800">{{ $siswa->nama_siswa }}</h3>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <span class="flex items-center">
                            <i class="fas fa-id-card mr-1"></i>
                            NISN: {{ $siswa->nis }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-envelope mr-1"></i>
                            {{ $siswa->user->email }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-home mr-1"></i>
                            Kelas: {{ $siswa->kelas->nama_kelas ?? 'Belum ditentukan' }}
                        </span>
                        <span class="flex items-center">
                            @if (strtolower($siswa->status) === 'aktif')
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
                    Form Edit Data Siswa
                </h2>
            </div>

            <form action="{{ route('Operator.Siswa.update', $siswa->id_siswa) }}" method="POST" class="p-6 space-y-6"
                id="editSiswaForm">
                @csrf
                @method('PATCH')

                <!-- Help Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800 mb-1">Tips Pengisian Form</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• NISN harus unik dan tidak boleh sama dengan siswa lain</li>
                                <li>• Email akan digunakan untuk login ke sistem</li>
                                <li>• Password hanya diisi jika ingin mengubah password lama</li>
                                <li>• Status "Tidak Aktif" akan menonaktifkan akses login siswa</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NISN -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card text-teal-600 mr-2"></i>
                            NISN <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10); validateNISN(this)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nis') border-red-500 @enderror"
                            placeholder="Masukkan NISN siswa" required>
                        @error('nis')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-lightbulb mr-1"></i>
                            NISN harus terdiri dari 10 digit angka
                        </div>
                    </div>

                    <!-- Nama Siswa -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-user text-teal-600 mr-2"></i>
                            Nama Siswa <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $siswa->nama_siswa) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap siswa" required>
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
                        <input type="email" name="email" value="{{ old('email', $siswa->user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('email') border-red-500 @enderror"
                            placeholder="Masukkan alamat email" required>
                        @error('email')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-home text-teal-600 mr-2"></i>
                            Kelas <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="kelas"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors appearance-none bg-white @error('kelas') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id_kelas }}"
                                        {{ old('kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('kelas')
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
                                <option value="Aktif" {{ old('status', $siswa->status) == 'Aktif' ? 'selected' : '' }}>
                                    ✅ Aktif
                                </option>
                                <option value="Tidak Aktif"
                                    {{ old('status', $siswa->status) == 'Tidak Aktif' ? 'selected' : '' }}>
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

                <!-- Additional Info Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-teal-600 mr-2"></i>
                        Informasi Tambahan
                    </h3>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar mr-2 text-teal-600"></i>
                                <span>Terdaftar: <strong
                                        class="text-gray-800">{{ $siswa->created_at ? $siswa->created_at->format('d M Y') : 'Tidak diketahui' }}</strong></span>
                            </div>
                            @if ($siswa->updated_at && $siswa->updated_at != $siswa->created_at)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-edit mr-2 text-teal-600"></i>
                                    <span>Terakhir diperbarui: <strong
                                            class="text-gray-800">{{ $siswa->updated_at->format('d M Y') }}</strong></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('Operator.Siswa.index') }}"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <button type="submit" id="submitBtn"
                        class="w-full sm:w-auto inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <span id="submitSpinner" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                        </span>
                        <i id="submitIcon" class="fas fa-save mr-2"></i>
                        <span id="submitText">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // NISN validation function
        function validateNISN(input) {
            const nisn = input.value;
            if (nisn.length === 10 && /^\d{10}$/.test(nisn)) {
                input.setCustomValidity("");
            } else {
                input.setCustomValidity("NISN harus terdiri dari 10 digit angka.");
            }
        }

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
        document.getElementById('editSiswaForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const submitIcon = document.getElementById('submitIcon');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitSpinner.classList.remove('hidden');
            submitIcon.classList.add('hidden');

            // Validate status change
            const currentStatus = '{{ $siswa->status }}';
            const newStatus = document.querySelector('select[name="status"]').value;

            if (currentStatus === 'Aktif' && newStatus === 'Tidak Aktif') {
                if (!confirm('Anda yakin ingin menonaktifkan siswa ini? Siswa tidak akan bisa login ke sistem.')) {
                    e.preventDefault();
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.textContent = 'Simpan Perubahan';
                    submitSpinner.classList.add('hidden');
                    submitIcon.classList.remove('hidden');
                    return false;
                }
            }

            // Validate NISN
            const nisn = document.querySelector('input[name="nis"]').value.trim();
            if (nisn.length !== 10) {
                e.preventDefault();
                alert('NISN harus terdiri dari 10 digit!');
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Simpan Perubahan';
                submitSpinner.classList.add('hidden');
                submitIcon.classList.remove('hidden');
                return false;
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

            // Add character counter for NISN
            const nisnInput = document.querySelector('input[name="nis"]');
            if (nisnInput) {
                const counterDiv = document.createElement('div');
                counterDiv.className = 'text-xs text-gray-400 mt-1 text-right';
                counterDiv.innerHTML = `<span id="nisn-count">${nisnInput.value.length}</span>/10 digit`;
                nisnInput.parentNode.appendChild(counterDiv);

                nisnInput.addEventListener('input', function() {
                    document.getElementById('nisn-count').textContent = this.value.length;

                    // Change color based on length
                    if (this.value.length === 10) {
                        counterDiv.className = 'text-xs text-green-500 mt-1 text-right';
                    } else if (this.value.length > 7) {
                        counterDiv.className = 'text-xs text-orange-500 mt-1 text-right';
                    } else {
                        counterDiv.className = 'text-xs text-gray-400 mt-1 text-right';
                    }
                });
            }
        });

        // Add smooth transitions for form elements
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('focus', function() {
                this.parentNode.style.transform = 'translateY(-1px)';
                this.parentNode.style.transition = 'transform 0.2s ease';
            });

            element.addEventListener('blur', function() {
                this.parentNode.style.transform = 'translateY(0)';
            });
        });

        // Hide number input spinners
        const style = document.createElement('style');
        style.textContent = `
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            input[type=number] {
                -moz-appearance: textfield;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
