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
                      <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Edit Nilai Persentase</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Edit Persentase Kuis</li>
                    <li>• Edit Persentase UTS</li>
                    <li>• Edit Persentase UAS</li>
                    <li>• Jumlah Ketiga Persentase Harus 100</li>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container mx-auto bg-white p-8 rounded-lg shadow-lg">

        <form action="{{ route('Guru.Persentase.update', $id_kursus) }}" method="POST">
            @csrf
<input type="hidden" name="id_kursus" value="{{ $id_kursus }}">
            <!-- Nama Sekolah Field -->
            <div class="mb-6">
                        <label for="persentase_kuis"
                            class="text-sm font-medium text-gray-700 flex-shrink-0 w-full sm:w-auto">Persentase
                            Kuis</label>
                        <input type="number" id="persentase_kuis" name="persentase_kuis"
                            value="{{ old('persentase_kuis', $persentase->where('id_tipe_persentase', 1)->first()->persentase ?? '') }}"
                            required
                            class="w-full sm:max-w-xs px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right">
                    </div>
            <!-- Email Field -->
            <div class="mb-6">
                  <label for="persentase_UTS"
                            class="text-sm font-medium text-gray-700 flex-shrink-0 w-full sm:w-auto">Persentase
                            UTS</label>
                        <input type="number" id="persentase_UTS" name="persentase_UTS"
                            value="{{ old('persentase_UTS', $persentase->where('id_tipe_persentase', 2)->first()->persentase ?? '') }}"
                            required min="0" max="100" step="0.01"
                            class="w-full sm:max-w-xs px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right">
                    </div>
            <!-- Password Field -->
            <div class="mb-6">
                          <label for="persentase_UAS"
                            class="text-sm font-medium text-gray-700 flex-shrink-0 w-full sm:w-auto">Persentase
                            UAS</label>
                        <input type="number" id="persentase_UAS" name="persentase_UAS"
                            value="{{ old('persentase_UAS', $persentase->where('id_tipe_persentase', 3)->first()->persentase ?? '') }}"
                            required min="0" max="100" step="0.01"
                            class="w-full sm:max-w-xs px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right">
                    </div>
                </div>

                    <div id="warning-message" class="text-sm text-yellow-600 font-semibold mt-1" style="display:none;">
                        Jumlah persentase tidak boleh lebih dari 100%.
                    </div>
                </form>






          
              
                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Guru.Nilai.index') }}"
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

          document.querySelector('form').addEventListener('input', function() {
                    let kuis = parseFloat(document.getElementById('persentase_kuis').value) || 0;
                    let uts = parseFloat(document.getElementById('persentase_UTS').value) || 0;
                    let uas = parseFloat(document.getElementById('persentase_UAS').value) || 0;

                    let total = kuis + uts + uas;

                    if (total > 100) {
                        document.getElementById('warning-message').style.display = 'block';
                    } else {
                        document.getElementById('warning-message').style.display = 'none';
                    }
                });
                // Dropdown toggle script
                const dropdownButton = document.getElementById('dropdownButton');
                const dropdownMenu = document.getElementById('dropdownMenu');
                const dropdownIcon = document.getElementById('dropdownIcon');

                dropdownButton.addEventListener('click', () => {
                    const isExpanded = dropdownButton.getAttribute('aria-expanded') === 'true';
                    dropdownButton.setAttribute('aria-expanded', !isExpanded);

                    if (dropdownMenu.style.maxHeight && dropdownMenu.style.maxHeight !== '0px') {
                        dropdownMenu.style.maxHeight = '0px';
                        dropdownMenu.style.paddingTop = '0';
                        dropdownMenu.style.paddingBottom = '0';
                        dropdownIcon.style.transform = 'rotate(0deg)';
                    } else {
                        dropdownMenu.style.maxHeight = dropdownMenu.scrollHeight + 'px';
                        dropdownMenu.style.paddingTop = '0.5rem';
                        dropdownMenu.style.paddingBottom = '0.5rem';
                        dropdownIcon.style.transform = 'rotate(180deg)';
                    }
                });

                // Close dropdown if clicked outside
                window.addEventListener('click', (e) => {
                    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.style.maxHeight = '0px';
                        dropdownMenu.style.paddingTop = '0';
                        dropdownMenu.style.paddingBottom = '0';
                        dropdownButton.setAttribute('aria-expanded', 'false');
                        dropdownIcon.style.transform = 'rotate(0deg)';
                    }
                });

                // Initialize dropdown closed
                dropdownMenu.style.maxHeight = '0px';
                dropdownMenu.style.overflow = 'hidden';
                dropdownMenu.style.transition = 'max-height 0.3s ease, padding 0.3s ease';

                function toggleDropdown() {
                    const dropdown = document.getElementById("dropdown-menu");
                    dropdown.classList.toggle("show");
                }

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