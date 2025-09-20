@extends('layouts.admin-layout')

@section('title', 'Tambah Akun')

@section('content')
    <!-- Additional Info Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penambahan Bisnis</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Masukkan Nama Sekolah</li>
                    <li>• Masukkan Jumlah Pendapatan</li>
                    <li>• Masukkan Surat Perjanjian dalam bentuk PDF/Word</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container mx-auto bg-white p-8 rounded-lg shadow-lg">

        <form id="form-bisnis" action="{{ route('Admin.Bisnis.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Sekolah Field -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Nama Sekolah<span class="text-red-500">*</span></label>
                <input type="text" name="nama_sekolah" class="w-full border border-gray-400 p-2 rounded-lg" >
                @error('nama_sekolah')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jumlah Pendapatan Field -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Jumlah Pendapatan<span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_pendapatan" class="w-full border border-gray-400 p-2 rounded-lg">
                @error('jumlah_pendapatan')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Perjanjian Field -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Perjanjian (PDF/Word) <span class="text-red-500">*</span></label>
                <input type="file" name="perjanjian" class="w-full border border-gray-400 p-2 rounded-lg" accept=".pdf,.doc,.docx">
                @error('perjanjian')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle"></i>
                    <span>Semua field yang bertanda (*) wajib diisi</span>
                </div>

                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('Admin.Bisnis.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div id="successAlert" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
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
        <div id="errorAlert" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
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

        // Auto close alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
        }, 5000);
    </script>
    
    @section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelector('#form-bisnis').addEventListener('submit', function (e) {
        const namaSekolah = document.querySelector('input[name="nama_sekolah"]').value.trim();
        const jumlahPendapatan = document.querySelector('input[name="jumlah_pendapatan"]').value.trim();
        const perjanjian = document.querySelector('input[name="perjanjian"]').value;

        if (!namaSekolah || !jumlahPendapatan || !perjanjian) {
            e.preventDefault(); // Mencegah form terkirim

            Swal.fire({
                icon: 'warning',
                title: 'Form Tidak Lengkap!',
                text: 'Semua field yang bertanda * wajib diisi.',
                confirmButtonColor: '#3085d6'
            });
        }
    });
</script>
@endsection

@endsection