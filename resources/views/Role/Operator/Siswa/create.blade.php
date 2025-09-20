@extends('layouts.operator-layout')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')
@section('page-description', 'Formulir untuk menambahkan siswa baru ke dalam sistem')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-4 rounded-lg shadow-md flex items-center gap-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('Operator.Siswa.index') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-user-graduate mr-1"></i>
                        Daftar Siswa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Siswa</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Import Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <i class="fas fa-upload text-blue-500 mr-2"></i>
                        Import Data Siswa
                    </h3>
                    <p class="text-sm text-gray-600">Unggah file Excel untuk menambahkan multiple siswa sekaligus</p>
                </div>

                <form id="importForm" action="{{ route('Operator.Siswa.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="fileInput" name="file" class="hidden" accept=".xlsx,.xls" />
                    <button type="button" id="importButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200">
                        <i class="fas fa-upload mr-2"></i>
                        Pilih File Excel
                    </button>
                </form>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Format file Excel harus memiliki kolom: NISN, Nama, Email, Password, Kelas
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    <i class="fas fa-user-plus text-green-500 mr-2"></i>
                    Tambah Siswa Manual
                </h3>
                <p class="text-sm text-gray-600">Isi formulir di bawah untuk menambahkan siswa baru</p>
            </div>

            <form action="{{ route('Operator.Siswa.store') }}" method="POST" class="space-y-6" id="createSiswaForm">
                @csrf

                <!-- Help Section -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Tips Pengisian Form</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>NISN harus terdiri dari 10 digit angka dan unik</li>
                                    <li>Email harus unik dan belum terdaftar di sistem</li>
                                    <li>Pilih kelas yang sesuai dengan tingkat siswa</li>
                                    <li>Kata sandi minimal 8 karakter</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NISN Field -->
                    <div class="space-y-2">
                        <label for="nis" class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card text-teal-600 mr-2"></i>
                            NISN <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="nis" name="nis" maxlength="10" value="{{ old('nis') }}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10); validateNISN(this)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nis') border-red-500 @enderror"
                            placeholder="Masukkan NISN (10 digit)" required>
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

                    <!-- Nama Siswa Field -->
                    <div class="space-y-2">
                        <label for="name" class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-user text-teal-600 mr-2"></i>
                            Nama Lengkap <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap siswa" required>
                        @error('name')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope text-teal-600 mr-2"></i>
                            Email <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('email') border-red-500 @enderror"
                            placeholder="contoh@email.com" required>
                        @error('email')
                            <div class="flex items-center mt-1 text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Kelas Field -->
                    <div class="space-y-2">
                        <label for="kelas" class="flex items-center text-sm font-medium text-gray-700">
                            <i class="fas fa-home text-teal-600 mr-2"></i>
                            Kelas <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="kelas" id="kelas"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors appearance-none bg-white @error('kelas') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id_kelas }}"
                                        {{ old('kelas') == $k->id_kelas ? 'selected' : '' }}>
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
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-key text-teal-600 mr-2"></i>
                        Informasi Login
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="flex items-center text-sm font-medium text-gray-700">
                                <i class="fas fa-lock text-teal-600 mr-2"></i>
                                Kata Sandi <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan kata sandi" required>
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

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="flex items-center text-sm font-medium text-gray-700">
                                <i class="fas fa-lock text-teal-600 mr-2"></i>
                                Konfirmasi Kata Sandi <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('password_confirmation') border-red-500 @enderror"
                                    placeholder="Konfirmasi kata sandi" required>
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
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-100">
                    <a href="{{ route('Operator.Siswa.index') }}"
                        class="inline-flex items-center px-6 py-3 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <div class="flex space-x-3">
                        <button type="submit" id="submitBtn"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <span id="submitSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                            </span>
                            <i id="submitIcon" class="fas fa-save mr-2"></i>
                            <span id="submitText">Simpan Siswa</span>
                        </button>
                    </div>
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

        // Import functionality
        const importButton = document.getElementById('importButton');
        const fileInput = document.getElementById('fileInput');
        const importForm = document.getElementById('importForm');

        importButton.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Show loading state
                importButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
                importButton.disabled = true;

                // Submit form
                importForm.submit();
            }
        });

        // Password toggle functionality
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
        document.getElementById('createSiswaForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const submitIcon = document.getElementById('submitIcon');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitSpinner.classList.remove('hidden');
            submitIcon.classList.add('hidden');

            // Basic validation
            const nisn = document.getElementById('nis').value.trim();
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const kelas = document.getElementById('kelas').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (!nisn || !name || !email || !kelas || !password || !passwordConfirmation) {
                e.preventDefault();
                alert('Semua field harus diisi!');
                resetSubmitButton();
                return false;
            }

            if (nisn.length !== 10) {
                e.preventDefault();
                alert('NISN harus terdiri dari 10 digit!');
                resetSubmitButton();
                return false;
            }

            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok!');
                resetSubmitButton();
                return false;
            }

            function resetSubmitButton() {
                submitBtn.disabled = false;
                submitText.textContent = 'Simpan Siswa';
                submitSpinner.classList.add('hidden');
                submitIcon.classList.remove('hidden');
            }
        });

        // Add character counter for NISN
        document.addEventListener('DOMContentLoaded', function() {
            const nisnInput = document.getElementById('nis');
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
