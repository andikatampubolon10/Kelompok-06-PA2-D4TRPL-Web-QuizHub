@extends('layouts.operator-layout')

@section('title', 'Edit Kursus')
@section('page-title', 'Edit Kursus')
@section('page-description', 'Perbarui informasi kursus yang sudah ada')

@section('content')
    <div class="space-y-6">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('Operator.Course.beranda') }}" 
                       class="text-green-600 hover:text-green-700 font-medium">
                        <i class="fas fa-book-open mr-1"></i>
                        Kursus
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                    <span class="text-gray-900 font-medium">Edit Kursus</span>
                </li>
            </ol>
        </nav>

        <!-- Current Data Info Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-100 border border-blue-200 rounded-xl p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Data Kursus Saat Ini</h3>
                    <p class="text-sm text-gray-600">Informasi kursus yang akan diperbarui</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <p class="text-sm font-medium text-gray-600">Nama Kursus</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $course->nama_kursus }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <p class="text-sm font-medium text-gray-600">Guru Pengampu</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $course->guru->nama_guru }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <p class="text-sm font-medium text-gray-600">Kelas</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $course->kelas->nama_kelas }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $course->mataPelajaran->nama_mata_pelajaran ?? 'Tidak ada' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-edit text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Form Edit Kursus</h3>
                        <p class="text-sm text-gray-600">Perbarui informasi kursus sesuai kebutuhan</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form id="editCourseForm" action="{{ route('Operator.Course.update', $course->id_kursus) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Nama Kursus -->
                            <div>
                                <label for="nama_kursus" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-book-open text-green-600 mr-2"></i>
                                    Nama Kursus <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nama_kursus" 
                                       name="nama_kursus" 
                                       value="{{ old('nama_kursus', $course->nama_kursus) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="Masukkan nama kursus yang menarik"
                                       required>
                                @error('nama_kursus')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Gunakan nama yang jelas dan mudah dipahami</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock text-blue-600 mr-2"></i>
                                    Kata Sandi Baru
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password" 
                                           name="password"
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Kosongkan jika tidak ingin mengubah">
                                    <button type="button" 
                                            onclick="togglePassword('password')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter, kombinasi huruf dan angka</p>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock text-blue-600 mr-2"></i>
                                    Konfirmasi Kata Sandi
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation"
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Ulangi kata sandi baru">
                                    <button type="button" 
                                            onclick="togglePassword('password_confirmation')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="password_confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <div id="password-match-message" class="mt-1 text-xs hidden"></div>
                            </div>

                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="ID_Tahun_Ajaran_display" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-orange-600 mr-2"></i>
                                    Tahun Ajaran
                                </label>
                                <input type="hidden" name="ID_Tahun_Ajaran" value="{{ $tahunAjaranAktif->ID_Tahun_Ajaran }}">
                                <input type="text" 
                                       id="ID_Tahun_Ajaran_display"
                                       value="{{ $tahunAjaranAktif->Nama_Tahun_Ajaran }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                       readonly>
                                <p class="mt-1 text-xs text-gray-500">Tahun ajaran aktif saat ini</p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Mata Pelajaran -->
                            <div>
                                <label for="mata_pelajaran" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-book text-green-600 mr-2"></i>
                                    Mata Pelajaran <span class="text-red-500">*</span>
                                </label>
                                <select id="mata_pelajaran" 
                                        name="id_mata_pelajaran"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                        required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mataPelajarans as $mapel)
                                        <option value="{{ $mapel->id_mata_pelajaran }}"
                                                {{ old('id_mata_pelajaran', $course->id_mata_pelajaran) == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                            {{ $mapel->nama_mata_pelajaran }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_mata_pelajaran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Pilih mata pelajaran yang sesuai</p>
                            </div>

                            <!-- Guru -->
                            <div>
                                <label for="guru" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-chalkboard-teacher text-blue-600 mr-2"></i>
                                    Guru Pengampu <span class="text-red-500">*</span>
                                </label>
                                <select id="guru" 
                                        name="id_guru"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                    <option value="">Pilih Guru</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id_guru }}"
                                                {{ old('id_guru', $course->id_guru) == $guru->id_guru ? 'selected' : '' }}>
                                            {{ $guru->nama_guru }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_guru')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Guru akan disesuaikan dengan mata pelajaran</p>
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-users text-purple-600 mr-2"></i>
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <select id="kelas" 
                                        name="id_kelas"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}"
                                                {{ old('id_kelas', $course->id_kelas) == $kls->id_kelas ? 'selected' : '' }}>
                                            {{ $kls->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Pilih kelas yang akan mengikuti kursus</p>
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-image text-indigo-600 mr-2"></i>
                                    Gambar Kursus
                                </label>
                                <div class="space-y-4">
                                    @if ($course->image)
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ asset('images/' . $course->image) }}" 
                                                 alt="Current Image"
                                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Gambar Saat Ini</p>
                                                <p class="text-xs text-gray-500">Pilih file baru untuk mengubah gambar</p>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" 
                                           id="image" 
                                           name="image"
                                           accept="image/*"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           onchange="previewImage(this)">
                                    <div id="image-preview" class="hidden">
                                        <img id="preview-img" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-500 mt-1">Preview gambar baru</p>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 pt-6 mt-6 border-t border-gray-100">
                        <a href="{{ route('Operator.Course.beranda') }}" 
                           class="inline-flex items-center px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                Perbarui Kursus
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change History Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-history text-yellow-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Perubahan</h3>
                        <p class="text-sm text-gray-600">Informasi tentang pembuatan dan perubahan kursus</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Dibuat</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $course->created_at ? $course->created_at->format('d M Y, H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Terakhir Diperbarui</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $course->updated_at ? $course->updated_at->format('d M Y, H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Preview Perubahan Kursus</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-600">Nama Kursus</p>
                            <p id="previewNama" class="text-lg font-semibold text-gray-900">-</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                            <p id="previewMapel" class="text-lg font-semibold text-gray-900">-</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-600">Guru Pengampu</p>
                            <p id="previewGuru" class="text-lg font-semibold text-gray-900">-</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-600">Kelas</p>
                            <p id="previewKelas" class="text-lg font-semibold text-gray-900">-</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Status Password</p>
                        <p id="previewPassword" class="text-lg font-semibold text-gray-900">-</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closePreview()" 
                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                    <button onclick="submitFromPreview()" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Perbarui Kursus
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
                            <h3 class="text-lg font-semibold text-gray-900">Validasi Form</h3>
                            <p class="text-sm text-gray-600">Harap lengkapi field yang diperlukan</p>
                        </div>
                    </div>
                    <div id="validationErrors" class="space-y-2 mb-6"></div>
                    <div class="flex justify-end">
                        <button onclick="closeValidation()" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Subject-Teacher filtering
        document.getElementById('mata_pelajaran').addEventListener('change', function() {
            const selectedMapel = this.value;
            const guruSelect = document.getElementById('guru');
            const currentGuru = '{{ old('id_guru', $course->id_guru) }}';

            // Clear existing options
            guruSelect.innerHTML = '<option value="">Pilih Guru</option>';

            if (selectedMapel !== '') {
                @foreach ($mataPelajarans as $mapel)
                    if (selectedMapel == "{{ $mapel->id_mata_pelajaran }}") {
                        @foreach ($mapel->guru as $guru)
                            const option = document.createElement("option");
                            option.value = "{{ $guru->id_guru }}";
                            option.text = "{{ $guru->nama_guru }}";
                            if (currentGuru == "{{ $guru->id_guru }}") {
                                option.selected = true;
                            }
                            guruSelect.appendChild(option);
                        @endforeach
                    }
                @endforeach
            } else {
                // Show all teachers if no subject selected
                @foreach ($gurus as $guru)
                    const option = document.createElement("option");
                    option.value = "{{ $guru->id_guru }}";
                    option.text = "{{ $guru->nama_guru }}";
                    if (currentGuru == "{{ $guru->id_guru }}") {
                        option.selected = true;
                    }
                    guruSelect.appendChild(option);
                @endforeach
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

        // Password matching validation
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const message = document.getElementById('password-match-message');
            
            if (!password && !confirmation) {
                message.classList.add('hidden');
                return true;
            }
            
            if (password !== confirmation) {
                message.textContent = 'Password tidak cocok';
                message.className = 'mt-1 text-xs text-red-600';
                message.classList.remove('hidden');
                return false;
            } else if (password) {
                message.textContent = 'Password cocok';
                message.className = 'mt-1 text-xs text-green-600';
                message.classList.remove('hidden');
                return true;
            }
            
            message.classList.add('hidden');
            return true;
        }

        document.getElementById('password').addEventListener('input', checkPasswordMatch);
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }

        // Form validation
        function validateForm() {
            const errors = [];
            
            const namaKursus = document.getElementById('nama_kursus').value.trim();
            if (!namaKursus) {
                errors.push('Nama kursus harus diisi');
            }
            
            const mataPelajaran = document.getElementById('mata_pelajaran').value;
            if (!mataPelajaran) {
                errors.push('Mata pelajaran harus dipilih');
            }
            
            const guru = document.getElementById('guru').value;
            if (!guru) {
                errors.push('Guru harus dipilih');
            }
            
            const kelas = document.getElementById('kelas').value;
            if (!kelas) {
                errors.push('Kelas harus dipilih');
            }
            
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            // Hanya validasi password jika diisi
            if (password && password !== confirmation) {
                errors.push('Konfirmasi password tidak cocok');
            }
            
            console.log('Validation errors:', errors); // Debug log
            return errors;
        }

        // Preview functionality
        function showPreview() {
            console.log('Preview button clicked'); // Debug log
            
            const errors = validateForm();
            
            if (errors.length > 0) {
                console.log('Validation errors:', errors); // Debug log
                showValidationErrors(errors);
                return;
            }
            
            // Populate preview data
            const namaKursus = document.getElementById('nama_kursus').value;
            document.getElementById('previewNama').textContent = namaKursus || 'Tidak diisi';
            
            const mapelSelect = document.getElementById('mata_pelajaran');
            const mapelText = mapelSelect.selectedIndex > 0 ? mapelSelect.options[mapelSelect.selectedIndex].text : 'Tidak dipilih';
            document.getElementById('previewMapel').textContent = mapelText;
            
            const guruSelect = document.getElementById('guru');
            const guruText = guruSelect.selectedIndex > 0 ? guruSelect.options[guruSelect.selectedIndex].text : 'Tidak dipilih';
            document.getElementById('previewGuru').textContent = guruText;
            
            const kelasSelect = document.getElementById('kelas');
            const kelasText = kelasSelect.selectedIndex > 0 ? kelasSelect.options[kelasSelect.selectedIndex].text : 'Tidak dipilih';
            document.getElementById('previewKelas').textContent = kelasText;
            
            const password = document.getElementById('password').value;
            document.getElementById('previewPassword').textContent = password ? 'Password akan diperbarui' : 'Password tidak diubah';
            
            console.log('Opening preview modal'); // Debug log
            document.getElementById('previewModal').classList.remove('hidden');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        function submitFromPreview() {
            document.getElementById('editCourseForm').submit();
        }

        // Validation modal
        function showValidationErrors(errors) {
            const errorsContainer = document.getElementById('validationErrors');
            errorsContainer.innerHTML = '';
            
            errors.forEach(error => {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'flex items-center text-sm text-red-600';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${error}`;
                errorsContainer.appendChild(errorDiv);
            });
            
            document.getElementById('validationModal').classList.remove('hidden');
        }

        function closeValidation() {
            document.getElementById('validationModal').classList.add('hidden');
        }

        // Quick actions
        function duplicateCourse() {
            if (confirm('Apakah Anda ingin membuat duplikasi kursus ini?')) {
                // Implement duplication logic
                alert('Fitur duplikasi akan segera tersedia');
            }
        }

        function showHelp() {
            alert('Bantuan:\n\n1. Isi semua field yang wajib (*)\n2. Pilih mata pelajaran terlebih dahulu untuk memfilter guru\n3. Password baru bersifat opsional\n4. Gunakan preview untuk melihat perubahan sebelum menyimpan');
        }

        // Form submission validation
        document.getElementById('editCourseForm').addEventListener('submit', function(e) {
            const errors = validateForm();
            
            if (errors.length > 0) {
                e.preventDefault();
                showValidationErrors(errors);
            }
        });

        // Initialize form and event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger subject change to populate teachers correctly
            const mapelSelect = document.getElementById('mata_pelajaran');
            if (mapelSelect.value) {
                mapelSelect.dispatchEvent(new Event('change'));
            }
            
            // Add event listener to preview button
            document.getElementById('previewButton').addEventListener('click', showPreview);
        });
    </script>
@endsection
