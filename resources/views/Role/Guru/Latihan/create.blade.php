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
                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penambahan Topik Latihan</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Masukkan Topik</li>
                    <li>• Pilih Kelas yang sesuai</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container mx-auto bg-white p-8 rounded-lg shadow-lg">

        <form action="{{ route('Guru.Latihan.store') }}" method="POST">
            @csrf

            <!-- Nama Sekolah Field -->
            <div class="mb-6">
                 <label for="Topik" class="block font-bold mb-2">Topik Latihan</label>
                        <input type="text" name="Topik" class="block w-full p-2 border border-gray-300 rounded-md" >
                        @error('Topik')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="id_kurikulum" class="block font-bold mb-2">Pilih Kurikulum</label>
                        <select name="id_kurikulum" id="id_kurikulum" class="block w-full p-2 border border-gray-300 rounded-md" >
                            <option value="" disabled selected>Pilih kurikulum</option>
                            @foreach ($kurikulums as $k)
                                <option value="{{ $k->id_kurikulum }}" {{ old('id_kurikulum') == $k->id_kurikulum ? 'selected' : '' }}>
                                    {{ $k->nama_kurikulum }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kurikulum')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

            <!-- Confirm Password Field -->
            <div class="mb-6">
               <label for="id_mata_pelajaran" class="block font-bold mb-2">Pilih Mata Pelajaran</label>
                        <select name="id_mata_pelajaran" id="id_mata_pelajaran" class="block w-full p-2 border border-gray-300 rounded-md">
                            <option value="" disabled selected>Pilih Mata Pelajaran</option>
                            @foreach ($mataPelajarans as $mapel)
                                <option value="{{ $mapel->id_mata_pelajaran }}" data-kurikulum="{{ $mapel->id_kurikulum }}">
                                    {{ $mapel->nama_mata_pelajaran }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_mata_pelajaran')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

            <!-- Durasi Field -->
            <div class="mb-6">
                <label for="id_kelas" class="block font-bold mb-2">Pilih Kelas</label>
                        <select name="id_kelas" class="block w-full p-2 border border-gray-300 rounded-md">
                            <option value="" disabled selected>Pilih kelas</option>
                            @foreach ($kelas as $class)
                                <option value="{{ $class->id_kelas }}">{{ $class->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- <div class="mb-4">
                        <label for="acak" class="block font-bold mb-2">Acak Soal dan Pilihan</label>
                        <select name="acak" class="block w-full p-2 border border-gray-300 rounded-md">
                            <option value="" disabled selected>Pilih opsi</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                        @error('acak')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    {{-- <div class="mb-4">
                        <label for="status_jawaban" class="block font-bold mb-2">Status Jawaban</label>
                        <select name="status_jawaban" class="block w-full p-2 border border-gray-300 rounded-md">
                            <option value="" disabled selected>Pilih opsi</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                        @error('status_jawaban')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    {{-- <div class="mb-4">
                        <label for="grade" class="block font-bold mb-2">Grade</label>
                        <input type="number" name="grade" class="block w-full p-2 border border-gray-300 rounded-md" >
                        @error('grade')
                            <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}

              
                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Guru.Latihan.index') }}"
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
             function toggleDropdown() {
                const menu = document.getElementById('dropdown-menu');
                menu.classList.toggle('show');
            }

            // Close dropdown if clicked outside
            window.addEventListener('click', function(e) {
                const dropdown = document.getElementById('userDropdown');
                const menu = document.getElementById('dropdown-menu');
                if (!dropdown.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });

            document.getElementById('id_kurikulum').addEventListener('change', function() {
                const selectedKurikulum = this.value;
                const mapelOptions = document.querySelectorAll('#id_mata_pelajaran option');

                mapelOptions.forEach(option => {
                    const itemKurikulum = option.getAttribute('data-kurikulum');
                    if (selectedKurikulum === '' || itemKurikulum === selectedKurikulum) {
                        option.style.display = 'block'; // Show the option
                    } else {
                        option.style.display = 'none'; // Hide the option
                    }
                });
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