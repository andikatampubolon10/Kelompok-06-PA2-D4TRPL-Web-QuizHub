@extends('layouts.operator-layout')

@section('title', 'Edit Kurikulum')
@section('page-title', 'Edit Kurikulum')
@section('page-description', 'Perbarui informasi kurikulum yang sudah ada')

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
            <span class="text-gray-800 font-medium">Edit Kurikulum</span>
        </nav>

        <!-- Current Data Info Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Kurikulum Saat Ini</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="bg-white bg-opacity-60 rounded-lg p-3">
                            <p class="text-blue-700 font-medium">Nama Kurikulum:</p>
                            <p class="text-blue-900 font-semibold">{{ $kurikulum->nama_kurikulum }}</p>
                        </div>
                        <div class="bg-white bg-opacity-60 rounded-lg p-3">
                            <p class="text-blue-700 font-medium">Terakhir Diperbarui:</p>
                            <p class="text-blue-900 font-semibold">
                                {{ $kurikulum->updated_at ? $kurikulum->updated_at->format('d M Y, H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white">Form Edit Kurikulum</h3>
                        <p class="text-green-100 text-sm">Perbarui informasi kurikulum</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('Operator.Kurikulum.update', $kurikulum->id_kurikulum) }}" method="POST" class="p-6"
                id="editForm">
                @csrf
                @method('PUT')

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
                                value="{{ old('nama_kurikulum', $kurikulum->nama_kurikulum) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama_kurikulum') border-red-500 @enderror"
                                placeholder="Masukkan nama kurikulum yang baru" required>
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
                            Pastikan nama kurikulum yang baru berbeda dan mudah diidentifikasi
                        </p>
                    </div>

                    <!-- Change History Section -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-history text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-yellow-800 mb-1">Riwayat Perubahan</h4>
                                <div class="text-xs text-yellow-700 space-y-1">
                                    <p>• Dibuat:
                                        {{ $kurikulum->created_at ? $kurikulum->created_at->format('d M Y, H:i') : 'N/A' }}
                                    </p>
                                    <p>• Terakhir diperbarui:
                                        {{ $kurikulum->updated_at ? $kurikulum->updated_at->format('d M Y, H:i') : 'N/A' }}
                                    </p>
                                    <p>• Perubahan akan tercatat secara otomatis</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span>Perubahan akan disimpan secara permanen</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Operator.Kurikulum.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>

                        <button type="button" onclick="showPreview()"
                            class="inline-flex items-center justify-center px-6 py-3 border border-blue-300 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </button>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>
                            Perbarui Kurikulum
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Preview Perubahan</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm font-medium text-red-800">Nama Lama:</p>
                        <p class="text-red-900">{{ $kurikulum->nama_kurikulum }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <p class="text-sm font-medium text-green-800">Nama Baru:</p>
                        <p id="previewNewName" class="text-green-900">-</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closePreview()"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                    <button onclick="submitForm()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Konfirmasi Perubahan
                    </button>
                </div>
            </div>
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

        function showPreview() {
            const newName = document.getElementById('nama_kurikulum').value;
            document.getElementById('previewNewName').textContent = newName || '-';
            document.getElementById('previewModal').classList.remove('hidden');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('editForm').submit();
        }

        function duplicateKurikulum() {
            const currentName = '{{ $kurikulum->nama_kurikulum }}';
            const newName = prompt('Masukkan nama untuk kurikulum yang akan diduplikasi:', currentName + ' (Copy)');
            if (newName) {
                // Here you would typically make an AJAX request to duplicate
                alert('Fitur duplikasi akan segera tersedia!');
            }
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
            const form = document.getElementById('editForm');
            const nameInput = document.getElementById('nama_kurikulum');
            const originalName = '{{ $kurikulum->nama_kurikulum }}';

            nameInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.remove('border-red-500');
                    if (this.value !== originalName) {
                        this.classList.add('border-green-500');
                    } else {
                        this.classList.remove('border-green-500');
                    }
                }
            });

            form.addEventListener('submit', function(e) {
                if (nameInput.value.trim() === '') {
                    e.preventDefault();
                    nameInput.classList.add('border-red-500');
                    nameInput.focus();
                    return;
                }

                if (nameInput.value === originalName) {
                    e.preventDefault();
                    alert(
                        'Tidak ada perubahan yang dilakukan. Silakan ubah nama kurikulum terlebih dahulu.');
                    return;
                }
            });
        });
    </script>
@endsection
