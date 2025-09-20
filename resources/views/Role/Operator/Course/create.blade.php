@extends('layouts.operator-layout')

@section('title', 'Tambah Kursus')
@section('page-title', 'Tambah Kursus')
@section('page-description', 'Buat kursus baru untuk pembelajaran')

@section('content')
    <div class="space-y-6">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-4 rounded-lg shadow-md flex items-center gap-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('Operator.Course.beranda') }}"
                        class="text-teal-500 hover:text-teal-700 font-semibold flex items-center">
                        Kursus
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                </li>
                <li class="breadcrumb-item active text-gray-600" aria-current="page">
                    Tambah Kursus
                </li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Formulir Tambah Kursus</h3>
                        <p class="text-sm text-gray-600">Lengkapi informasi kursus yang akan dibuat</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form action="{{ route('Operator.Course.store') }}" method="POST" enctype="multipart/form-data"
                    id="courseForm" class="space-y-6">
                    @csrf

                    <!-- Tips Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-6">
                        <div class="flex items-start">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Tips Membuat Kursus</h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                        Gunakan nama kursus yang jelas dan mudah dipahami siswa
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                        Pilih guru yang sesuai dengan mata pelajaran
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                        Upload gambar yang menarik untuk thumbnail kursus
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                        Pastikan kata sandi mudah diingat tapi tetap aman
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Course Name -->
                    <div class="space-y-2">
                        <label for="nama_kursus" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-book text-green-600 mr-2"></i>
                            Nama Kursus <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_kursus" name="nama_kursus" value="{{ old('nama_kursus') }}"
                            placeholder="Masukkan nama kursus yang akan dibuat"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama_kursus') border-red-500 @enderror"
                            required>
                        @error('nama_kursus')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500">Gunakan nama yang jelas dan mudah dipahami</p>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-lock text-blue-600 mr-2"></i>
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                placeholder="Masukkan kata sandi untuk kursus"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password') border-red-500 @enderror"
                                required>
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500">Minimal 8 karakter untuk keamanan</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-lock text-blue-600 mr-2"></i>
                            Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Ulangi kata sandi yang sama"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required>
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">Pastikan kata sandi sama dengan yang di atas</p>
                    </div>

                    <!-- Academic Year (Read-only) -->
                    <div class="space-y-2">
                        <label for="ID_Tahun_Ajaran_display" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                            Tahun Ajaran
                        </label>
                        <input type="hidden" id="ID_Tahun_Ajaran" name="ID_Tahun_Ajaran"
                            value="{{ $tahunAjaranAktif->ID_Tahun_Ajaran }}">
                        <input type="text" id="ID_Tahun_Ajaran_display"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                            value="{{ $tahunAjaranAktif->Nama_Tahun_Ajaran }}" readonly>
                        <p class="text-xs text-gray-500">Tahun ajaran aktif saat ini</p>
                    </div>

                    <!-- Subject Selection -->
                    <div class="space-y-2">
                        <label for="mata_pelajaran" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-book-open text-orange-600 mr-2"></i>
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <select id="mata_pelajaran" name="id_mata_pelajaran"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('id_mata_pelajaran') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach ($mataPelajarans as $mapel)
                                <option value="{{ $mapel->id_mata_pelajaran }}"
                                    {{ old('id_mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                    {{ $mapel->nama_mata_pelajaran }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_mata_pelajaran')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500">Pilih mata pelajaran untuk kursus ini</p>
                    </div>

                    <!-- Teacher Selection -->
                    <div class="space-y-2">
                        <label for="guru" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-chalkboard-teacher text-blue-600 mr-2"></i>
                            Guru Pengampu <span class="text-red-500">*</span>
                        </label>
                        <select id="guru" name="id_guru"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('id_guru') border-red-500 @enderror"
                            required>
                            <option value="">Pilih mata pelajaran terlebih dahulu</option>
                        </select>
                        @error('id_guru')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500">Guru akan muncul setelah memilih mata pelajaran</p>
                    </div>

                    <!-- Class Selection -->
                    <div class="space-y-2">
                        <label for="kelas" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-users text-indigo-600 mr-2"></i>
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select id="kelas" name="id_kelas"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('id_kelas') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $kls)
                                <option value="{{ $kls->id_kelas }}"
                                    {{ old('id_kelas') == $kls->id_kelas ? 'selected' : '' }}>
                                    {{ $kls->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500">Pilih kelas yang akan mengikuti kursus</p>
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-2">
                        <label for="image" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-image text-pink-600 mr-2"></i>
                            Gambar Kursus <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('image') border-red-500 @enderror"
                                required onchange="previewImage(this)">
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <p class="text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB</p>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar:</p>
                            <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden">
                                <img id="previewImg" src="/placeholder.svg" alt="Preview"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-100">
                        <a href="{{ route('Operator.Course.beranda') }}"
                            class="inline-flex items-center px-6 py-3 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                        <div class="flex space-x-3">
                            <button type="button" onclick="previewForm()"
                                class="inline-flex items-center px-6 py-3 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Preview
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Kursus
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Preview Kursus</h3>
                    <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nama Kursus</p>
                                <p id="previewNama" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tahun Ajaran</p>
                                <p id="previewTahun" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                                <p id="previewMapel" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Guru Pengampu</p>
                                <p id="previewGuru" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kelas</p>
                                <p id="previewKelas" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Status Kata Sandi</p>
                                <p id="previewPassword" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                        <div class="mt-4" id="previewImageContainer" style="display: none;">
                            <p class="text-sm font-medium text-gray-600 mb-2">Gambar Kursus</p>
                            <img id="previewModalImg" src="/placeholder.svg" alt="Preview"
                                class="w-32 h-32 object-cover rounded-lg border">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closePreviewModal()"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                    <button onclick="submitFromPreview()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Simpan Kursus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Subject-Teacher filtering
        document.getElementById('mata_pelajaran').addEventListener('change', function() {
            const selectedMapel = this.value;
            const guruSelect = document.getElementById('guru');

            // Clear existing options
            guruSelect.innerHTML = '<option value="">Pilih Guru</option>';

            if (selectedMapel !== '') {
                // Add teachers based on selected subject
                @foreach ($mataPelajarans as $mapel)
                    if (selectedMapel == "{{ $mapel->id_mata_pelajaran }}") {
                        @foreach ($mapel->guru as $guru)
                            const option = document.createElement("option");
                            option.value = "{{ $guru->id_guru }}";
                            option.text = "{{ $guru->nama_guru }}";
                            guruSelect.appendChild(option);
                        @endforeach
                    }
                @endforeach
            } else {
                guruSelect.innerHTML = '<option value="">Pilih mata pelajaran terlebih dahulu</option>';
            }
        });

        // Password toggle functionality
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }

        // Form preview functionality
        function previewForm() {
            const namaKursus = document.getElementById('nama_kursus').value;
            const tahunAjaran = document.getElementById('ID_Tahun_Ajaran_display').value;
            const mataPelajaran = document.getElementById('mata_pelajaran');
            const guru = document.getElementById('guru');
            const kelas = document.getElementById('kelas');
            const password = document.getElementById('password').value;
            const image = document.getElementById('image').files[0];

            // Validate required fields
            if (!namaKursus || !mataPelajaran.value || !guru.value || !kelas.value || !password) {
                alert('Mohon lengkapi semua field yang wajib diisi terlebih dahulu!');
                return;
            }

            // Update preview content
            document.getElementById('previewNama').textContent = namaKursus;
            document.getElementById('previewTahun').textContent = tahunAjaran;
            document.getElementById('previewMapel').textContent = mataPelajaran.options[mataPelajaran.selectedIndex].text;
            document.getElementById('previewGuru').textContent = guru.options[guru.selectedIndex].text;
            document.getElementById('previewKelas').textContent = kelas.options[kelas.selectedIndex].text;
            document.getElementById('previewPassword').textContent = password ? 'Sudah diatur' : 'Belum diatur';

            // Handle image preview
            if (image) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewModalImg').src = e.target.result;
                    document.getElementById('previewImageContainer').style.display = 'block';
                }
                reader.readAsDataURL(image);
            } else {
                document.getElementById('previewImageContainer').style.display = 'none';
            }

            // Show modal
            document.getElementById('previewModal').classList.remove('hidden');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        function submitFromPreview() {
            closePreviewModal();
            document.getElementById('courseForm').submit();
        }

        // Form validation
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Kata sandi dan konfirmasi kata sandi tidak sama!');
                return false;
            }
        });

        // Real-time password matching validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            if (confirmation && password !== confirmation) {
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            } else if (confirmation && password === confirmation) {
                this.classList.add('border-green-500');
                this.classList.remove('border-red-500');
            } else {
                this.classList.remove('border-red-500', 'border-green-500');
            }
        });
    </script>
@endsection
