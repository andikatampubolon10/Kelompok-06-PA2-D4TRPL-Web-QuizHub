@extends('layouts.operator-layout')

@section('title', 'Tambah Kelas')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('Operator.Kelas.index') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Kelas
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Kelas</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tambah Kelas Baru</h1>
                    <p class="mt-2 text-sm text-gray-600">Lengkapi form di bawah untuk menambahkan kelas baru</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Help Section -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-blue-800">Tips Penamaan Kelas</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Gunakan format yang konsisten seperti "X IPA 1" atau "XI IPS 2"</li>
                                <li>Sertakan tingkat kelas (X, XI, XII) dan jurusan jika ada</li>
                                <li>Hindari penggunaan karakter khusus yang tidak perlu</li>
                                <li>Pastikan nama kelas mudah dipahami oleh siswa dan guru</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('Operator.Kelas.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Nama Kelas -->
                    <div>
                        <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nama_kelas') border-red-500 @enderror"
                                placeholder="Contoh: X IPA 1, XI IPS 2, XII Bahasa 1" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-home text-gray-400"></i>
                            </div>
                        </div>
                        @error('nama_kelas')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Masukkan nama kelas dengan format yang jelas dan mudah dipahami
                        </p>
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-100">
                    <a href="{{ route('Operator.Kelas.index') }}"
                        class="inline-flex items-center px-6 py-3 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <div class="flex space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kelas
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            // Auto-focus pada input pertama
            document.addEventListener('DOMContentLoaded', function() {
                const firstInput = document.getElementById('nama_kelas');
                if (firstInput) {
                    firstInput.focus();
                }
            });

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const namaKelas = document.getElementById('nama_kelas').value.trim();

                if (!namaKelas) {
                    e.preventDefault();
                    alert('Nama kelas harus diisi!');
                    document.getElementById('nama_kelas').focus();
                    return false;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Reset if form validation fails
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });
        </script>
    @endpush
@endsection
