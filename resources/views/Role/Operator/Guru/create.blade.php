@extends('layouts.operator-layout')

@section('title', 'Tambah Guru')
@section('page-title', 'Tambah Guru')
@section('page-description', 'Formulir untuk menambahkan guru baru ke dalam sistem')

@section('content')
    <div class="space-y-6">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-4 rounded-lg shadow-md flex items-center gap-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('Operator.Guru.index') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Daftar Guru
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Guru</span>
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
                        Import Data Guru
                    </h3>
                    <p class="text-sm text-gray-600">Unggah file Excel untuk menambahkan multiple guru sekaligus</p>
                </div>

                <form id="importForm" action="{{ route('Operator.Guru.import') }}" method="POST"
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
                            Format file Excel harus memiliki kolom: NIP, Nama, Email, Password
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
                    Tambah Guru Manual
                </h3>
                <p class="text-sm text-gray-600">Isi formulir di bawah untuk menambahkan guru baru</p>
            </div>

            <form action="{{ route('Operator.Guru.store') }}" method="POST" class="space-y-6">
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
                                    <li>NIP harus terdiri dari 18 digit angka</li>
                                    <li>Email harus unik dan belum terdaftar di sistem</li>
                                    <li>Pilih minimal satu mata pelajaran yang akan diampu</li>
                                    <li>Kata sandi minimal 8 karakter</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NIP Field -->
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card text-gray-400 mr-1"></i>
                        NIP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nip" name="nip" maxlength="18" value="{{ old('nip') }}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 18); validateNIP(this)"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nip') border-red-500 @enderror"
                        placeholder="Masukkan NIP (18 digit)">

                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <script>
                        function validateNIP(input) {
                            const nip = input.value;

                            if (nip.length === 18 && /^\d{18}$/.test(nip)) {
                                input.setCustomValidity(""); // Clear the error if valid
                            } else {
                                input.setCustomValidity("NIP harus terdiri dari 18 digit angka.");
                            }
                        }
                    </script>
                    <style>
                        input::-webkit-outer-spin-button,
                        input::-webkit-inner-spin-button {
                            -webkit-appearance: none;
                            margin: 0;
                        }

                        /* Hilangkan spinner di Firefox */
                        input[type=number] {
                            -moz-appearance: textfield;
                        }
                    </style>
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Nama Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap guru">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Mata Pelajaran Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-book text-gray-400 mr-1"></i>
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($mataPelajaran as $mapel)
                            <div class="flex items-center">
                                <input type="checkbox" id="mata_pelajaran_{{ $mapel->id_mata_pelajaran }}"
                                    name="mata_pelajaran[]" value="{{ $mapel->id_mata_pelajaran }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ in_array($mapel->id_mata_pelajaran, old('mata_pelajaran', [])) ? 'checked' : '' }}>
                                <label for="mata_pelajaran_{{ $mapel->id_mata_pelajaran }}"
                                    class="ml-2 text-sm text-gray-700">
                                    {{ $mapel->nama_mata_pelajaran }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('mata_pelajaran')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="Masukkan kata sandi">
                        <button type="button" onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Ulangi kata sandi">
                        <button type="button" onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password_confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-100">
                    <a href="{{ route('Operator.Guru.index') }}"
                        class="inline-flex items-center px-6 py-3 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <div class="flex space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kursus
                        </button>
                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>

    <script>
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

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="mata_pelajaran[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu mata pelajaran!');
                return false;
            }
        });
    </script>
@endsection
