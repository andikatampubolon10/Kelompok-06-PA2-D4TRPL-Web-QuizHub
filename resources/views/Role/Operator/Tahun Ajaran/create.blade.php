@extends('layouts.operator-layout')

@section('title', 'Tambah Tahun Ajaran')
@section('page-title', 'Tambah Tahun Ajaran')
@section('page-description', 'Buat tahun ajaran baru untuk sistem pembelajaran')

@section('content')
    <div class="space-y-6">
        <nav
            class="flex items-center space-x-2 text-sm text-gray-600 bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-100">
            <a href="{{ route('Operator.TahunAjaran.index', ['id_kurikulum' => $kurikulum->id_kurikulum]) }}"
                class="text-green-600 hover:text-green-700 font-medium transition-colors">
                <i class="fas fa-calendar mr-1"></i>
                Tahun Ajaran
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-800 font-medium">Tambah Tahun Ajaran</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white">Form Tambah Tahun Ajaran</h3>
                        <p class="text-green-100 text-sm">Lengkapi informasi tahun ajaran baru</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('Operator.TahunAjaran.store', ['id_kurikulum' => $kurikulum->id_kurikulum]) }}" method="POST" class="p-6" id="tahunAjaranForm">
                @csrf
                
                {{-- Perbaiki penggunaan variabel kurikulum --}}
                <input type="hidden" name="id_kurikulum" value="{{ $kurikulum->id_kurikulum }}">

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="Nama_Tahun_Ajaran" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar text-green-600 mr-2"></i>
                            Nama Tahun Ajaran
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="Nama_Tahun_Ajaran" name="Nama_Tahun_Ajaran"
                                value="{{ old('Nama_Tahun_Ajaran') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('Nama_Tahun_Ajaran') border-red-500 @enderror"
                                placeholder="Masukkan nama tahun ajaran (contoh: 2024/2025)">
                        </div>
                        @error('Nama_Tahun_Ajaran')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format yang disarankan: YYYY/YYYY (contoh: 2024/2025)
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label for="Mulai_Tahun_Ajaran" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Tanggal Mulai Tahun Ajaran
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" id="Mulai_Tahun_Ajaran" name="Mulai_Tahun_Ajaran"
                                value="{{ old('Mulai_Tahun_Ajaran') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('Mulai_Tahun_Ajaran') border-red-500 @enderror">
                        </div>
                        @error('Mulai_Tahun_Ajaran')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="Selesai_Tahun_Ajaran" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                            Tanggal Selesai Tahun Ajaran
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" id="Selesai_Tahun_Ajaran" name="Selesai_Tahun_Ajaran"
                                value="{{ old('Selesai_Tahun_Ajaran') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('Selesai_Tahun_Ajaran') border-red-500 @enderror">
                        </div>
                        @error('Selesai_Tahun_Ajaran')
                            <div class="flex items-center space-x-2 text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Membuat Tahun Ajaran</h4>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Pastikan nama tahun ajaran mengikuti format standar</li>
                                    <li>• Tanggal mulai biasanya dimulai pada bulan Juli atau Agustus</li>
                                    <li>• Durasi tahun ajaran umumnya 10-12 bulan</li>
                                    <li>• Periksa kembali tanggal untuk menghindari kesalahan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        {{-- Perbaiki link kembali dengan parameter kurikulum --}}
                        <a href="{{ route('Operator.TahunAjaran.index', ['id_kurikulum' => $kurikulum->id_kurikulum]) }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Tahun Ajaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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

        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('Mulai_Tahun_Ajaran');
            const endDateInput = document.getElementById('Selesai_Tahun_Ajaran');
            const form = document.getElementById('tahunAjaranForm');

            const today = new Date().toISOString().split('T')[0];
            if (startDateInput) {
                startDateInput.min = today;
            }

            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    const startDate = startDateInput.value;
                    endDateInput.min = startDate;

                    if (endDateInput.value && endDateInput.value < startDate) {
                        endDateInput.value = '';
                    }
                });
            }

            form.addEventListener('submit', function(e) {
                const nama = document.getElementById('Nama_Tahun_Ajaran').value.trim();
                const mulai = startDateInput.value;
                const selesai = endDateInput.value;

                if (!nama || !mulai || !selesai) {
                    e.preventDefault();
                    alert('Semua field wajib diisi!');
                    return;
                }

                if (new Date(selesai) <= new Date(mulai)) {
                    e.preventDefault();
                    alert('Tanggal selesai harus setelah tanggal mulai!');
                    return;
                }
            });

            setTimeout(() => {
                const successAlert = document.getElementById('successAlert');
                const errorAlert = document.getElementById('errorAlert');
                if (successAlert) successAlert.style.display = 'none';
                if (errorAlert) errorAlert.style.display = 'none';
            }, 5000);
        });
    </script>
@endsection
