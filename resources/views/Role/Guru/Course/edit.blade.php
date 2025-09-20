@extends('layouts.guru-layout')

@section('title', 'Tambah Akun')

@section('content')
    <!-- Additional Info Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penambahan Ujian</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Masukkan Nama Ujian</li>
                    <li>• Masukkan Password Masuk dan Keluar Ujian</li>
                    <li>• Pilih Tipe Ujian</li>
                    <li>• Pilih Waktu Mulai dan Selesan Ujian
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container mx-auto bg-white p-8 rounded-lg shadow-lg">

        <form action="{{ route('Guru.Ujian.update', $ujian->id_ujian) }}"  method="POST">
            
            @csrf
 <input type="hidden" name="id_kursus" value="{{ $id_kursus }}">
            <!-- Nama Sekolah Field -->
            <div class="mb-6">
                      <label for="nama_ujian" class="block font-bold mb-2">Judul Ujian</label>
                        <input type="text" name="nama_ujian" value="{{ old('nama_ujian', $ujian->nama_ujian) }}"
                            class="block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>


            <!-- Email Field -->
            <div class="mb-6">
                       <label for="tipe_ujian" class="block font-bold mb-2">Tipe Ujian</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="id_tipe_ujian" value="1"
                                    class="form-radio text-green-500" {{ $ujian->id_tipe_ujian == 1 ? 'checked' : '' }}
                                    required>
                                <span class="ml-2">Kuis</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="id_tipe_ujian" value="2"
                                    class="form-radio text-green-500" {{ $ujian->id_tipe_ujian == 2 ? 'checked' : '' }}
                                    required>
                                <span class="ml-2">Ujian Tengah Semester</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="id_tipe_ujian" value="2"
                                    class="form-radio text-green-500" {{ $ujian->id_tipe_ujian == 3 ? 'checked' : '' }}
                                    required>
                                <span class="ml-2">Ujian Akhir Semester</span>
                            </label>
                        </div>
                    </div>

            <!-- Password Field -->
            <div class="mb-6">
                      <label for="acak" class="block font-bold mb-2">Acak Soal dan Pilihan</label>
                        <select name="acak" class="block w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="Aktif" {{ $ujian->acak == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ $ujian->acak == 'Tidak Aktif' ? 'selected' : '' }}>Tidak
                                Aktif</option>
                        </select>
                    </div>

              

                    <div class="mb-6">
                     <label for="status_jawaban" class="block font-bold mb-2">Status Jawaban</label>
                        <select name="status_jawaban" class="block w-full p-2 border border-gray-300 rounded-md"
                            required>
                            <option value="Aktif" {{ $ujian->status_jawaban == 'Aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="Tidak Aktif"
                                {{ $ujian->status_jawaban == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mb-6">
                       <label for="Waktu_Mulai" class="block font-bold mb-2">Waktu Mulai</label>
                        <input type="datetime-local" name="Waktu_Mulai"
                            value="{{ old('Waktu_Mulai', \Carbon\Carbon::parse($ujian->Waktu_Mulai)->format('Y-m-d\TH:i')) }}"
                            class="block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-6">
                        <label for="Waktu_Selesai" class="block font-bold mb-2">Waktu Selesai</label>
                        <input type="datetime-local" name="Waktu_Selesai"
                            value="{{ old('Waktu_Selesai', \Carbon\Carbon::parse($ujian->Waktu_Selesai)->format('Y-m-d\TH:i')) }}"
                            class="block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>                 






          
              
                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Guru.Ujian.index', ['id_kursus' => $course->id_kursus]) }}"
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

         document.addEventListener('DOMContentLoaded', function() {
        const waktuMulaiInput = document.getElementById('waktu_mulai');
        const waktuSelesaiInput = document.getElementById('waktu_selesai');

        // Set waktu minimal untuk waktu mulai
        const now = new Date();
        const nowString = now.toISOString().slice(0, 16); // YYYY-MM-DDTHH:MM
        waktuMulaiInput.min = nowString;

        // Set waktu minimal untuk waktu selesai (harus setelah waktu mulai)
        waktuMulaiInput.addEventListener('change', function() {
            waktuSelesaiInput.min = this.value;
        });

        // Optionally, set waktu selesai minimal saat halaman dimuat
        waktuSelesaiInput.min = nowString;
    });
        // Dropdown toggle script
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdown-menu");
            dropdown.classList.toggle("show");
        }
        document.addEventListener('DOMContentLoaded', function() {
                var startDateInput = document.getElementById('start_date');
                var endDateInput = document.getElementById('end_date');

                var today = new Date();
                var todayString = today.toISOString().split('T')[0]; // Format YYYY-MM-DD

                // Set tanggal mulai tidak bisa lebih awal dari hari ini
                if (startDateInput) {
                    startDateInput.min = todayString;
                }

                // Set tanggal selesai tidak bisa lebih awal dari tanggal mulai
                if (startDateInput && endDateInput) {
                    startDateInput.addEventListener('change', function() {
                        var startDate = startDateInput.value;
                        endDateInput.min =
                        startDate; // Set tanggal selesai tidak bisa lebih awal dari tanggal mulai
                    });
                }
            });
              function toggleDropdown() {
                const dropdown = document.getElementById("dropdown-menu");
                dropdown.classList.toggle("show");
            }

            document.addEventListener('DOMContentLoaded', function() {
                var startDateInput = document.getElementById('start_date');
                var endDateInput = document.getElementById('end_date');

                var today = new Date();
                var todayString = today.toISOString().split('T')[0]; // Format YYYY-MM-DD

                // Set tanggal mulai tidak bisa lebih awal dari hari ini
                if (startDateInput) {
                    startDateInput.min = todayString;
                }

                // Set tanggal selesai tidak bisa lebih awal dari tanggal mulai
                if (startDateInput && endDateInput) {
                    startDateInput.addEventListener('change', function() {
                        var startDate = startDateInput.value;
                        endDateInput.min =
                            startDate; // Set tanggal selesai tidak bisa lebih awal dari tanggal mulai
                    });
                }
            });
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