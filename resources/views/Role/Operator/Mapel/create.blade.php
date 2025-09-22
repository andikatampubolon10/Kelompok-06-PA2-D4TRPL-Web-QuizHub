@extends('layouts.operator-layout')

@section('title', 'Tambah Mata Pelajaran')
@section('page-title', 'Tambah Mata Pelajaran')
@section('page-description', 'Buat mata pelajaran baru untuk sistem pembelajaran')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav
            class="flex items-center space-x-2 text-sm text-gray-600 bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-100">
            <a href="{{ route('Operator.MataPelajaran.index') }}"
                class="text-green-600 hover:text-green-700 font-medium transition-colors">
                <i class="fas fa-book mr-1"></i>
                Mata Pelajaran
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-800 font-medium">Tambah Mata Pelajaran</span>
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
                        <h3 class="text-xl font-semibold text-white">Form Tambah Mata Pelajaran</h3>
                        <p class="text-green-100 text-sm">Lengkapi informasi mata pelajaran baru</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('Operator.MataPelajaran.store') }}" method="POST" class="p-6" id="mataPelajaranForm">
                @csrf


                <!-- Additional Info Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Menambah Mata Pelajaran</h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Pastikan kurikulum sudah dipilih sebelum mengisi nama mata pelajaran</li>
                                <li>• Gunakan nama yang standar dan mudah dipahami</li>
                                <li>• Hindari penggunaan singkatan yang tidak umum</li>
                                <li>• Periksa kembali ejaan nama mata pelajaran</li>
                            </ul>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Form Fields -->
        <div class="space-y-6">
            <!-- Kurikulum Selection Field -->
            <div class="space-y-2">
                <label for="id_kurikulum" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-layer-group text-green-600 mr-2"></i>
                    Pilih Kurikulum
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select id="id_kurikulum" name="id_kurikulum"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('id_kurikulum') border-red-500 @enderror appearance-none bg-white">
                        <option value="">Pilih Kurikulum</option>
                        @foreach ($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id_kurikulum }}"
                                {{ old('id_kurikulum') == $kurikulum->id_kurikulum ? 'selected' : '' }}>
                                {{ $kurikulum->nama_kurikulum }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                @error('id_kurikulum')
                    <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Pilih kurikulum yang sesuai untuk mata pelajaran ini
                </p>
            </div>

            <!-- Nama Mata Pelajaran Field -->
            <div class="space-y-2">
                <label for="nama_mata_pelajaran" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-book text-green-600 mr-2"></i>
                    Nama Mata Pelajaran
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="nama_mata_pelajaran" name="nama_mata_pelajaran"
                        value="{{ old('nama_mata_pelajaran') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama_mata_pelajaran') border-red-500 @enderror"
                        placeholder="Masukkan nama mata pelajaran (contoh: Matematika, Bahasa Indonesia)">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-edit text-gray-400"></i>
                    </div>
                </div>
                @error('nama_mata_pelajaran')
                    <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Nama mata pelajaran harus unik dalam kurikulum yang dipilih
                </p>
            </div>

            <!-- Form Actions -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle"></i>
                    <span>Semua field yang bertanda (*) wajib diisi</span>
                </div>

                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('Operator.MataPelajaran.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <button type="button" onclick="previewData()"
                        class="inline-flex items-center justify-center px-6 py-3 border border-blue-300 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Preview
                    </button>

                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Mata Pelajaran
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
                    <h3 class="text-lg font-semibold text-gray-900">Preview Data Mata Pelajaran</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-sm font-medium text-gray-800">Kurikulum:</p>
                        <p id="previewKurikulum" class="text-gray-900 font-semibold">-</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-sm font-medium text-gray-800">Nama Mata Pelajaran:</p>
                        <p id="previewNama" class="text-gray-900 font-semibold">-</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closePreview()"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                    <button onclick="submitForm()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Konfirmasi & Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Modal -->
    <div id="validationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">Validasi Form</h3>
                        <p class="text-sm text-red-600">Terdapat kesalahan pada form</p>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <h4 class="text-sm font-semibold text-red-800 mb-2">Field yang perlu diperbaiki:</h4>
                    <ul id="validationErrors" class="text-sm text-red-700 space-y-1">
                        <!-- Error messages will be populated here -->
                    </ul>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeValidationModal()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Mengerti
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

        function previewData() {
            const errors = validateForm();

            if (errors.length > 0) {
                showValidationModal(errors);
                return;
            }

            const kurikulumSelect = document.getElementById('id_kurikulum');
            const kurikulumText = kurikulumSelect.options[kurikulumSelect.selectedIndex].text;
            const nama = document.getElementById('nama_mata_pelajaran').value;

            document.getElementById('previewKurikulum').textContent = kurikulumSelect.value ? kurikulumText : '-';
            document.getElementById('previewNama').textContent = nama || '-';

            document.getElementById('previewModal').classList.remove('hidden');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        function submitForm() {
            const errors = validateForm();

            if (errors.length > 0) {
                closePreview();
                showValidationModal(errors);
                return false;
            }

            document.getElementById('mataPelajaranForm').submit();
        }

        // Auto close alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
        }, 5000);

        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('mataPelajaranForm');
            const kurikulumSelect = document.getElementById('id_kurikulum');
            const namaInput = document.getElementById('nama_mata_pelajaran');

            // Validation functions
            function validateForm() {
                const errors = [];
                const kurikulum = kurikulumSelect.value;
                const nama = namaInput.value.trim();

                // Reset all field styles
                resetFieldStyles();

                // Validate Kurikulum
                if (!kurikulum) {
                    errors.push('Kurikulum harus dipilih');
                    markFieldError('id_kurikulum');
                }

                // Validate Nama Mata Pelajaran
                if (!nama) {
                    errors.push('Nama Mata Pelajaran harus diisi');
                    markFieldError('nama_mata_pelajaran');
                } else if (nama.length < 3) {
                    errors.push('Nama Mata Pelajaran minimal 3 karakter');
                    markFieldError('nama_mata_pelajaran');
                } else if (nama.length > 100) {
                    errors.push('Nama Mata Pelajaran maksimal 100 karakter');
                    markFieldError('nama_mata_pelajaran');
                }

                return errors;
            }

            function markFieldError(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.classList.add('border-red-500', 'bg-red-50');
                    field.classList.remove('border-green-500');
                }
            }

            function resetFieldStyles() {
                const fields = ['id_kurikulum', 'nama_mata_pelajaran'];
                fields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.classList.remove('border-red-500', 'bg-red-50', 'border-green-500');
                    }
                });
            }

            function showValidationModal(errors) {
                const errorsList = document.getElementById('validationErrors');
                errorsList.innerHTML = '';

                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.innerHTML = `<i class="fas fa-times-circle mr-2"></i>${error}`;
                    errorsList.appendChild(li);
                });

                document.getElementById('validationModal').classList.remove('hidden');
            }

            function closeValidationModal() {
                document.getElementById('validationModal').classList.add('hidden');
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Always prevent default first

                const errors = validateForm();

                if (errors.length > 0) {
                    showValidationModal(errors);
                    return false;
                }

                // If no errors, submit the form
                this.submit();
            });

            // Real-time validation feedback
            const inputs = [kurikulumSelect, namaInput];

            inputs.forEach(input => {
                if (input) {
                    input.addEventListener('change', function() {
                        // Remove error styling when user makes changes
                        this.classList.remove('border-red-500', 'bg-red-50');

                        if ((this.type === 'select-one' && this.value !== '') ||
                            (this.type !== 'select-one' && this.value.trim() !== '')) {
                            this.classList.add('border-green-500');
                        } else {
                            this.classList.remove('border-green-500');
                        }
                    });

                    if (input.type !== 'select-one') {
                        input.addEventListener('input', function() {
                            // Remove error styling when user starts typing
                            this.classList.remove('border-red-500', 'bg-red-50');

                            if (this.value.trim() !== '') {
                                this.classList.add('border-green-500');
                            } else {
                                this.classList.remove('border-green-500');
                            }
                        });
                    }

                    input.addEventListener('blur', function() {
                        // Validate individual field on blur
                        const errors = validateForm();
                        if (errors.length === 0) {
                            this.classList.add('border-green-500');
                            this.classList.remove('border-red-500', 'bg-red-50');
                        }
                    });
                }
            });

            // Make functions globally available
            window.validateForm = validateForm;
            window.showValidationModal = showValidationModal;
            window.closeValidationModal = closeValidationModal;
        });
    </script>
@endsection
