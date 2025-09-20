@extends('layouts.operator-layout')

@section('title', 'Tambah Kurikulum')
@section('page-title', 'Tambah Kurikulum')
@section('page-description', 'Buat kurikulum baru untuk sistem pembelajaran')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav
            class="flex items-center space-x-2 text-sm text-gray-600 bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-100">
            <a href="{{ route('Operator.Kurikulum.index') }}"
                class="text-green-600 hover:text-green-700 font-medium transition-colors">
                <i class="fas fa-book mr-1"></i>
                Kurikulum
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-800 font-medium">Tambah Kurikulum</span>
        </nav>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white">Form Tambah Kurikulum</h3>
                        <p class="text-green-100 text-sm">Lengkapi informasi kurikulum baru</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('Operator.Kurikulum.store') }}" method="POST" class="p-6">
                @csrf

                <!-- Form Fields -->
                <div class="space-y-6">
                    <!-- Nama Kurikulum Field -->
                    <div class="space-y-2">
                        <label for="nama_kurikulum" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-book text-green-600 mr-2"></i>
                            Nama Kurikulum
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="nama_kurikulum" name="nama_kurikulum"
                                value="{{ old('nama_kurikulum') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama_kurikulum') border-red-500 @enderror"
                                placeholder="Masukkan nama kurikulum (contoh: Kurikulum 2013, Kurikulum Merdeka)" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-edit text-gray-400"></i>
                            </div>
                        </div>
                        @error('nama_kurikulum')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nama kurikulum harus unik dan mudah diidentifikasi
                        </p>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penamaan Kurikulum</h4>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Gunakan nama yang jelas dan mudah dipahami</li>
                                    <li>• Sertakan tahun atau periode jika diperlukan</li>
                                    <li>• Hindari penggunaan karakter khusus</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Operator.Kurikulum.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kurikulum
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
