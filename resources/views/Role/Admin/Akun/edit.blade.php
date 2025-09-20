@extends('layouts.admin-layout')

@section('title', 'Edit Akun')

@section('content')
  <!-- Additional Info Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penambahan Akun</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Masukkan Nama Sekolah menggunakan dengan jelas</li>
                    <li>• Masukkan Email Sekolah yang Aktif</li>
                    <li>• Atur Status Akun</li>
                    <li>• Masukkan Password Minimal 8 karakter</li>
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="form-container  mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Akun</h2>

        <form action="{{ route('Admin.Akun.update', $operator->id_operator) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Sekolah Field -->
            <div class="mb-6">
                <label for="nama_sekolah" class="block text-lg font-medium text-gray-700 mb-2">Nama Sekolah<span class="text-red-500">*</span></label>
                <input type="text" name="nama_sekolah" id="nama_sekolah" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('nama_sekolah') border-red-500 @enderror" 
                    value="{{ old('nama_sekolah', $operator['nama_sekolah']) }}" placeholder="Masukkan nama sekolah">
                @error('nama_sekolah')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="mb-6">
                <label for="email" class="block text-lg font-medium text-gray-700 mb-2">Email<span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror" 
                    value="{{ old('email', $operator->user['email']) }}" placeholder="Masukkan email">
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

  <!-- Status Field as Slice Toggle -->
<div class="mb-6">
    <label for="status" class="block text-lg font-medium text-gray-700 mb-2">
        Status Aktif <span class="text-red-500">*</span>
    </label>

    <div class="flex items-center space-x-4">
        <!-- Label untuk Tidak Aktif -->
        <span class="text-gray-700">Tidak Aktif</span>

        <!-- Switch -->
        <label class="relative inline-flex items-center cursor-pointer">
            <input 
                type="checkbox" 
                name="status_toggle" 
                id="status_toggle"
                class="sr-only peer"
                onchange="document.getElementById('status').value = this.checked ? 'Aktif' : 'Tidak Aktif';"
                {{ old('status', $operator->status) == 'Aktif' ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-500 transition-all duration-300"></div>
            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-all duration-300 peer-checked:translate-x-full"></div>
        </label>

        <!-- Label untuk Aktif -->
        <span class="text-gray-700">Aktif</span>
    </div>

    <!-- Hidden input untuk menampung nilai -->
    <input type="hidden" name="status" id="status" value="{{ old('status', $operator->status) }}">

    @error('status')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>


            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-lg font-medium text-gray-700 mb-2">Kata Sandi <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" 
                    placeholder="Masukkan kata sandi">
                @error('password')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-lg font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi<span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password_confirmation') border-red-500 @enderror" 
                    placeholder="Konfirmasi kata sandi">
                @error('password_confirmation')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
   <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Admin.Akun.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div id="successAlert"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <p class="font-semibold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="closeAlert('successAlert')" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="errorAlert"
            class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <div>
                <p class="font-semibold">Error!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            <button onclick="closeAlert('errorAlert')" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <script>
        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }

        // Auto close alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
        }, 5000);

        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('nama_kurikulum');

            nameInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                }
            });

            form.addEventListener('submit', function(e) {
                if (nameInput.value.trim() === '') {
                    e.preventDefault();
                    nameInput.classList.add('border-red-500');
                    nameInput.focus();
                }
            });
        });
    </script>
@endsection