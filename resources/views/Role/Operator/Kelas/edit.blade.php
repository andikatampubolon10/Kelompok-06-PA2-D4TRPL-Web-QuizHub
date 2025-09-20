@extends('layouts.operator-layout')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Data Kelas')
@section('page-description', 'Ubah informasi data kelas yang sudah terdaftar')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 bg-white p-4 rounded-lg shadow-sm">
            <a href="{{ route('Operator.Kelas.index') }}" class="flex items-center hover:text-teal-600 transition-colors">
                <i class="fas fa-home mr-1"></i>
                Daftar Kelas
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-800 font-medium">Edit Kelas</span>
        </nav>

        <!-- Kelas Info Card -->
        <div class="bg-gradient-to-r from-teal-50 to-blue-50 border border-teal-200 rounded-lg p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-home text-2xl text-teal-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800">{{ $kelas->nama_kelas }}</h3>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            Dibuat: {{ $kelas->created_at ? $kelas->created_at->format('d M Y') : 'Tidak diketahui' }}
                        </span>
                        @if ($kelas->updated_at && $kelas->updated_at != $kelas->created_at)
                            <span class="flex items-center">
                                <i class="fas fa-edit mr-1"></i>
                                Diperbarui: {{ $kelas->updated_at->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-edit text-teal-600 mr-2"></i>
                    Form Edit Data Kelas
                </h2>
            </div>

            <form action="{{ route('Operator.Kelas.update', $kelas->id_kelas) }}" method="POST" class="p-6 space-y-6"
                id="editKelasForm">
                @csrf
                @method('PATCH')

                <!-- Help Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800 mb-1">Tips Pengisian Form</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Nama kelas harus unik dan tidak boleh sama dengan kelas lain</li>
                                <li>• Gunakan format yang konsisten (contoh: X-A, XI-IPA-1, XII-IPS-2)</li>
                                <li>• Nama kelas akan digunakan dalam sistem penjadwalan</li>
                                <li>• Pastikan nama mudah diidentifikasi oleh guru dan siswa</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Nama Kelas Field -->
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <i class="fas fa-home text-teal-600 mr-2"></i>
                        Nama Kelas <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nama_kelas') border-red-500 @enderror"
                        placeholder="Masukkan nama kelas (contoh: X-A, XI-IPA-1)" maxlength="50" required>
                    @error('nama_kelas')
                        <div class="flex items-center mt-1 text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                    <div class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Contoh format: X-A, XI-IPA-1, XII-IPS-2, atau sesuai standar sekolah
                    </div>
                </div>
                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('Operator.Kelas.index') }}"
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
        // Form submission with loading state
        document.getElementById('editKelasForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const submitIcon = document.getElementById('submitIcon');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitSpinner.classList.remove('hidden');
            submitIcon.classList.add('hidden');

            // Validate form
            const namaKelas = document.querySelector('input[name="nama_kelas"]').value.trim();

            if (!namaKelas) {
                e.preventDefault();
                alert('Nama kelas harus diisi!');

                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Simpan Perubahan';
                submitSpinner.classList.add('hidden');
                submitIcon.classList.remove('hidden');
                return false;
            }

            // Additional validation for class name format
            if (namaKelas.length < 2) {
                e.preventDefault();
                alert('Nama kelas terlalu pendek! Minimal 2 karakter.');

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

            // Add character counter for nama_kelas
            const namaKelasInput = document.querySelector('input[name="nama_kelas"]');
            if (namaKelasInput) {
                const maxLength = namaKelasInput.getAttribute('maxlength');
                const counterDiv = document.createElement('div');
                counterDiv.className = 'text-xs text-gray-400 mt-1 text-right';
                counterDiv.innerHTML =
                    `<span id="char-count">${namaKelasInput.value.length}</span>/${maxLength} karakter`;
                namaKelasInput.parentNode.appendChild(counterDiv);

                namaKelasInput.addEventListener('input', function() {
                    document.getElementById('char-count').textContent = this.value.length;

                    // Change color based on length
                    if (this.value.length > maxLength * 0.8) {
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
    </script>
@endsection
